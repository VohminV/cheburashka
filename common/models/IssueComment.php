<?php

namespace common\models;

use yii\db\ActiveRecord;

class IssueComment extends ActiveRecord
{
    public static function tableName()
    {
        return 'public.issue_comment';
    }

    public function rules()
    {
        return [
            [['issue_id', 'user_id', 'body'], 'required'],
            [['issue_id', 'user_id'], 'integer'],
            [['body'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'issue_id' => 'Задача',
            'user_id' => 'Пользователь',
            'body' => 'Комментарий',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлён',
        ];
    }

    // Связь: комментарий принадлежит задаче
    public function getIssue()
    {
        return $this->hasOne(Issue::class, ['id' => 'issue_id']);
    }

    // Связь: комментарий написан пользователем
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}