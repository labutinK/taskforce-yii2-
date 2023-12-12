<?php

namespace borpheus\logic\action;

use borpheus\logic\action\Action;


class CompleteAction extends Action
{
    public static function returnActionName(): string
    {
        return 'Задание выполнено';
    }

    public static function returnSysActionName(): string
    {
        return 'completion';
    }

    public static function checkRights($id_doer, $id_cust, $id_user): bool
    {
        return ($id_cust === $id_user);
    }
}
