<?php

namespace borpheus\logic;

use app\models\Users;
use app\models\Opinions;
use app\models\UserSettings;
use borpheus\utils\MyHelper;
use Yii;

class UserRating
{

    /**
     * @param $userId
     * @return float
     */
    static public function getUserRating($userId)
    {
        $user = Users::findOne($userId);
        $rating = 0;
        if ($user) {
            $userOpinions = Opinions::find()->where(['performer_id' => $userId])->all();
            $userSettings = UserSettings::find()->select(['refused_counter'])->where(['user_id' => $userId])->one();
            $tasksRefused = 0;
            if ($userSettings) {
                $tasksRefused = $userSettings->refused_counter;
            }
            if (!empty($userOpinions)) {
                $sum = 0;
                foreach ($userOpinions as $opinion) {
                    $sum += $opinion->rate;
                }
                $rating = $sum / (count($userOpinions) + $tasksRefused);
            }
        }
        return round($rating, 2);
    }

    /**
     * @param $userId
     * @return Opinions[]|array
     */
    static public function getUserOpinions($userId)
    {
        $user = Users::findOne($userId);
        if ($user) {
            $userOpinions = Opinions::find()->where(['performer_id' => $userId])->all();
            return $userOpinions;
        }
        return [];
    }

    static function compareValues($a, $b)
    {
        $a = floatval($a['rating']);
        $b = floatval($b['rating']);

        if ($a == $b) {
            return 0;
        }

        return ($a > $b) ? -1 : 1;
    }

    static public function getPlaceInRating($userId)
    {
        $performers = Users::find()->where(['role_id' => 2])->all();
        $performersarray = array();
        foreach ($performers as $perf) {
            $userOpinions = Opinions::find()->where(['performer_id' => $perf->id])->all();
            $userSettings = UserSettings::find()->select(['refused_counter'])->where(['user_id' => $perf->id])->one();
            $tasksRefused = 0;
            $rating = 0;
            if ($userSettings) {
                $tasksRefused = $userSettings->refused_counter;
            }
            if (!empty($userOpinions)) {
                $sum = 0;
                foreach ($userOpinions as $opinion) {
                    $sum += $opinion->rate;
                }
                $rating = $sum / (count($userOpinions) + $tasksRefused);
            }
            $performersarray[] = array(
                'perf_id' => $perf->id,
                'rating' => round($rating, 2)
            );
        }


// Сортировка массива по значениям
        usort($performersarray, ['borpheus\logic\UserRating', 'compareValues']);

// Вывод отсортированного массива
        return array_search(Yii::$app->user->id, array_column($performersarray, 'perf_id')) + 1;
    }


}