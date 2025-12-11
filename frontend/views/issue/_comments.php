<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<!-- Обёртка модуля комментариев -->
<div id="commentsmodule" class="issue-comments">
    <!-- Заголовок с кнопкой сворачивания -->
    <div id="commentsmodule_heading" class="mod-header">
        <div class="toggle-title-group">
            <button class="aui-button toggle-title"
                    aria-label="Комментарии"
                    aria-controls="commentsmodule_content"
                    aria-expanded="true"
                    data-toggle-target="commentsmodule_content">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14">
                    <g fill="none" fill-rule="evenodd">
                        <path d="M3.29175 4.793c-.389.392-.389 1.027 0 1.419l2.939 2.965c.218.215.5.322.779.322s.556-.107.769-.322l2.93-2.955c.388-.392.388-1.027 0-1.419-.389-.392-1.018-.392-1.406 0l-2.298 2.317-2.307-2.327c-.194-.195-.449-.293-.703-.293-.255 0-.51.098-.703.293z" fill="#344563"></path>
                    </g>
                </svg>
            </button>
            <h4 class="toggle-title">Комментарии (<?= count($model->comments) ?>)</h4>
        </div>
    </div>

    <!-- Контент: список комментариев + форма -->
    <div id="commentsmodule_content" class="mod-content">
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
        <?php $form = ActiveForm::begin([
            'action' => ['/issue/add-comment', 'issue_id' => $model->id],
            'options' => ['style' => 'margin-top: 16px;'],
        ]); ?>
            <?= $form->field($commentModel, 'body')
                ->textarea([
                    'rows' => 3,
                    'class' => 'comment-textarea',
                ])
                ->label(false) ?>
            <?= Html::submitButton('Добавить комментарий', ['class' => 'aui-button aui-button-primary']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>