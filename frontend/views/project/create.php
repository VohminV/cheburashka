<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\User;
?>

<div class="aui-page-header" style="margin-bottom: 24px;">
    <h1 style="font-size: 24px; font-weight: 600; color: #172B4D; margin: 0;">Создать проект</h1>
</div>

<div class="jira-form-container" style="max-width: 700px; margin: 0 auto; padding: 20px; background: white; border-radius: 3px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">

    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['style' => 'font-size: 14px; font-weight: 600; color: #172B4D; margin-bottom: 6px;'],
            'errorOptions' => ['class' => 'field-error', 'style' => 'color: #DE350B; font-size: 12px; margin-top: 4px;'],
        ],
    ]); ?>

    <!-- Название и Ключ в одну строку (на десктопе) -->
    <div class="row mb-4" style="margin-bottom: 24px !important;">
        <div class="col-md-8">
            <?= $form->field($model, 'name')
                ->textInput([
                    'maxlength' => 255,
                    'style' => 'width: 100%; padding: 8px 12px; border: 1px solid #DFE1E6; border-radius: 3px;',
                    'placeholder' => 'Введите название проекта'
                ])
                ->label('Название проекта') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'project_key')
                ->textInput([
                    'maxlength' => 10,
                    'style' => 'width: 100%; padding: 8px 12px; border: 1px solid #DFE1E6; border-radius: 3px; text-transform: uppercase;',
                    'placeholder' => 'Напр. PROJ'
                ])
                ->label('Ключ проекта') ?>
        </div>
    </div>

    <!-- Описание -->
    <?= $form->field($model, 'description')
        ->textarea([
            'rows' => 3,
            'style' => 'width: 100%; padding: 8px 12px; border: 1px solid #DFE1E6; border-radius: 3px;',
            'placeholder' => 'Описание проекта (необязательно)'
        ])
        ->label('Описание') ?>

    <!-- Руководитель -->
    <?= $form->field($model, 'lead_user_id')
        ->dropDownList(
            ArrayHelper::map(User::find()->orderBy('username')->all(), 'id', 'username'),
            [
                'prompt' => '— Выберите руководителя —',
                'style' => 'width: 100%; padding: 8px 12px; border: 1px solid #DFE1E6; border-radius: 3px; background: white;'
            ]
        )
        ->label('Руководитель проекта') ?>

    <!-- Кнопки -->
    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid #EBECF0;">
        <?= Html::a('Отмена', ['index'], [
            'class' => 'aui-button aui-button-subtle',
            'style' => 'padding: 6px 16px; font-weight: 500; color: #172B4D; border: 1px solid #DFE1E6; border-radius: 3px; background: #FAFBFC;'
        ]) ?>
        <?= Html::submitButton('Создать проект', [
            'class' => 'aui-button aui-button-primary',
            'style' => 'padding: 6px 16px; font-weight: 500; background-color: #0052CC; border: none; color: white; border-radius: 3px;'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<!-- Дополнительно: автоматическая генерация ключа (опционально) -->
<?php
$js = <<<JS
document.getElementById('project-name').addEventListener('input', function() {
    const name = this.value.trim();
    const keyField = document.getElementById('project-project_key');
    if (!keyField.value && name) {
        // Простая генерация: первые 2-4 буквы каждого слова, до 10 символов
        const words = name.split(/\s+/).filter(w => w.length > 0);
        let key = words.map(w => w[0].toUpperCase()).join('');
        if (key.length < 2) key = name.replace(/[^A-Z0-9]/gi, '').substring(0, 10).toUpperCase();
        keyField.value = key.substring(0, 10);
    }
});
JS;
$this->registerJs($js);
?>