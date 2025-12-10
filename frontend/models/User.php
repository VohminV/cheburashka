<?php
namespace app\models;
use yii\db\ActiveRecord;
class User extends ActiveRecord
{
    public static function tableName()
    {
        return 'public."user"';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
        ];
    }

    public function getCreatedIssues()
    {
        return $this->hasMany(Issue::class, ['reporter_id' => 'id']);
    }

    public function getAssignedIssues()
    {
        return $this->hasMany(Issue::class, ['assignee_id' => 'id']);
    }
}