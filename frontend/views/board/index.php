<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Доски';
?>

<div class="aui-page-panel">
    <div class="aui-page-panel-inner">
        <div class="aui-page-header">
            <div class="aui-page-header-inner">
                <div class="aui-page-header-main">
                    <h1>Доски</h1>
                </div>
                <div class="aui-page-header-actions">
                    <!-- Пока заглушка -->
                    <?= Html::a('Создать доску', ['create'], ['class' => 'aui-button aui-button-primary']) ?>
                </div>
            </div>
        </div>

        <div class="project-list">
            <?php
            $boards = \common\models\Board::find()->with('project')->all();
            if (empty($boards)): ?>
                <div class="dashboard-gadget" style="text-align: center; padding: 48px;">
                    <p>Нет досок</p>
                    <p style="color: #6b778c; font-size: 14px;">Создайте первую доску, чтобы управлять задачами визуально.</p>
                </div>
            <?php else: ?>
                <?php foreach ($boards as $board): ?>
                    <div class="project-card">
                        <div class="project-info">
                            <div class="project-title">
                                <?= Html::a(Html::encode($board->name), ['view', 'id' => $board->id]) ?>
                            </div>
                            <div class="project-meta">
                                Проект: <?= Html::encode($board->project->name ?? '—') ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>