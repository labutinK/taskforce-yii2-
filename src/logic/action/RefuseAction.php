<?php

namespace borpheus\logic\action;

use borpheus\logic\action\Action;

class RefuseAction extends Action
{
    public static function returnActionName(): string
    {
        return 'Задание провалено';
    }

    public static function returnSysActionName(): string
    {
        return 'refusal';
    }

    public static function checkRights($id_doer, $id_cust, $id_user): bool
    {
        return ($id_doer === $id_user);
    }
}
