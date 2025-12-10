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
                    <h1>Задачи</h1>
                </div>
                <div class="aui-page-header-actions">
                    <?= Html::a('Создать задачу', ['create'], ['class' => 'aui-button aui-button-primary']) ?>
                </div>
            </div>
        </div>

        <!-- Список задач -->
        <div class="project-list">
            <?php if (!empty($issues)): ?>
                <table class="aui issue-navigator" style="width: 100%; border-collapse: collapse; margin-top: 16px; font-size: 14px;">
                    <thead>
                        <tr>
                            <th style="padding: 8px 12px; text-align: left; border-bottom: 2px solid #dfe1e6; font-weight: 600; color: #6b778c;">Ключ</th>
                            <th style="padding: 8px 12px; text-align: left; border-bottom: 2px solid #dfe1e6; font-weight: 600; color: #6b778c;">Тип</th>
                            <th style="padding: 8px 12px; text-align: left; border-bottom: 2px solid #dfe1e6; font-weight: 600; color: #6b778c;">Статус</th>
                            <th style="padding: 8px 12px; text-align: left; border-bottom: 2px solid #dfe1e6; font-weight: 600; color: #6b778c;">Исполнитель</th>
                            <th style="padding: 8px 12px; text-align: left; border-bottom: 2px solid #dfe1e6; font-weight: 600; color: #6b778c;">Обновлено</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($issues as $issue): ?>
                            <tr style="border-bottom: 1px solid #ebecf0; height: 52px;">
                                <td style="padding: 12px; vertical-align: top;">
                                    <?= Html::a(Html::encode($issue->issue_key), ['view', 'id' => $issue->id], [
                                        'style' => 'font-weight: 600; color: #0052cc; text-decoration: none;'
                                    ]) ?>
                                </td>
                                <td style="padding: 12px; vertical-align: top;">
                                    <?php if ($issue->issueType): ?>
                                        <span class="aui-icon-container" style="display: inline-flex; align-items: center; gap: 6px;">
                                            <span style="font-size: 14px; color: #6b778c;"><?= Html::encode(mb_substr($issue->issueType->name, 0, 1)) ?></span>
                                            <span><?= Html::encode($issue->issueType->name) ?></span>
                                        </span>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 12px; vertical-align: top;">
                                    <?php if ($issue->status): ?>
                                        <span class="aui-lozenge" style="
                                            display: inline-block;
                                            padding: 2px 8px;
                                            border-radius: 3px;
                                            font-size: 12px;
                                            font-weight: 500;
                                            background-color: <?= match($issue->status->name) {
                                                'Готово' => '#e3fcef',
                                                'В работе' => '#deebff',
                                                'Открыто' => '#fff0b3',
                                                'Закрыто' => '#e9ecef',
                                                default => '#f4f5f7'
                                            }; ?>;
                                            color: <?= match($issue->status->name) {
                                                'Готово' => '#006e52',
                                                'В работе' => '#0052cc',
                                                'Открыто' => '#573900',
                                                'Закрыто' => '#6b778c',
                                                default => '#6b778c'
                                            }; ?>;
                                        ><?= Html::encode($issue->status->name) ?></span>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 12px; vertical-align: top;">
                                    <?= $issue->assignee ? Html::encode($issue->assignee->username) : '—' ?>
                                </td>
                                <td style="padding: 12px; vertical-align: top; color: #6b778c;">
                                    <?= Yii::$app->formatter->asDatetime($issue->updated_at, 'php:d M Y H:i') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="dashboard-gadget" style="text-align: center; padding: 48px 24px; color: #6b778c; font-size: 14px;">
                    <p>Нет задач</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>