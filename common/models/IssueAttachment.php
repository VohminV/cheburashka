<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class IssueAttachment extends ActiveRecord
{
    public static function tableName()
    {
        return 'issue_attachment';
    }

	public function rules()
	{
		return [
			[['issue_id', 'user_id', 'filename', 'stored_path', 'file_size'], 'required'],
			[['filename', 'stored_path', 'mime_type'], 'string', 'max' => 255], // ← УБРАЛ file_path
			[['mime_type'], 'string', 'max' => 100],
			[['file_size'], 'integer'],
		];
	}
	
    public function getIssue()
    {
        return $this->hasOne(Issue::class, ['id' => 'issue_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
	
	public function getUrl()
	{
		return Yii::getAlias('@web/uploads/' . $this->stored_path);
	}

	public function getSize()
	{
		return $this->formatBytes($this->file_size);
	}

	private function formatBytes($size, $precision = 2)
	{
		$units = ['B', 'kB', 'MB', 'GB'];
		for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
			$size /= 1024;
		}
		return round($size, $precision) . ' ' . $units[$i];
	}
}