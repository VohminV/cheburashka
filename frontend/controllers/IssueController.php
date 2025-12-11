<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\web\Response; 

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
	public function actionSearch()
		{
			$q = Yii::$app->request->get('q', '');
			$issues = [];
			
			if (trim($q) !== '') {
				$issues = Issue::find()
					->where(['like', 'summary', $q])
					->orWhere(['like', 'description', $q])
					->with('project', 'assignee')
					->limit(50)
					->all();
			}

			return $this->render('search', [
				'issues' => $issues,
				'query' => $q,
			]);
		}
	public function actionUploadAttachment()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;

		$issueId = Yii::$app->request->post('issue_id');
		$issue = Issue::findOne($issueId);
		if (!$issue) {
			return ['success' => false, 'message' => 'Задача не найдена'];
		}

		$files = UploadedFile::getInstancesByName('files');
		if (empty($files)) {
			return ['success' => false, 'message' => 'Файлы не выбраны'];
		}

		$saved = 0;
		foreach ($files as $file) {
			$attachment = new IssueAttachment();
			$attachment->issue_id = $issue->id;
			$attachment->user_id = Yii::$app->user->id; // не забудь! это required
			$attachment->filename = $file->name;
			$attachment->stored_path = ''; // временно, установим после сохранения файла
			$attachment->mime_type = $file->type;
			$attachment->file_size = $file->size;
			$attachment->created_at = Yii::$app->formatter->asDatetime(time(), 'php:Y-m-d H:i:s');

			// Сохраняем файл
			$dir = Yii::getAlias('@webroot/uploads/attachments');
			if (!is_dir($dir)) {
				mkdir($dir, 0777, true);
			}
			$newName = uniqid() . '_' . $file->name;
			if ($file->saveAs("$dir/$newName")) {
				$attachment->stored_path = "uploads/attachments/$newName";
				if ($attachment->save()) {
					$saved++;
				} else {
					// Лог ошибок (временно для отладки)
					\Yii::error('Validation errors: ' . print_r($attachment->getErrors(), true));
				}
			}
		}

		return ['success' => $saved > 0, 'message' => "Загружено $saved файлов"];
	}

	private function formatBytes($size, $precision = 2)
	{
		$units = ['B', 'kB', 'MB', 'GB'];
		for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
			$size /= 1024;
		}
		return round($size, $precision) . ' ' . $units[$i];
	}
	
	public function actionDeleteAttachment()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;

		$id = Yii::$app->request->post('id');
		$attachment = IssueAttachment::findOne($id);

		if (!$attachment) {
			return ['success' => false, 'message' => 'Вложение не найдено'];
		}

		// Проверка: может удалять только автор задачи или автор вложения?
		// (опционально)
		// if ($attachment->user_id !== Yii::$app->user->id) {
		//     return ['success' => false, 'message' => 'Недостаточно прав'];
		// }

		// Удаляем файл с диска
		$filePath = Yii::getAlias('@webroot/' . $attachment->stored_path);
		if (file_exists($filePath)) {
			unlink($filePath);
		}

		// Удаляем из БД
		if ($attachment->delete()) {
			return ['success' => true];
		} else {
			return ['success' => false, 'message' => 'Ошибка при удалении из БД'];
		}
	}
}