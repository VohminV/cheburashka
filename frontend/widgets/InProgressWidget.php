<?php
namespace frontend\widgets;

use yii\base\Widget;
use common\models\Issue;
use Yii;

class AssignedToMeWidget extends Widget
{
    public function run()
    {
        $userId = Yii::$app->user->id;
        $issues = Issue::find()
            ->where(['assignee_id' => $userId])
            ->andWhere(['!=', 'status_id', 2]) // предположим, что 3 = Done
            ->limit(10)
            ->all();

        return $this->render('assigned-to-me', ['issues' => $issues]);
    }
}