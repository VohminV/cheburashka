<?php
namespace common\models;
use Yii;
use yii\db\ActiveRecord;

class IssueResolution extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%issue_resolution}}';
    }
}