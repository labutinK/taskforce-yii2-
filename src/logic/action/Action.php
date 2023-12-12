<?php

namespace borpheus\logic\action;

abstract class Action
{
    abstract public static function returnActionName(): string;

    abstract public static function returnSysActionName(): string;

    abstract public static function checkRights(?int $id_doer, ?int $id_cust, ?int $id_user): bool;
}
