<?php
namespace common\models;
use yii\db\ActiveRecord;
class IssueStatus extends ActiveRecord
{
    public static function tableName() { return 'public.issue_status'; }
    public function attributeLabels() { return ['name' => 'Статус']; }
}