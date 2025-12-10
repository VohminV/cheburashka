<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\Issue;
use common\models\IssueStatus;

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

        // Получаем статусы (предположим: To Do = 1, In Progress = 2, Done = 3)
        $statusInProgress = IssueStatus::findOne(['name' => 'In Progress']);
        $statusToDo = IssueStatus::findOne(['name' => 'To Do']);

        // Назначенные мне (все, кроме Done)
        $assignedToMe = Issue::find()
            ->where(['assignee_id' => $userId])
            ->andWhere(['!=', 'status_id', 3]) // исключаем Done
            ->limit(10)
            ->all();

        // В работе (статус = In Progress)
        $inProgress = Issue::find()
            ->where(['assignee_id' => $userId])
            ->andWhere(['status_id' => $statusInProgress ? $statusInProgress->id : 2])
            ->limit(10)
            ->all();

        // Наблюдаемые задачи — пока заглушка (можно реализовать позже через таблицу watchers)
        $watched = [];
		$statusData = Yii::$app->db->createCommand("
			SELECT 
				COALESCE(s.name, 'Без статуса') AS status,
				COUNT(i.id) AS count
			FROM issue i
			LEFT JOIN issue_status s ON i.status_id = s.id
			GROUP BY s.name, s.id
			ORDER BY s.id
		")->queryAll();
				// Передаём данные в представление
        return $this->render('index', [
            'assignedToMe' => $assignedToMe,
            'inProgress' => $inProgress,
            'watched' => $watched,
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