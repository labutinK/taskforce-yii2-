<?php

namespace borpheus\utils;


class DateBack
{
    public static function get_diff($timestamp): string
    {
        $timestamp = strtotime($timestamp);

        $currentTime = time();

        $diff = $currentTime - $timestamp; // Разница в секундах

        if ($diff < 60) {
            $result = $diff . ' секунд';
        } elseif ($diff < 3600) {
            $result = floor($diff / 60) . ' минут';
        } elseif ($diff < 86400) {
            $result = floor($diff / 3600) . ' часов';
        } else {
            $result = floor($diff / 86400) . ' дней';
        }
        return $result;
    }

    public static function getAge($bd)
    {
        $birthDate = new \DateTime($bd);
        $currentDate = new \DateTime('now');
        $age = $currentDate->diff($birthDate)->y;
        return $age;
    }

}