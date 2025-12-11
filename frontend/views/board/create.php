<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Создать доску';
?>

<div class="aui-page-panel">
    <div class="aui-page-panel-inner">
        <div class="aui-page-header">
            <div class="aui-page-header-inner">
                <div class="aui-page-header-main">
                    <h1>Создать доску</h1>
                </div>
            </div>
        </div>

        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['style' => 'font-weight: 600; color: #172b4d; margin-bottom: 6px; display: block;'],
                'errorOptions' => ['style' => 'color: #d00; font-size: 12px; margin-top: 4px;'],
            ],
        ]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'project_id')->dropDownList(
            \yii\helpers\ArrayHelper::map($projects, 'id', 'name'),
            ['prompt' => 'Выберите проект']
        ) ?>

        <div style="text-align: right; margin-top: 24px;">
            <?= Html::a('Отмена', ['index'], ['class' => 'aui-button aui-button-subtle']) ?>
            <?= Html::submitButton('Создать', ['class' => 'aui-button aui-button-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>