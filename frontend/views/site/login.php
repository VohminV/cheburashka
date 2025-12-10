<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

// Убираем заголовок и хлебные крошки — как в Jira
$this->title = '';
$this->params['breadcrumbs'] = [];
?>

<div class="dashboard-content">
    <div class="login-form card" style="max-width: 400px; margin: 80px auto 0; padding: 24px; border: 1px solid #dfe1e6; border-radius: 3px; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; margin-bottom: 24px; color: #172b4d;">Вход</h2>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['style' => 'width: 100%;'],
        ]); ?>

            <?= $form->field($model, 'username')->textInput([
                'autofocus' => true,
                'placeholder' => 'Имя пользователя',
                'style' => 'padding: 8px 12px; border: 1px solid #dfe1e6; border-radius: 3px;',
            ])->label(false) ?>

            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => 'Пароль',
                'style' => 'padding: 8px 12px; border: 1px solid #dfe1e6; border-radius: 3px;',
            ])->label(false) ?>

            <div style="margin-bottom: 16px;">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'style' => 'margin-top: 8px;',
                ]) ?>
            </div>

            <div class="form-group" style="text-align: center;">
                <?= Html::submitButton('Войти', [
                    'class' => 'btn',
                    'style' => 'background-color: #0052cc; color: white; padding: 8px 24px; border: none; border-radius: 3px; width: 100%;',
                    'name' => 'login-button'
                ]) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>