<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="aui-page-panel">
    <div class="aui-page-panel-inner">

        <!-- Заголовок -->
        <div class="aui-page-header">
            <div class="aui-page-header-inner">
                <div class="aui-page-header-main">
                    <h1>Проекты</h1>
                </div>
                <div class="aui-page-header-actions">
                    <?= Html::a('Создать проект', ['create'], ['class' => 'aui-button aui-button-primary']) ?>
                </div>
            </div>
        </div>

        <?php if (empty($projects)): ?>
            <div class="dashboard-gadget" style="text-align: center; padding: 60px 20px;">
                <div style="font-size: 16px; margin-bottom: 16px; color: var(--jira-text-secondary);">У вас пока нет проектов</div>
                <p>Создайте первый проект, чтобы начать работу.</p>
            </div>
        <?php else: ?>
            <div class="project-list">
                <?php foreach ($projects as $project): ?>
                    <div class="project-card">
                        <div class="project-info">
                            <!-- Аватар-ключ -->
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                <span class="aui-avatar aui-avatar-project" style="background-color: #0052CC; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                    <?= strtoupper(substr($project->project_key, 0, 1)) ?>
                                </span>
                                <?= Html::a(Html::encode($project->name), ['view', 'id' => $project->id], [
                                    'class' => 'project-title'
                                ]) ?>
                                <span class="project-key"><?= Html::encode($project->project_key) ?></span>
                            </div>

                            <?php if (!empty($project->description)): ?>
                                <div class="project-description">
                                    <?= Html::encode($project->description) ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($project->leadUser): ?>
                                <div class="project-meta">
                                    <span>Руководитель:</span>
                                    <strong><?= Html::encode($project->leadUser->username) ?></strong>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="project-actions">
                            <?= Html::a('Редактировать', ['update', 'id' => $project->id], ['class' => 'aui-button aui-button-subtle']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>