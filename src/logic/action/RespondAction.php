<?php

namespace borpheus\logic\action;

use borpheus\logic\action\Action;

class RespondAction extends Action
{
    public static function returnActionName(): string
    {
        return 'Откликнуться';
    }

    public static function returnSysActionName(): string
    {
        return 'act_response';
    }

    public static function checkRights($id_doer, $id_cust, $id_user): bool
    {
        return ($id_doer === $id_user);
    }
}
