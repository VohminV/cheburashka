<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Board extends ActiveRecord
{
    public static function tableName()
    {
        return 'public.board';
    }

    public function rules()
    {
        return [
            [['name', 'project_id'], 'required'],
            [['project_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'project_id' => 'Проект',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    // Связь с проектом
    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }
}