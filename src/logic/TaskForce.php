<?php

namespace borpheus\logic;

use borpheus\logic\action\CancelAction;
use borpheus\logic\action\CompleteAction;
use borpheus\logic\action\RefuseAction;
use borpheus\logic\action\RespondAction;
use borpheus\exception\StatusException;
use borpheus\exception\ActionException;
use borpheus\utils\MyHelper;
use yii\mutex\FileMutex;

class TaskForce
{

    const STATUS_NEW = 'NEW';
    const STATUS_IN_WORK = 'IN_WORK';
    const STATUS_CANCELED = 'CANCELED';
    const STATUS_COMPLETE = 'COMPLETED';
    const STATUS_FAILED = 'FAILED';
    const CUSTOMER_ROLE_NAME = 'customer';
    const DOER_ROLE_NAME = 'doer';

    private string $status;
    private ?int $doer_id;
    private ?int $customer_id;
    private ?string $role = null;

    /**
     * @param string $status
     * @param int $customer_id
     * @param int $doer_id
     */
    public function __construct(
        string $status,
        int $customer_id,
        ?int $doer_id
    ) {
        $this->set_status($status);
        $this->customer_id = $customer_id;
        $this->doer_id = $doer_id;
    }

    /**
     * @return string[]
     */
    public static function get_status_map(): array
    {
        return [
            self::STATUS_NEW => 'Новый',
            self::STATUS_IN_WORK => 'В работе',
            self::STATUS_CANCELED => 'Отменен',
            self::STATUS_COMPLETE => 'Выполнен',
            self::STATUS_FAILED => 'Провален',
        ];
    }


    /**
     * @return string[]
     */
    public function get_action_map(): array
    {
        return [
            RespondAction::returnSysActionName() => RespondAction::returnActionName(),
            CancelAction::returnSysActionName() => CancelAction::returnActionName(),
            CompleteAction::returnSysActionName() => CompleteAction::returnActionName(),
            RefuseAction::returnSysActionName() => RefuseAction::returnActionName(),
        ];
    }

    /**
     * @param string $next_action
     * @return string[]|null
     * @throws StatusException
     */
    public function get_status_for_next_action(string $next_action): ?array
    {
        $map = [
            RespondAction::returnSysActionName() => [self::STATUS_IN_WORK],
            CancelAction::returnSysActionName() => [self::STATUS_CANCELED],
            CompleteAction::returnSysActionName() => [self::STATUS_COMPLETE],
            RefuseAction::returnSysActionName() => [self::STATUS_FAILED],
        ];
        if (!$map[$next_action]) {
            throw new ActionException('Название следующего действия некорректно');
        }
        return $map[$next_action];
    }

    /**
     * @param string $status
     * @throws StatusException
     */
    private function set_status(string $status)
    {
        $availableStatuses = [
            self::STATUS_NEW,
            self::STATUS_IN_WORK,
            self::STATUS_CANCELED,
            self::STATUS_COMPLETE,
            self::STATUS_FAILED
        ];

        if (!in_array($status, $availableStatuses)) {
            throw new StatusException("Данного статуса не существует");
        }
        $this->status = $status;
    }

    /**
     * @param int $user_id
     */
    private function check_role(string $role)
    {
        $availableRoles = [self::DOER_ROLE_NAME, self::CUSTOMER_ROLE_NAME];

        if (!in_array($role, $availableRoles)) {
            throw new StatusActionException("Неизвестная роль: $role");
        }
    }

    /**
     * @return array|null
     */
    private function get_actions_for_status(): ?array
    {
        $map = [
            self::STATUS_NEW => [CancelAction::class, RespondAction::class],
            self::STATUS_IN_WORK => [RefuseAction::class, CompleteAction::class],
        ];
        return $map[$this->status] ?? array();
    }

    /**
     * @return array|null
     */
    private function get_actions_for_role(string $role, int $user_id): ?array
    {
        if ($role === self::CUSTOMER_ROLE_NAME && $this->customer_id === $user_id) {
            $map = [
                CancelAction::class,
                CompleteAction::class
            ];
        } elseif ($role === self::DOER_ROLE_NAME) {
            $map = [
                RespondAction::class,
            ];
            if ($this->doer_id === $user_id) {
                $map[] = RefuseAction::class;
            }
        }
        return $map ?? array();
    }

    /**
     * @param int $user_id
     * @return array
     */
    public function get_avaliable_actions(string $role, int $user_id): array
    {
        $this->check_role($role);
        $actions_for_status = $this->get_actions_for_status();
        $actions_for_role = $this->get_actions_for_role($role, $user_id);
        return array_values((array_intersect($actions_for_status, $actions_for_role)));
    }
}
