<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\web\Response; 

use common\models\Worklog;
use common\models\Project;
use common\models\IssueType;
use common\models\IssueStatus;
use common\models\User;
use common\models\Issue;
use common\models\IssueComment;
use common\models\IssuePriority;
use common\models\IssueResolution;
use common\models\IssueWatcher;
use common\models\IssueAttachment;
use common\models\IssueWorklog;

class WorklogController extends Controller
{
	public function actionCreate($issue_id)
	{
		$model = new IssueWorklog();
		$model->issue_id = (int)$issue_id;
		$model->user_id = Yii::$app->user->id;
		$model->created_at = date('Y-m-d H:i:s');

		if (Yii::$app->request->isAjax) {
			if (Yii::$app->request->isPost) {
				$model->load(Yii::$app->request->post());

				// Парсим timeLogged → time_spent
				if (!empty($model->timeLogged)) {
					$model->time_spent = $model->parseTimeToSeconds($model->timeLogged);
				} else {
					$model->time_spent = 0;
				}

				if ($model->time_spent <= 0) {
					$model->addError('timeLogged', 'Затраченное время должно быть больше нуля.');
				}

				if ($model->save()) {
					Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
					return ['success' => true];
				} else {
					Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
					return ['success' => false, 'errors' => $model->errors];
				}
			} else {
				// GET: отдаём форму
				return $this->renderAjax('_form_modal', [
					'model' => $model,
					'issueId' => $issue_id,
				]);
			}
		}

		return $this->redirect(['issue/view', 'id' => $issue_id]);
	}
}