<?php
/**
 * @var $this yii\web\View
 * @var $project common\models\Project|null
 */

use yii\helpers\Html;
use yii\helpers\Url;

// Если project === null — не отображаем сайдбар (например, на странице создания задачи)
if (!$project) {
    return;
}
?>

<div class="aui-sidebar-wrapper">
    <div class="aui-sidebar-body">
        <!-- Заголовок проекта -->
        <div class="sidebar-project-header" style="margin-bottom: 16px;">
            <a href="<?= Url::to(['/project/view', 'id' => $project->id]) ?>"
               title="<?= Html::encode($project->name) ?>"
               class="sidebar-project-link"
               style="display: flex; align-items: center; text-decoration: none; color: var(--jira-text);">
                <span class="aui-avatar aui-avatar-small aui-avatar-project"
                      style="display: inline-flex; align-items: center; justify-content: center;
                             width: 24px; height: 24px; border-radius: 3px;
                             background-color: #0052cc; color: white; font-size: 12px; font-weight: bold;">
                    <?= mb_substr(Html::encode($project->name), 0, 1, 'UTF-8') ?>
                </span>
                <span style="margin-left: 8px; font-weight: 600; font-size: 14px; color: var(--jira-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    <?= Html::encode($project->name) ?>
                </span>
            </a>
        </div>

        <!-- Навигация -->
        <nav class="aui-navgroup aui-navgroup-vertical">
            <div class="aui-navgroup-inner">
                <div class="aui-sidebar-group aui-sidebar-group-tier-one">
                    <ul class="aui-nav">
                        <li class="<?= \Yii::$app->controller->route === 'project/view' && \Yii::$app->request->get('id') == $project->id ? 'aui-nav-selected' : '' ?>">
                            <?= Html::a(
                                '<span class="aui-icon aui-icon-small"></span><span class="aui-nav-item-label">Общие</span>',
                                ['/project/view', 'id' => $project->id],
                                ['class' => 'aui-nav-item', 'encode' => false]
                            ) ?>
                        </li>
                        <li class="<?= \Yii::$app->controller->route === 'issue/index' && \Yii::$app->request->get('ProjectSearch', [])['project_id'] == $project->id ? 'aui-nav-selected' : '' ?>">
                            <?= Html::a(
                                '<span class="aui-icon aui-icon-small"></span><span class="aui-nav-item-label">Задачи</span>',
                                ['/issue/index', 'ProjectSearch' => ['project_id' => $project->id]],
                                ['class' => 'aui-nav-item', 'encode' => false]
                            ) ?>
                        </li>
                        <li class="<?= \Yii::$app->controller->route === 'project/report' && \Yii::$app->request->get('id') == $project->id ? 'aui-nav-selected' : '' ?>">
                            <?= Html::a(
                                '<span class="aui-icon aui-icon-small"></span><span class="aui-nav-item-label">Отчёты</span>',
                                ['/project/report', 'id' => $project->id],
                                ['class' => 'aui-nav-item', 'encode' => false]
                            ) ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <!-- Футер: кнопка сворачивания (опционально, пока без JS) -->
    <div class="aui-sidebar-footer">
        <button class="aui-button aui-button-subtle" title="Свернуть колонку ( [ )" disabled>
            <span class="aui-icon aui-icon-small">«</span>
        </button>
    </div>
</div>