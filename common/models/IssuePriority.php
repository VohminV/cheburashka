<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class IssuePriority extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%issue_priority}}';
    }

    public function getIssues()
    {
        return $this->hasMany(Issue::class, ['priority_id' => 'id']);
    }
}