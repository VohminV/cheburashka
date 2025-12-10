<?php
namespace app\models;

use yii\db\ActiveRecord;

class Project extends ActiveRecord
{
    public static function tableName()
    {
        return 'public.project';
    }

    public function rules()
    {
        return [
            [['name', 'project_key'], 'required'],
            [['name', 'project_key'], 'string', 'max' => 255],
            [['project_key'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'project_key' => 'Ключ проекта',
        ];
    }

    // Связь: проект может иметь много задач
    public function getIssues()
    {
        return $this->hasMany(Issue::class, ['project_id' => 'id']);
    }

    // Связь: руководитель проекта (если в вашей таблице есть lead_user_id)
    // public function getLeadUser()
    // {
    //     return $this->hasOne(User::class, ['id' => 'lead_user_id']);
    // }
}