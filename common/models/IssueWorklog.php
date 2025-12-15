<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\base\InvalidParamException;
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
	private $_timeLogged;
	
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
			[['timeLogged'], 'safe'],
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
	
	public function setTimeLogged($value)
	{
		$this->_timeLogged = $value;
		$this->time_spent = $this->parseTimeToSeconds($value);
	}

	public function getTimeLogged()
	{
		// Можно реализовать обратное преобразование, если нужно
		return $this->_timeLogged;
	}

	public function parseTimeToSeconds($input)
	{
		if (empty($input)) {
			return 0;
		}

		$seconds = 0;
		// Поддержка: 3w 4d 12h 30m 45s
		$patterns = [
			'w' => 7 * 24 * 3600,
			'd' => 24 * 3600,
			'h' => 3600,
			'm' => 60,
			's' => 1,
		];

		foreach ($patterns as $unit => $multiplier) {
			if (preg_match_all('/(\d+)\s*' . preg_quote($unit, '/') . '/i', $input, $matches)) {
				foreach ($matches[1] as $value) {
					$seconds += (int)$value * $multiplier;
				}
			}
		}

		return $seconds;
	}
}