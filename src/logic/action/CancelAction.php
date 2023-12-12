<?php

namespace borpheus\logic\action;

use borpheus\logic\action\Action;

class CancelAction extends Action
{

    public static function returnActionName(): string
    {
        return 'Отменить задачу';
    }

    public static function returnSysActionName(): string
    {
        return 'act_cancel';
    }

    public static function checkRights($id_doer, $id_cust, $id_user): bool
    {
        return ($id_cust === $id_user);
    }
}
