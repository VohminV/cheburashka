<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\User;
?>

<div class="jira-edit-page" style="max-width: 700px; margin: 0 auto; padding: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">

    <!-- Заголовок в стиле Jira -->
    <div class="aui-page-header" style="margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 600; color: #172B4D; margin: 0;">
            <?= Html::encode($model->name) ?>
            <span style="font-size: 14px; color: #5E6C84; font-weight: normal; margin-left: 8px;">Настройки</span>
        </h1>
    </div>

    <!-- Форма -->
    <div class="jira-form" style="background: white; border-radius: 3px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); padding: 24px;">

        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['style' => 'font-size: 14px; font-weight: 600; color: #172B4D; margin-bottom: 6px;'],
                'errorOptions' => ['style' => 'color: #DE350B; font-size: 12px; margin-top: 4px;'],
            ],
        ]); ?>

        <!-- Название проекта -->
        <?= $form->field($model, 'name')->textInput([
            'maxlength' => 255,
            'style' => 'width: 100%; padding: 8px 12px; border: 1px solid #DFE1E6; border-radius: 3px;',
        ])->label('Название проекта') ?>

        <!-- Ключ проекта (только для чтения!) -->
        <?= $form->field($model, 'project_key')->textInput([
            'maxlength' => 10,
            'readonly' => true,
            'style' => 'width: 100%; padding: 8px 12px; border: 1px solid #DFE1E6; border-radius: 3px; background-color: #FAFBFC; color: #5E6C84;',
        ])->label('Ключ проекта') ?>

        <!-- Описание -->
        <?= $form->field($model, 'description')->textarea([
            'rows' => 3,
            'style' => 'width: 100%; padding: 8px 12px; border: 1px solid #DFE1E6; border-radius: 3px;',
        ])->label('Описание') ?>

        <!-- Руководитель -->
        <?= $form->field($model, 'lead_user_id')->dropDownList(
            ArrayHelper::map(User::find()->orderBy('username')->all(), 'id', 'username'),
            [
                'prompt' => '— Выберите руководителя —',
                'style' => 'width: 100%; padding: 8px 12px; border: 1px solid #DFE1E6; border-radius: 3px; background: white;'
            ]
        )->label('Руководитель проекта') ?>

        <!-- Кнопки -->
		<div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid #EBECF0;">
			<?= Html::a('← Назад к проектам', ['index'], [
				'class' => 'aui-button aui-button-subtle',
				'style' => 'padding: 6px 16px; font-weight: 500; color: #172B4D; border: 1px solid #DFE1E6; border-radius: 3px; background: #FAFBFC; text-decoration: none;'
			]) ?>
			<?= Html::submitButton('Сохранить изменения', [
				'class' => 'aui-button aui-button-primary',
				'style' => 'padding: 6px 16px; font-weight: 500; background-color: #0052CC; border: none; color: white; border-radius: 3px;'
			]) ?>
		</div>

        <?php ActiveForm::end(); ?>
    </div>
</div>