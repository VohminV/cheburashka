<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Issue */
/* @var $commentModel common\models\IssueComment */
?>

<?= $this->render('_project_sidebar', ['project' => $model->project]) ?>

<div class="aui-page-panel">
    <div class="aui-page-panel-inner">

        <!-- ЗАГОЛОВОК -->
        <div class="aui-page-header">
            <div class="aui-page-header-inner">
                <!-- Аватар проекта -->
                <div class="aui-page-header-image">
                    <a href="<?= Url::to(['/project/view', 'id' => $model->project->id]) ?>" title="<?= Html::encode($model->project->name) ?>">
                        <span class="aui-avatar aui-avatar-project" style="
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background-color: var(--jira-primary);
                            color: white;
                            font-weight: bold;
                            width: 40px;
                            height: 40px;
                            border-radius: 4px;
                        ">
                            <?= strtoupper(mb_substr(Html::encode($model->project->project_key ?? $model->project->name), 0, 1, 'UTF-8')) ?>
                        </span>
                    </a>
                </div>

                <!-- Основной контент заголовка -->
                <div class="aui-page-header-main">
                    <ol class="aui-nav aui-nav-breadcrumbs">
                        <li><?= Html::a(Html::encode($model->project->name), ['/project/view', 'id' => $model->project->id]) ?></li>
                        <li><?= Html::encode($model->issue_key) ?></li>
                    </ol>
                    <h1><?= Html::encode($model->summary) ?></h1>
                </div>

                <!-- Действия -->
                <div class="aui-page-header-actions">
                    <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'aui-button aui-button-subtle']) ?>
                </div>
            </div>
        </div>

        <!-- ПАНЕЛЬ ДЕЙСТВИЙ -->
        <div class="command-bar">
            <div class="aui-toolbar2">
                <div class="aui-toolbar2-inner">
                    <div class="aui-toolbar2-primary">
                        <?= Html::a('<span class="aui-icon aui-icon-small">✎</span> Редактировать', ['update', 'id' => $model->id], [
                            'class' => 'aui-button toolbar-trigger',
                            'encode' => false
                        ]) ?>
                        <?= Html::button('<span class="aui-icon aui-icon-small">💬</span> Комментарий', [
                            'class' => 'aui-button toolbar-trigger',
                            'onclick' => 'document.getElementById("comment-form").scrollIntoView({behavior: "smooth"});',
                            'encode' => false
                        ]) ?>
                    </div>
                    <div class="aui-toolbar2-secondary">
                        <?= Html::a('📤 Экспорт', '#', ['class' => 'aui-button aui-button-subtle']) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ОСНОВНОЙ КОНТЕНТ -->
        <div class="aui-group issue-body">

            <!-- ЛЕВАЯ КОЛОНКА -->
            <div class="aui-item issue-main-column">

                <!-- ДЕТАЛИ -->
                <div class="module toggle-wrap">
                    <div class="mod-header">
                        <h4>Детали задачи</h4>
                    </div>
                    <div class="mod-content">
                        <ul class="property-list two-cols">
                            <li class="item">
                                <div class="wrap">
                                    <span class="name">Тип:</span>
                                    <span class="value">
                                        <?php if ($model->issueType): ?>
                                            <span style="display: inline-flex; align-items: center; gap: 6px; color: var(--jira-text-secondary);">
                                                <span><?= mb_substr($model->issueType->name, 0, 1) ?></span>
                                                <?= Html::encode($model->issueType->name) ?>
                                            </span>
                                        <?php else: ?>—<?php endif; ?>
                                    </span>
                                </div>
                            </li>
                            <li class="item">
                                <div class="wrap">
                                    <span class="name">Статус:</span>
                                    <span class="value">
                                        <?php if ($model->status): ?>
                                            <span class="aui-lozenge" style="
                                                background-color: <?= match($model->status->name) {
                                                    'Готово' => '#e3fcef',
                                                    'В работе' => '#deebff',
                                                    'Открыто' => '#fff0b3',
                                                    'Закрыто' => '#e9ecef',
                                                    default => '#f4f5f7'
                                                }; ?>;
                                                color: <?= match($model->status->name) {
                                                    'Готово' => '#006e52',
                                                    'В работе' => 'var(--jira-primary)',
                                                    'Открыто' => '#573900',
                                                    'Закрыто' => 'var(--jira-text-secondary)',
                                                    default => 'var(--jira-text-secondary)'
                                                }; ?>;
                                            "><?= Html::encode($model->status->name) ?></span>
                                        <?php else: ?>—<?php endif; ?>
                                    </span>
                                </div>
                            </li>
                            <li class="item">
                                <div class="wrap">
                                    <span class="name">Приоритет:</span>
                                    <span class="value"><?= $model->priority ? Html::encode($model->priority->name) : '—' ?></span>
                                </div>
                            </li>
                            <li class="item">
                                <div class="wrap">
                                    <span class="name">Решение:</span>
                                    <span class="value"><?= $model->resolution ? Html::encode($model->resolution->name) : 'Нет решения' ?></span>
                                </div>
                            </li>
                            <li class="item" style="grid-column: span 2;">
                                <div class="wrap">
                                    <span class="name">Метки:</span>
                                    <span class="value">
                                        <?php if (!empty($model->labels)): ?>
                                            <?php foreach (explode(',', $model->labels) as $label): ?>
                                                <span class="aui-lozenge" style="background: #e0e0e0; color: #333; margin-right: 4px; margin-top: 4px; display: inline-block;">
                                                    <?= Html::encode(trim($label)) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php else: ?>—<?php endif; ?>
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- ОПИСАНИЕ -->
                <div class="module toggle-wrap">
                    <div class="mod-header">
                        <h4>Описание</h4>
                    </div>
                    <div class="user-content-block mod-content">
                        <?= $model->description ? nl2br(Html::encode($model->description)) : '<em>Нет описания</em>' ?>
                    </div>
                </div>

                <!-- ВЛОЖЕНИЯ -->
				<?= $this->render('_attachments', ['model' => $model]) ?>

                <!-- КОММЕНТАРИИ -->
                <?= $this->render('_comments', ['model' => $model, 'commentModel' => $commentModel]) ?>

            </div>

            <!-- ПРАВАЯ КОЛОНКА -->
            <div class="aui-item issue-side-column">

                <!-- Люди -->
                <div class="module toggle-wrap">
                    <div class="mod-header">
                        <h4 class="toggle-title">Люди</h4>
                    </div>
                    <div class="mod-content">
                        <dl>
                            <dt>Исполнитель:</dt>
                            <dd>
                                <?php if ($model->assignee): ?>
                                    <span class="view-issue-field editable-field inactive">
                                        <span class="aui-avatar aui-avatar-small">
                                            <span class="aui-avatar-inner">
                                                <span style="
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    width: 100%;
                                                    height: 100%;
                                                    background: var(--jira-primary);
                                                    color: white;
                                                    font-size: 10px;
                                                    border-radius: 50%;
                                                "><?= mb_substr(Html::encode($model->assignee->username), 0, 1, 'UTF-8') ?></span>
                                            </span>
                                        </span>
                                        <?= Html::encode($model->assignee->username) ?>
                                        <span class="overlay-icon aui-icon aui-icon-small aui-iconfont-edit"></span>
                                    </span>
                                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->id != $model->assignee_id): ?>
                                        <span class="assign-to-me-link">
                                            <?= Html::a('Назначить меня', ['assign-to-me', 'id' => $model->id]) ?>
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="unassigned">—</span>
                                    <?php if (!Yii::$app->user->isGuest): ?>
                                        <span class="assign-to-me-link">
                                            <?= Html::a('Назначить меня', ['assign-to-me', 'id' => $model->id]) ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </dd>

                            <dt>Автор:</dt>
                            <dd>
                                <?php if ($model->reporter): ?>
                                    <span class="view-issue-field">
                                        <span class="aui-avatar aui-avatar-small">
                                            <span class="aui-avatar-inner">
                                                <span style="
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    width: 100%;
                                                    height: 100%;
                                                    background: var(--jira-text-secondary);
                                                    color: white;
                                                    font-size: 10px;
                                                    border-radius: 50%;
                                                "><?= mb_substr(Html::encode($model->reporter->username), 0, 1, 'UTF-8') ?></span>
                                            </span>
                                        </span>
                                        <?= Html::encode($model->reporter->username) ?>
                                    </span>
                                <?php else: ?>—<?php endif; ?>
                            </dd>

                            <dt>Наблюдатели:</dt>
                            <dd>
                                <a href="#" id="view-watcher-list" aria-label="Посмотреть наблюдателей">
                                    <span class="aui-badge"><?= count($model->getWatchers()->all()) ?></span>
                                </a>
                                <?php if (!Yii::$app->user->isGuest): ?>
                                    <?php
                                    $isWatching = \common\models\IssueWatcher::find()
                                        ->where(['issue_id' => $model->id, 'user_id' => Yii::$app->user->id])
                                        ->exists();
                                    ?>
                                    <a id="watching-toggle"
                                       class="<?= $isWatching ? 'watch-state-on' : 'watch-state-off' ?>"
                                       href="<?= Url::to(['issue/watch', 'id' => $model->id]) ?>">
                                        <?= $isWatching ? 'Прекратить наблюдение' : 'Начать наблюдение за задачей' ?>
                                    </a>
                                <?php endif; ?>
                            </dd>
                        </dl>
                    </div>
                </div>

                <!-- Даты -->
                <div class="module toggle-wrap">
                    <div class="mod-header">
                        <h4 class="toggle-title">Даты</h4>
                    </div>
                    <div class="mod-content">
                        <dl class="dates">
                            <dt>Создано:</dt>
                            <dd title="<?= Yii::$app->formatter->asDatetime($model->created_at, 'php:d.m.Y H:i:s') ?>">
                                <time datetime="<?= date(DATE_W3C, strtotime($model->created_at)) ?>">
                                    <?= Yii::$app->formatter->asDatetime($model->created_at, 'php:d M Y H:i') ?>
                                </time>
                            </dd>
                        </dl>
                        <dl class="dates">
                            <dt>Обновлено:</dt>
                            <dd title="<?= Yii::$app->formatter->asDatetime($model->updated_at, 'php:d.m.Y H:i:s') ?>">
                                <time datetime="<?= date(DATE_W3C, strtotime($model->updated_at)) ?>">
                                    <?= Yii::$app->formatter->asDatetime($model->updated_at, 'php:d M Y H:i') ?>
                                </time>
                            </dd>
                        </dl>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>