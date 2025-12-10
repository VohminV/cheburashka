<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<!-- Заголовок как в Jira -->
<div class="aui-page-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
    <h1 style="font-size: 24px; font-weight: 600; color: #172B4D; margin: 0;">Проекты</h1>
    <div class="aui-page-header-actions">
        <?= Html::a('Создать проект', ['create'], [
            'class' => 'aui-button aui-button-primary',
            'style' => 'background-color: #0052CC; border-color: #0052CC; padding: 6px 16px; font-weight: 500;'
        ]) ?>
    </div>
</div>

<?php if (empty($projects)): ?>
    <div class="dashboard-gadget" style="text-align: center; padding: 60px 20px; color: #5E6C84; border: 1px dashed #DFE1E6; border-radius: 3px;">
        <div style="font-size: 16px; margin-bottom: 16px;">У вас пока нет проектов</div>
        <p>Создайте первый проект, чтобы начать работу.</p>
    </div>
<?php else: ?>
    <!-- Сетка проектов (как в Jira) -->
    <div class="projects-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; margin-top: 16px;">
        <?php foreach ($projects as $project): ?>
            <div class="project-card" style="
                border: 1px solid #DFE1E6;
                border-radius: 3px;
                padding: 16px;
                background: white;
                box-shadow: 0 1px 1px rgba(0,0,0,0.05);
                transition: box-shadow 0.2s;
            ">
                <!-- Верхняя часть: аватар + название -->
                <div style="display: flex; gap: 12px;">
                    <!-- Аватар (Jira-style) -->
                    <div style="
                        width: 32px;
                        height: 32px;
                        border-radius: 3px;
                        background-color: #0052CC;
                        color: white;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: bold;
                        font-size: 14px;
                        flex-shrink: 0;
                    ">
                        <?= strtoupper(substr($project->project_key, 0, 1)) ?>
                    </div>

                    <!-- Информация о проекте -->
                    <div style="flex: 1; min-width: 0;">
                        <!-- Название проекта -->
                        <?= Html::a(Html::encode($project->name), ['view', 'id' => $project->id], [
                            'style' => 'font-size: 16px; font-weight: 600; color: #172B4D; text-decoration: none; display: block; margin-bottom: 4px; line-height: 1.3;'
                        ]) ?>

                        <!-- Ключ проекта -->
                        <div style="font-size: 12px; color: #5E6C84; margin-bottom: 8px;">
                            <?= Html::encode($project->project_key) ?>
                        </div>

                        <!-- Описание (если есть) -->
                        <?php if (!empty($project->description)): ?>
                            <div style="font-size: 13px; color: #5E6C84; line-height: 1.4; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                <?= Html::encode($project->description) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Нижняя часть: руководитель и действия -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding-top: 12px; border-top: 1px solid #EBECF0;">
                    <!-- Руководитель -->
                    <div style="font-size: 12px; color: #6B778C;">
                        <?php if ($project->leadUser): ?>
                            <span style="color: #5E6C84;">Руководитель:</span>
                            <span style="font-weight: 500; color: #172B4D;"><?= Html::encode($project->leadUser->username) ?></span>
                        <?php else: ?>
                            <span>—</span>
                        <?php endif; ?>
                    </div>

                    <!-- Действия (три точки) -->
                    <div class="project-actions" style="display: flex; gap: 8px;">
                        <?= Html::a('Редактировать', ['update', 'id' => $project->id], [
                            'class' => 'aui-button aui-button-subtle',
                            'style' => 'font-size: 12px; padding: 4px 8px; height: auto; min-height: auto;'
                        ]) ?>
                        <!-- Позже можно заменить на иконку "..." с dropdown-меню -->
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Дополнительные стили для AUI-совместимости -->
<style>
    .aui-button {
        border-radius: 3px !important;
        font-size: 14px !important;
    }
    .project-card:hover {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>