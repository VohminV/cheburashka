<?php

namespace common\models;

use yii\db\ActiveRecord;

class IssueHistory extends ActiveRecord
{
    public static function tableName()
    {
        return 'public.issue_history';
    }

    public function getIssue()
    {
        return $this->hasOne(Issue::class, ['id' => 'issue_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}