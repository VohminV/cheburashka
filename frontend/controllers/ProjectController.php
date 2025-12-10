<?php

namespace frontend\controllers;

use Yii;
use common\models\Project;
use common\models\Tenant;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class ProjectController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // только авторизованные
                    ],
                ],
            ],
        ];
    }

    // Список проектов текущего пользователя или тенанта
    public function actionIndex()
    {
        // Показываем проекты, где пользователь — руководитель или участник
        // Пока просто все проекты тенанта (упрощённо)
        $projects = Project::find()
            ->where(['tenant_id' => 1]) // ← временно, позже свяжем с user.tenant_id
            ->with('leadUser')
            ->all();

        return $this->render('index', [
            'projects' => $projects,
        ]);
    }

    // Просмотр проекта
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    // Создание проекта
    public function actionCreate()
    {
        $model = new Project();
        $model->tenant_id = 1; // ← временно

        if ($this->request->isPost) {
            $model->load($this->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Проект создан');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    // Редактирование
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            $model->load($this->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Проект обновлён');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    // Удаление (soft delete через статус или отдельное поле)
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        // В Jira проекты не удаляют, а архивируют → вы можете добавить поле `is_archived`
        // Пока просто удалим (или закомментируйте)
        // $model->delete();

        Yii::$app->session->setFlash('warning', 'Удаление проектов временно отключено (Jira-style)');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Проект не найден.');
    }
}