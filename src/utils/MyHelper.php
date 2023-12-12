<?php

namespace borpheus\utils;

use app\models\Replies;
use app\models\Tasks;
use app\models\Users;
use borpheus\logic\TaskForce;
use borpheus\logic\action\CancelAction;
use borpheus\logic\action\RespondAction;
use borpheus\logic\action\RefuseAction;
use borpheus\logic\action\CompleteAction;
use yii\helpers\Url;
use yii\helpers\Html;
use Yii;

const CUSTOMER_ROLE_NAME = 'customer';
const DOER_ROLE_NAME = 'doer';

class MyHelper
{

    public static function userCanCancel(Tasks $task, Users $user)
    {
        $tasksInfo = new TaskForce($task->status->name, $task->client_id, $task->performer_id);
        $userRole = $user->isCustomer() ? CUSTOMER_ROLE_NAME : DOER_ROLE_NAME;
        $avaliableActions = $tasksInfo->get_avaliable_actions($userRole, $user->id);
        foreach ($avaliableActions as $action) {
            if ($action::returnSysActionName() === CancelAction::returnSysActionName()) {
                return true;
            }
        }
        return false;
    }

    public static function userCanComplete(Tasks $task, Users $user)
    {
        $tasksInfo = new TaskForce($task->status->name, $task->client_id, $task->performer_id);
        $userRole = $user->isCustomer() ? CUSTOMER_ROLE_NAME : DOER_ROLE_NAME;
        $avaliableActions = $tasksInfo->get_avaliable_actions($userRole, $user->id);
        foreach ($avaliableActions as $action) {
            if ($action::returnSysActionName() === CompleteAction::returnSysActionName()) {
                return true;
            }
        }
        return false;
    }

    public static function userCanRefuse(Tasks $task, Users $user)
    {
        $tasksInfo = new TaskForce($task->status->name, $task->client_id, $task->performer_id);
        $userRole = $user->isCustomer() ? CUSTOMER_ROLE_NAME : DOER_ROLE_NAME;
        $avaliableActions = $tasksInfo->get_avaliable_actions($userRole, $user->id);
        foreach ($avaliableActions as $action) {
            if ($action::returnSysActionName() === RefuseAction::returnSysActionName()) {
                return true;
            }
        }
        return false;
    }

    public static function userCanRespond(Tasks $task, Users $user)
    {
        if (!Replies::findOne(['task_id' => $task->id, 'user_id' => $user])) {
            $tasksInfo = new TaskForce($task->status->name, $task->client_id, $task->performer_id);
            $userRole = $user->isCustomer() ? CUSTOMER_ROLE_NAME : DOER_ROLE_NAME;
            $avaliableActions = $tasksInfo->get_avaliable_actions($userRole, $user->id);
            foreach ($avaliableActions as $action) {
                if ($action::returnSysActionName() === RespondAction::returnSysActionName()) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function getAvaliableButtons(Tasks $task, Users $user)
    {
        $buttons = [];

        $colorsMap = [
            CancelAction::returnSysActionName() => 'orange',
            RespondAction::returnSysActionName() => 'blue',
            RefuseAction::returnSysActionName() => 'pink',
            CompleteAction::returnSysActionName() => 'yellow'
        ];

        try {
            $tasksInfo = new TaskForce($task->status->name, $task->client_id, $task->performer_id);
            $userRole = $user->isCustomer() ? CUSTOMER_ROLE_NAME : DOER_ROLE_NAME;
            $avaliableActions = $tasksInfo->get_avaliable_actions($userRole, $user->id);


            foreach ($avaliableActions as $action) {
                $color = $colorsMap[$action::returnSysActionName()];
                $label = $action::returnActionName();

                $options = [
                    'data-action' => $action::returnSysActionName(),
                    'class' => 'button action-btn button--' . $color
                ];


                if ($action::returnSysActionName() === 'act_cancel') {
                    $options['href'] = Url::to(['tasks/cancel', 'id' => $task->id]);
                }
                elseif($action::returnSysActionName() === 'act_response' && Replies::findOne(['task_id' => $task->id, 'user_id' => $user])){
                    continue;
                }

                $btn = Html::tag('a', $label, $options);

                $buttons[] = $btn;
            }
        } catch (StatusActionException $e) {
            error_log($e->getMessage());
        }

        return $buttons;

    }

    static function pre($arr)
    {
        echo '<pre>', print_r($arr, 1), '</pre>';
    }

    static function FileSizeConvert($bytes)
    {
        $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "Тб",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "Гб",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "Мб",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "Кб",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "б",
                "VALUE" => 1
            ),
        );

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
                break;
            }
        }
        return $result;
    }


}