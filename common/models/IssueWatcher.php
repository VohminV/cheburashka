<?php

namespace common\models;

use yii\db\ActiveRecord;

class IssueWatcher extends ActiveRecord
{
    public static function tableName()
    {
        return 'public.issue_watcher';
    }

    public function rules()
    {
        return [
            [['issue_id', 'user_id'], 'required'],
            [['issue_id', 'user_id'], 'integer'],
            [['issue_id', 'user_id'], 'unique', 'targetAttribute' => ['issue_id', 'user_id']],
            ['issue_id', 'exist', 'targetClass' => Issue::class, 'targetAttribute' => 'id', 'on' => 'default'],
            ['user_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id', 'on' => 'default'],
        ];
    }

    // Связи (опционально)
    public function getIssue()
    {
        return $this->hasOne(Issue::class, ['id' => 'issue_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}