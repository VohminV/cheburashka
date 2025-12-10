<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\Issue;
use common\models\IssueStatus;
use common\models\IssueHistory;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'], // только авторизованные
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Jira-подобный Dashboard
     */
	public function actionIndex()
	{
		$userId = Yii::$app->user->id;

		// 1. Назначенные мне
		$assigned = Issue::find()
			->where(['assignee_id' => $userId])
			->with('project')
			->all();

		// 2. В работе (статус "In Progress" = id=2)
		$inProgress = Issue::find()
			->where(['assignee_id' => $userId, 'status_id' => 2])
			->with('project')
			->all();

		// 3. Наблюдаю
		$watched = Issue::find()
			->innerJoin('public.issue_watcher', 'issue.id = issue_watcher.issue_id')
			->where(['issue_watcher.user_id' => $userId])
			->with('project')
			->all();

		// 4. Лента активности (последние 10 записей)
		$activities = IssueHistory::find()
			->with('issue', 'user')
			->orderBy('created_at DESC')
			->limit(10)
			->all();

		// 5. Диаграмма: статистика по статусам
		$statusData = (new \yii\db\Query())
			->select(['s.name as status', 'COUNT(i.id) as count'])
			->from('public.issue i')
			->innerJoin('public.issue_status s', 'i.status_id = s.id')
			->groupBy('s.id, s.name')
			->orderBy('s.id')
			->all();

		return $this->render('index', [
			'assigned' => $assigned,
			'inProgress' => $inProgress,
			'watched' => $watched,
			'activities' => $activities,
			'statusData' => $statusData,
		]);
	}

    /**
     * Logs out the current user.
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
	
	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new \common\models\LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goHome();
		}

		$model->password = '';
		return $this->render('login', ['model' => $model]);
	}
}