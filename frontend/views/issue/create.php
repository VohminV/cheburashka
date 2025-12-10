<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<?= $this->render('_project_sidebar', ['project' => null]) ?>

<div class="aui-page-panel">
    <div class="aui-page-panel-inner">

        <!-- Заголовок -->
        <div class="aui-page-header">
            <div class="aui-page-header-inner">
                <div class="aui-page-header-main">
                    <h1>Создать задачу</h1>
                </div>
            </div>
        </div>

        <!-- Основное содержимое -->
        <div class="aui-page-panel-content" style="padding: 24px;">

            <?php $form = ActiveForm::begin([
                'id' => 'issue-create-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['style' => 'font-weight: 600; color: #172b4d; margin-bottom: 6px; display: block;'],
                    'errorOptions' => ['style' => 'color: #d00; font-size: 12px; margin-top: 4px;'],
                ],
            ]); ?>

            <!-- Основные поля -->
            <?= $form->field($model, 'summary')->textInput([
                'maxlength' => 255,
                'placeholder' => 'Краткое описание задачи',
                'class' => 'text'
            ])->label('Краткое описание') ?>

            <?= $form->field($model, 'description')->textarea([
                'rows' => 5,
                'placeholder' => 'Опишите задачу подробно...',
                'class' => 'text'
            ])->label('Описание') ?>

            <!-- Двухколоночная сетка -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 24px;">
                <div>
                    <?= $form->field($model, 'project_id')->dropDownList(
                        ArrayHelper::map($projects, 'id', 'name'),
                        ['prompt' => '— Выберите проект —', 'class' => 'text']
                    )->label('Проект') ?>

                    <?= $form->field($model, 'issue_type_id')->dropDownList(
                        ArrayHelper::map($issueTypes, 'id', 'name'),
                        ['prompt' => '— Тип задачи —', 'class' => 'text']
                    )->label('Тип задачи') ?>

                    <?= $form->field($model, 'priority_id')->dropDownList(
                        ArrayHelper::map($priorities, 'id', 'name'),
                        ['prompt' => '— Приоритет —', 'class' => 'text']
                    )->label('Приоритет') ?>
                </div>

                <div>
                    <?= $form->field($model, 'status_id')->dropDownList(
                        ArrayHelper::map($statuses, 'id', 'name'),
                        ['prompt' => '— Статус —', 'class' => 'text']
                    )->label('Статус') ?>

                    <?= $form->field($model, 'assignee_id')->dropDownList(
                        ArrayHelper::map($users, 'id', 'username'),
                        ['prompt' => '— Не назначена —', 'class' => 'text']
                    )->label('Назначена') ?>

                    <?= $form->field($model, 'resolution_id')->dropDownList(
                        ArrayHelper::map($resolutions, 'id', 'name'),
                        ['prompt' => '— Решение —', 'class' => 'text']
                    )->label('Решение') ?>
                </div>
            </div>

            <!-- Скрытые поля -->
            <?= Html::activeHiddenInput($model, 'reporter_id') ?>

            <!-- Кнопки -->
            <div style="text-align: right; margin-top: 32px; display: flex; gap: 8px; justify-content: flex-end;">
                <?= Html::a('Отмена', ['index'], ['class' => 'aui-button aui-button-subtle']) ?>
                <?= Html::submitButton('Создать', ['class' => 'aui-button aui-button-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<!-- Стили для формы (уже часть вашего CSS, но на всякий случай) -->
<style>
.text {
    width: 100%;
    padding: 6px 8px;
    border: 1px solid #dfe1e6;
    border-radius: 3px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 14px;
}

.field-error {
    color: #d00 !important;
    font-size: 12px !important;
    margin-top: 4px !important;
}
</style>