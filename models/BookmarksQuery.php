<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Bookmarks]].
 *
 * @see Bookmarks
 */
class BookmarksQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Bookmarks[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Bookmarks|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
