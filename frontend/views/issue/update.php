<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Issue */
/* @var $projects array */
/* @var $issueTypes array */
/* @var $statuses array */
/* @var $users array */
?>

<div class="aui-page-panel">
    <div class="aui-page-panel-inner">
        <header class="aui-page-header">
            <div class="aui-page-header-inner">
                <div class="aui-page-header-main">
                    <h1>Редактировать задачу</h1>
                </div>
            </div>
        </header>

        <div class="aui-page-panel-content">
            <div class="aui-group issue-body">
                <div class="aui-item issue-main-column">
                    <?php $form = ActiveForm::begin(['id' => 'issue-update-form']); ?>

                    <!-- Две колонки, как в Jira -->
                    <div class="property-list two-cols">
                        <div class="item">
                            <div class="wrap">
                                <span class="name">Проект</span>
                                <?= $form->field($model, 'project_id')
                                    ->dropDownList(
                                        ArrayHelper::map($projects, 'id', 'name'),
                                        ['prompt' => 'Выберите проект', 'class' => 'aui-field']
                                    )
                                    ->label(false)
                                    ->error(false) ?>
                            </div>
                        </div>
                        <div class="item item-right">
                            <div class="wrap">
                                <span class="name">Тип задачи</span>
                                <?= $form->field($model, 'issue_type_id')
                                    ->dropDownList(
                                        ArrayHelper::map($issueTypes, 'id', 'name'),
                                        ['prompt' => 'Выберите тип', 'class' => 'aui-field']
                                    )
                                    ->label(false)
                                    ->error(false) ?>
                            </div>
                        </div>

                        <div class="item">
                            <div class="wrap">
                                <span class="name">Статус</span>
                                <?= $form->field($model, 'status_id')
                                    ->dropDownList(
                                        ArrayHelper::map($statuses, 'id', 'name'),
                                        ['prompt' => 'Выберите статус', 'class' => 'aui-field']
                                    )
                                    ->label(false)
                                    ->error(false) ?>
                            </div>
                        </div>
                        <div class="item item-right">
                            <div class="wrap">
                                <span class="name">Назначена</span>
                                <?= $form->field($model, 'assignee_id')
                                    ->dropDownList(
                                        $users,
                                        ['prompt' => 'Назначить на...', 'class' => 'aui-field']
                                    )
                                    ->label(false)
                                    ->error(false) ?>
                            </div>
                        </div>
                    </div>

                    <!-- Основные поля — на всю ширину -->
                    <div style="margin: 24px 0;">
                        <?= $form->field($model, 'summary')
                            ->textInput(['class' => 'aui-field', 'maxlength' => true])
                            ->label('Краткое описание') ?>
                    </div>

                    <div style="margin: 24px 0;">
                        <?= $form->field($model, 'description')
                            ->textarea(['class' => 'aui-field', 'rows' => 6])
                            ->label('Описание') ?>
                    </div>

                    <div class="command-bar">
                        <div class="aui-toolbar2">
                            <div class="aui-toolbar2-inner">
                                <div class="aui-toolbar2-primary">
                                    <?= Html::submitButton('Сохранить', ['class' => 'aui-button aui-button-primary']) ?>
                                    <?= Html::a('Отмена', ['view', 'id' => $model->id], ['class' => 'aui-button aui-button-subtle']) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>

                <!-- Пустая боковая колонка (можно позже добавить историю или детали) -->
                <div class="aui-item issue-side-column">
                    <!-- reserved -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Встроенные стили, совместимые с твоей темой */
.aui-field {
    width: 100%;
    padding: 6px 8px;
    border: 1px solid var(--jira-border);
    border-radius: 3px;
    background: white;
    font-size: 14px;
    color: var(--jira-text);
}
.aui-field:focus {
    outline: none;
    border-color: var(--jira-primary);
    box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.2);
}
.aui-field option {
    color: var(--jira-text);
}
</style>