<?php

namespace common\models;

use yii\db\ActiveRecord;

class IssueType extends ActiveRecord
{
    public static function tableName()
    {
        return 'public.issue_type';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Тип задачи',
            'description' => 'Описание',
            'icon' => 'Иконка',
        ];
    }
}