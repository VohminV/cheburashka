<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="jira-project-page" style="max-width: 1200px; margin: 0 auto; padding: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">

    <!-- Заголовок проекта (как в Jira) -->
    <div class="d-flex align-items-start mb-4">
        <!-- Аватар проекта (Jira-style placeholder) -->
        <div class="me-3 flex-shrink-0">
            <div style="
                width: 48px;
                height: 48px;
                border-radius: 4px;
                background-color: #0052CC;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                font-size: 20px;
                line-height: 1;
            ">
                <?= strtoupper(substr($model->project_key, 0, 1)) ?>
            </div>
        </div>

        <!-- Основной контент заголовка -->
        <div>
            <h1 class="h2 mb-1" style="font-size: 24px; font-weight: 600; color: #172B4D;">
                <?= Html::encode($model->name) ?>
            </h1>
            <p class="text-muted mb-2" style="font-size: 14px; color: #5E6C84;">
                <?= Html::encode($model->project_key) ?>
            </p>
            <?php if (!empty($model->description)): ?>
                <p class="mb-0" style="font-size: 14px; color: #172B4D; max-width: 600px;">
                    <?= Html::encode($model->description) ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Навигация по вкладкам (как в Jira: Обзор, Задачи, Доски и т.д.) -->
    <ul class="nav nav-tabs mb-4" role="tablist" style="border-bottom: 2px solid #DFE1E6;">
        <li class="nav-item" role="presentation">
            <?= Html::a('Обзор', ['#'], [
                'class' => 'nav-link active',
                'style' => 'color: #172B4D; font-weight: 500; padding: 10px 16px; border: none; border-bottom: 2px solid #0052CC; margin-bottom: -2px;',
                'role' => 'tab'
            ]) ?>
        </li>
        <li class="nav-item" role="presentation">
            <?= Html::a('Задачи', ['issue/index', 'project_id' => $model->id], [
                'class' => 'nav-link',
                'style' => 'color: #5E6C84; font-weight: 500; padding: 10px 16px; border: none;',
                'role' => 'tab'
            ]) ?>
        </li>
        <!-- Добавьте другие вкладки по мере развития проекта -->
        <!-- <li class="nav-item">
            <?= Html::a('Доски', ['#'], ['class' => 'nav-link', 'style' => 'color: #5E6C84; font-weight: 500; padding: 10px 16px; border: none;']) ?>
        </li> -->
    </ul>

    <!-- Основной контент: сайдбар + основная область -->
    <div class="row">
        <!-- Основная область (в Jira — обычно активности, последние задачи) -->
        <div class="col-md-8">
            <div class="card mb-3" style="border: none; box-shadow: 0 1px 2px rgba(0,0,0,0.1); border-radius: 3px;">
                <div class="card-body p-3">
                    <h5 style="font-size: 14px; font-weight: 600; color: #172B4D; margin-bottom: 12px;">Недавние задачи</h5>
                    <p class="text-muted" style="font-size: 13px;">Здесь можно вывести последние 5 задач проекта (реализуется позже)</p>
                </div>
            </div>
        </div>

        <!-- Сайдбар (как в Jira справа) -->
        <div class="col-md-4">
            <div class="card mb-3" style="border: none; background: #f4f5f7; border-radius: 3px;">
                <div class="card-body p-3">
                    <h6 style="font-size: 12px; font-weight: 600; color: #6B778C; text-transform: uppercase; margin-bottom: 8px;">Детали</h6>

                    <div class="mb-3">
                        <div style="font-size: 11px; color: #6B778C; margin-bottom: 4px;">Руководитель</div>
                        <?php if ($model->leadUser): ?>
                            <div style="font-size: 14px; font-weight: 500; color: #172B4D;">
                                <?= Html::encode($model->leadUser->username) ?>
                            </div>
                        <?php else: ?>
                            <div class="text-muted" style="font-size: 14px;">—</div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <div style="font-size: 11px; color: #6B778C; margin-bottom: 4px;">Тип проекта</div>
                        <div style="font-size: 14px; font-weight: 500; color: #172B4D;">Программное обеспечение</div>
                    </div>

                    <div>
                        <div style="font-size: 11px; color: #6B778C; margin-bottom: 4px;">Статус</div>
                        <?php if (!empty($model->closed_at)): ?>
                            <span class="badge" style="background-color: #E0E2E5; color: #6B778C; font-weight: 500;">Закрыт</span>
                        <?php else: ?>
                            <span class="badge" style="background-color: #DEEBFF; color: #0052CC; font-weight: 500;">Активен</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Действия -->
            <div class="d-grid gap-2">
                <?= Html::a('Редактировать проект', ['update', 'id' => $model->id], [
                    'class' => 'btn',
                    'style' => 'background-color: #0052CC; color: white; border: none; font-weight: 500; padding: 8px 16px;'
                ]) ?>
                <?= Html::a('← Назад к проектам', ['index'], [
                    'class' => 'btn btn-outline-secondary',
                    'style' => 'font-weight: 500; padding: 8px 16px;'
                ]) ?>
            </div>
        </div>
    </div>

</div>