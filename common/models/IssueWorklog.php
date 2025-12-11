<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $issue_id
 * @property int $user_id
 * @property int $time_spent  // в секундах или минутах — как ты решишь
 * @property string|null $comment
 * @property string $created_at
 */
class IssueWorklog extends ActiveRecord
{
    public static function tableName()
    {
        return 'issue_worklog';
    }

    public function rules()
    {
        return [
            [['issue_id', 'user_id', 'time_spent'], 'required'],
            [['issue_id', 'user_id', 'time_spent'], 'integer'],
            [['comment'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    // Связи
    public function getIssue()
    {
        return $this->hasOne(Issue::class, ['id' => 'issue_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    // Форматирование времени для отображения: "1ч 30м"
    public function getFormattedTime()
    {
        $minutes = (int)($this->time_spent / 60);
        $hours = (int)($minutes / 60);
        $mins = $minutes % 60;

        if ($hours > 0) {
            return $hours . 'ч ' . ($mins > 0 ? $mins . 'м' : '');
        }
        return $mins . 'м';
    }
}