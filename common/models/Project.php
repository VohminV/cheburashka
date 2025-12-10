<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property int $tenant_id
 * @property string $name
 * @property string $project_key
 * @property string|null $description
 * @property int|null $lead_user_id
 * @property string|null $created_at
 *
 * @property Issue[] $issues
 * @property Tenant $tenant
 */
class Project extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'lead_user_id'], 'default', 'value' => null],
            [['tenant_id', 'name', 'project_key'], 'required'],
            [['tenant_id', 'lead_user_id'], 'default', 'value' => null],
            [['tenant_id', 'lead_user_id'], 'integer'],
            [['description'], 'string'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['project_key'], 'string', 'max' => 10],
            [['tenant_id', 'project_key'], 'unique', 'targetAttribute' => ['tenant_id', 'project_key']],
            [['tenant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tenant::class, 'targetAttribute' => ['tenant_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tenant_id' => 'Tenant ID',
            'name' => 'Name',
            'project_key' => 'Project Key',
            'description' => 'Description',
            'lead_user_id' => 'Lead User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Issues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(Issue::class, ['project_id' => 'id']);
    }

    /**
     * Gets query for [[Tenant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTenant()
    {
        return $this->hasOne(Tenant::class, ['id' => 'tenant_id']);
    }
	
	public function getLeadUser()
	{
		return $this->hasOne(\common\models\User::class, ['id' => 'lead_user_id']);
	}

}
