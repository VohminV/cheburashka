<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Issue extends ActiveRecord
{
    public static function tableName()
    {
        return 'public.issue';
    }

    public function rules()
    {
        return [
            [['project_id', 'issue_type_id', 'status_id', 'issue_key', 'summary', 'reporter_id'], 'required'],
            [['project_id', 'issue_type_id', 'status_id', 'assignee_id', 'reporter_id', 'parent_issue_id'], 'integer'],
            [['description'], 'string'],
            [['issue_key'], 'string', 'max' => 20],
            [['summary'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
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

    // Связи (даже если модели User/Project пока нет — можно оставить заглушки)
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
	/**
	 * @return \yii\db\ActiveQuery
	 */
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
			->viaTable('{{public.issue_watcher}}', ['issue_id' => 'id']);
	}
	public function getWatcherCount()
	{
		return $this->getWatchers()->count();
	}

}