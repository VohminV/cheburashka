<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Issue[] $issues */
/** @var string $query */
?>

<div class="aui-page-panel">
    <div class="aui-page-panel-inner">
        <header class="aui-page-header">
            <div class="aui-page-header-inner">
                <div class="aui-page-header-main">
                    <h1>
                        <?= Html::encode($query !== '' ? 'Результаты поиска: “' . $query . '”' : 'Поиск задач') ?>
                    </h1>
                </div>
            </div>
        </header>

        <?php if ($query === ''): ?>
            <p class="aui-message">Введите запрос в поле поиска.</p>
        <?php elseif (empty($issues)): ?>
            <p class="aui-message">Ничего не найдено по запросу “<?= Html::encode($query) ?>”.</p>
        <?php else: ?>
            <div class="project-list">
                <?php foreach ($issues as $issue): ?>
                    <div class="project-card">
                        <div class="project-info">
                            <h2 class="project-title">
                                <a href="<?= Url::to(['/issue/view', 'id' => $issue->id]) ?>">
                                    [<?= Html::encode($issue->project->key ?? '–') ?>-<?= $issue->id ?>]  
                                    <?= Html::encode($issue->summary) ?>
                                </a>
                            </h2>
                            <p class="project-description">
                                <?= Html::encode($issue->description ? mb_substr($issue->description, 0, 100) . '…' : '') ?>
                            </p>
                            <div class="project-meta">
                                Статус: <?= Html::encode($issue->status->name ?? '–') ?>  
                                | Назначено: <?= $issue->assignee ? Html::encode($issue->assignee->username) : '—' ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>