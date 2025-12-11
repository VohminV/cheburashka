<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Html::encode($model->name);
?>

<!-- Основной контейнер -->
<div class="aui-page-panel">
    <div class="aui-page-panel-inner">

        <!-- Заголовок проекта -->
        <div class="aui-page-header">
            <div class="aui-page-header-inner">
                <!-- Аватар проекта -->
                <div class="aui-page-header-image">
                    <span class="aui-avatar aui-avatar-project" style="
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background-color: var(--jira-primary);
                        color: white;
                        font-weight: bold;
                        font-size: 20px;
                        width: 40px;
                        height: 40px;
                        border-radius: 4px;
                    ">
                        <?= strtoupper(mb_substr(Html::encode($model->project_key ?? $model->name), 0, 1, 'UTF-8')) ?>
                    </span>
                </div>

                <!-- Основной контент заголовка -->
                <div class="aui-page-header-main">
                    <h1><?= Html::encode($model->name) ?></h1>
                    <p style="color: var(--jira-text-secondary); font-size: 14px; margin: 4px 0 8px;">
                        <?= Html::encode($model->project_key) ?>
                    </p>
                    <?php if (!empty($model->description)): ?>
                        <p style="color: var(--jira-text); font-size: 14px;">
                            <?= Html::encode($model->description) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Вкладки навигации (Jira-style) -->
        <div class="aui-navgroup-vertical" style="margin-bottom: 24px;">
            <ul class="aui-nav aui-nav-tabs" style="display: flex; list-style: none; padding: 0; margin: 0; border-bottom: 2px solid var(--jira-border);">
                <li style="margin: 0;">
                    <a href="#" class="aui-nav-item" style="
                        display: block;
                        padding: 10px 16px;
                        color: var(--jira-text);
                        font-weight: 600;
                        text-decoration: none;
                        border-bottom: 2px solid var(--jira-primary);
                    ">Обзор</a>
                </li>
                <li style="margin: 0;">
                    <a href="<?= Url::to(['/issue/index', 'project_id' => $model->id]) ?>" class="aui-nav-item" style="
                        display: block;
                        padding: 10px 16px;
                        color: var(--jira-text-secondary);
                        font-weight: 600;
                        text-decoration: none;
                    ">Задачи</a>
                </li>
                <!-- Можно добавить "Доски", когда реализуешь -->
            </ul>
        </div>

        <!-- Двухколоночный макет: основное + сайдбар -->
        <div id="content" style="display: flex; gap: 24px; padding: 0; max-width: none; margin: 0;">
            <!-- Основной контент -->
            <div style="flex: 2; min-width: 0;">
                <div class="dashboard-gadget">
                    <h3>Недавние задачи</h3>
                    <div class="mod-content">
                        <p style="color: var(--jira-text-secondary); font-size: 13px;">
                            Здесь можно вывести последние задачи проекта (реализуется позже).
                        </p>
                    </div>
                </div>
            </div>

            <!-- Сайдбар (справа) -->
            <div class="aui-sidebar-wrapper" style="width: 280px;">
                <div class="aui-sidebar-body">
                    <div class="dashboard-gadget" style="border: none; background: transparent; padding: 0;">
                        <h3>Детали</h3>
                        <div class="mod-content">
                            <dl class="module" style="margin: 0;">
                                <dt>Руководитель</dt>
                                <dd>
                                    <?php if ($model->leadUser): ?>
                                        <?= Html::encode($model->leadUser->username) ?>
                                    <?php else: ?>
                                        <span style="color: var(--jira-text-secondary);">—</span>
                                    <?php endif; ?>
                                </dd>

                                <dt>Тип проекта</dt>
                                <dd>Программное обеспечение</dd>

                                <dt>Статус</dt>
                                <dd>
                                    <?php if (!empty($model->closed_at)): ?>
                                        <span class="aui-badge" style="background: #e0e0e0; color: var(--jira-text-secondary);">Закрыт</span>
                                    <?php else: ?>
                                        <span class="aui-badge" style="background: #deebff; color: var(--jira-primary);">Активен</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="aui-sidebar-footer">
                    <?= Html::a('Редактировать проект', ['update', 'id' => $model->id], ['class' => 'aui-button aui-button-primary', 'style' => 'width: 100%;']) ?>
                    <?= Html::a('← Назад к проектам', ['index'], ['class' => 'aui-button aui-button-subtle', 'style' => 'width: 100%; margin-top: 8px;']) ?>
                </div>
            </div>
        </div>

    </div>
</div>