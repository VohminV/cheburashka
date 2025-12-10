<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="module toggle-wrap dashboard-gadget">
    <h4>Комментарии (<?= count($model->comments) ?>)</h4>

    <?php if (!empty($model->comments)): ?>
        <?php foreach ($model->comments as $comment): ?>
            <div class="comment-item" style="margin: 8px 0; padding-bottom: 8px; border-bottom: 1px solid #eee;">
                <div>
                    <strong><?= Html::encode($comment->user->username) ?></strong>
                    <span style="font-size: 0.85em; color: #666; margin-left: 8px;">
                        <?= Yii::$app->formatter->asDatetime($comment->created_at) ?>
                    </span>
                </div>
                <div style="margin-top: 4px; white-space: pre-wrap; line-height: 1.4;">
                    <?= Html::encode($comment->body) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p><em>Нет комментариев</em></p>
    <?php endif; ?>

    <!-- Форма добавления -->
    <?php $form = \yii\widgets\ActiveForm::begin([
        'action' => ['/issue/add-comment', 'issue_id' => $model->id],
        'options' => ['style' => 'margin-top: 16px;'],
    ]); ?>
        <?= $form->field($commentModel, 'body')
			->textarea([
				'rows' => 3,
				'class' => 'comment-textarea',
			])
			->label(false) ?>
        <?= \yii\helpers\Html::submitButton('Добавить комментарий', ['class' => 'aui-button aui-button-primary']) ?>
    <?php \yii\widgets\ActiveForm::end(); ?>
</div>