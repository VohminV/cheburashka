<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;


use common\models\Project;
use common\models\IssueType;
use common\models\IssueStatus;
use common\models\User;
use common\models\Issue;
use common\models\IssueComment;
use common\models\IssuePriority;
use common\models\IssueResolution;
use common\models\IssueWatcher;

class IssueController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'logout' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
	{
		$issues = Issue::find()
			->with('project', 'status', 'assignee') // ← это критически важно!
			->orderBy('created_at DESC')
			->all();

		return $this->render('index', ['issues' => $issues]);
	}

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
			'commentModel' => new \common\models\IssueComment(),
        ]);
    }

    public function actionCreate()
	{
		$model = new Issue();
		
		// Автоматически заполняем служебные поля
		if ($model->load(Yii::$app->request->post())) {
			$model->reporter_id = Yii::$app->user->id;
			$model->created_at = date('Y-m-d H:i:s');
			$model->updated_at = date('Y-m-d H:i:s');
			
			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
		}

		// Передаём данные для выпадающих списков
		return $this->render('create', [
			'model' => $model,
			'projects' => Project::find()->all(),
			'issueTypes' => IssueType::find()->all(),
			'statuses' => IssueStatus::find()->all(),
			'priorities' => IssuePriority::find()->all(),
			'resolutions' => IssueResolution::find()->all(),
			'users' => User::find()->andWhere(['status' => User::STATUS_ACTIVE])->all(),
		]);
	}

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->updated_at = date('Y-m-d H:i:s');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Задача обновлена');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $projects = Project::find()->orderBy('name')->all();
        $issueTypes = IssueType::find()->orderBy('name')->all();
        $statuses = IssueStatus::find()->orderBy('name')->all();
        $users = User::find()->select(['id', 'username'])->orderBy('username')->all();

        return $this->render('update', [
            'model' => $model,
            'projects' => $projects,
            'issueTypes' => $issueTypes,
            'statuses' => $statuses,
            'users' => ArrayHelper::map($users, 'id', 'username'),
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Задача удалена');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Issue::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
	
	public function actionAddComment($issue_id)
	{
		$comment = new IssueComment();
		$comment->issue_id = $issue_id;
		$comment->user_id = Yii::$app->user->id;
		$comment->created_at = date('Y-m-d H:i:s');
		
		if ($comment->load(Yii::$app->request->post()) && $comment->save()) {
			Yii::$app->session->setFlash('success', 'Комментарий добавлен');
		}
		return $this->redirect(['view', 'id' => $issue_id]);
	}
	
	public function actionWatch($id)
	{
		$issue = $this->findModel($id);
		$userId = Yii::$app->user->id;

		// Проверяем, наблюдаем ли уже
		$exists = IssueWatcher::find()
			->where(['issue_id' => $issue->id, 'user_id' => $userId])
			->exists();

		if ($exists) {
			// Удаляем из наблюдателей (отписка)
			IssueWatcher::deleteAll([
				'issue_id' => $issue->id,
				'user_id' => $userId
			]);
			Yii::$app->session->setFlash('success', 'Вы больше не наблюдаете за этой задачей.');
		} else {
			// Добавляем в наблюдатели
			$watcher = new IssueWatcher();
			$watcher->issue_id = $issue->id;
			$watcher->user_id = $userId;
			if (!$watcher->save()) {
				Yii::$app->session->setFlash('error', 'Не удалось добавить в наблюдатели.');
			} else {
				Yii::$app->session->setFlash('success', 'Теперь вы наблюдаете за этой задачей.');
			}
		}

		return $this->redirect(['view', 'id' => $id]);
	}
	
	public function actionUpdateStatus()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		if (!\Yii::$app->request->isPost) {
			return ['success' => false, 'error' => 'Метод не поддерживается'];
		}

		$data = \Yii::$app->request->post();
		$issue = Issue::findOne($data['id'] ?? null);

		if (!$issue) {
			return ['success' => false, 'error' => 'Задача не найдена'];
		}

		$issue->status_id = (int)$data['status_id'];
		$issue->updated_at = date('Y-m-d H:i:s');

		if ($issue->save(false, ['status_id', 'updated_at'])) {
			return ['success' => true];
		} else {
			return ['success' => false, 'error' => 'Не удалось сохранить'];
		}
	}
}