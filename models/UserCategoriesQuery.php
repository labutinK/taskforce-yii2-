<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[User_categories]].
 *
 * @see User_categories
 */
class UserCategoriesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return User_categories[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return User_categories|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
