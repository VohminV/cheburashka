<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Board;
use common\models\Project;

class BoardController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

	public function actionView($id)
	{
		$board = \common\models\Board::findOne($id);
		if (!$board) {
			throw new \yii\web\NotFoundHttpException('Доска не найдена');
		}

		// Получаем все задачи по проекту доски
		$issues = \common\models\Issue::find()
			->where(['project_id' => $board->project_id])
			->with('status')
			->orderBy('created_at DESC')
			->all();

		return $this->render('view', [
			'board' => $board,
			'issues' => $issues,
		]);
	}
	
	public function actionCreate()
	{
		$model = new \common\models\Board();

		if ($model->load(Yii::$app->request->post())) {
			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
		}

		return $this->render('create', [
			'model' => $model,
			'projects' => \common\models\Project::find()->all(),
		]);
	}
}