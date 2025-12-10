<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tenant".
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 */
class Tenant extends ActiveRecord
{
    public static function tableName()
    {
        return 'tenant';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
        ];
    }
}