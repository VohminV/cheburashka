<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\IssueHistory;
use common\models\IssueAttachment;
use common\models\Issue;
class Issue extends ActiveRecord
{
    public static function tableName()
    {
        return 'public.issue';
    }

    public function rules()
    {
        return [
            // Обязательные поля (issue_key НЕ входит!)
            [['project_id', 'issue_type_id', 'status_id', 'summary', 'reporter_id'], 'required'],

            // Типы данных
            [['project_id', 'issue_type_id', 'status_id', 'assignee_id', 'reporter_id', 'parent_issue_id', 'priority_id', 'resolution_id'], 'integer'],
            [['description'], 'string'],
            [['summary'], 'string', 'max' => 255],
            [['issue_key'], 'string', 'max' => 20],

            // Даты — безопасны для загрузки
            [['created_at', 'updated_at'], 'safe'],

            // Уникальность issue_key в рамках проекта
            [['issue_key'], 'unique', 'targetAttribute' => ['project_id', 'issue_key'], 'message' => 'Ключ задачи должен быть уникальным в рамках проекта'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Проект',
            'issue_type_id' => 'Тип задачи',
            'status_id' => 'Статус',
            'issue_key' => 'Ключ задачи',
            'summary' => 'Краткое описание',
            'description' => 'Описание',
            'assignee_id' => 'Назначена',
            'reporter_id' => 'Автор',
            'parent_issue_id' => 'Родительская задача',
            'created_at' => 'Создана',
            'updated_at' => 'Обновлена',
        ];
    }

    // === СВЯЗИ ===

    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }

    public function getIssueType()
    {
        return $this->hasOne(IssueType::class, ['id' => 'issue_type_id']);
    }

    public function getStatus()
    {
        return $this->hasOne(IssueStatus::class, ['id' => 'status_id']);
    }

    public function getAssignee()
    {
        return $this->hasOne(User::class, ['id' => 'assignee_id']);
    }

    public function getReporter()
    {
        return $this->hasOne(User::class, ['id' => 'reporter_id']);
    }

    public function getParentIssue()
    {
        return $this->hasOne(Issue::class, ['id' => 'parent_issue_id']);
    }

    public function getComments()
    {
        return $this->hasMany(IssueComment::class, ['issue_id' => 'id'])
            ->with('user')
            ->orderBy('created_at DESC');
    }

    public function getPriority()
    {
        return $this->hasOne(IssuePriority::class, ['id' => 'priority_id']);
    }

    public function getResolution()
    {
        return $this->hasOne(IssueResolution::class, ['id' => 'resolution_id']);
    }

    public function getWatchers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('public.issue_watcher', ['issue_id' => 'id']);
    }

    public function getWatcherCount()
    {
        return $this->getWatchers()->count();
    }

    // === ПОСЛЕ СОХРАНЕНИЯ ===

	public function afterSave($insert, $changedAttributes)
	{
		$userId = Yii::$app->user->id ?? 1;

		if ($insert) {
			// Логируем создание
			$history = new IssueHistory();
			$history->issue_id = $this->id;
			$history->user_id = $userId;
			$history->field = 'created';
			$history->new_value = 'Задача создана';
			$history->save(false);
		} else {
			// Логируем изменения полей
			foreach ($changedAttributes as $field => $oldValue) {
				// Пропускаем служебные поля
				if (in_array($field, ['updated_at', 'issue_key'])) {
					continue;
				}

				$history = new IssueHistory();
				$history->issue_id = $this->id;
				$history->user_id = $userId;
				$history->field = $field;
				$history->old_value = $oldValue;
				$history->new_value = $this->{$field};
				$history->save(false);
			}
		}

		// Генерация issue_key — только при создании
		if ($insert && !empty($this->project->project_key)) {
			$this->issue_key = $this->project->project_key . '-' . $this->id;
			self::updateAll(['issue_key' => $this->issue_key], ['id' => $this->id]);
		}

		parent::afterSave($insert, $changedAttributes);
	}
	
	public function getAttachments()
	{
		return $this->hasMany(IssueAttachment::class, ['issue_id' => 'id']);
	}
}