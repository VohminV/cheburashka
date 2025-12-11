<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;

class UserController extends Controller
{
    public function actionAccessibility()
    {
        return $this->render('accessibility');
    }

    // Опционально: профиль
    public function actionProfile()
	{
		$user = Yii::$app->user->identity;
		if (!$user) {
			throw new \yii\web\NotFoundHttpException('Пользователь не найден');
		}

		return $this->render('profile', ['user' => $user]);
	}
}