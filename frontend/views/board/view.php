<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Доска: ' . Html::encode($board->name);
?>

<div class="aui-page-panel">
    <div class="aui-page-panel-inner">
        <div class="aui-page-header">
            <div class="aui-page-header-inner">
                <div class="aui-page-header-main">
                    <h1>Доска: <?= Html::encode($board->name) ?></h1>
                </div>
            </div>
        </div>

        <!-- Kanban-доска -->
        <div class="kanban-board" style="display: flex; gap: 24px; padding: 0 24px 24px; flex-wrap: nowrap;">
            <?php
            $columns = [
                1 => 'To Do',
                2 => 'In Progress',
                3 => 'Done',
            ];
            ?>

            <?php foreach ($columns as $statusId => $title): ?>
                <div 
                    class="kanban-column" 
                    data-status="<?= $statusId ?>"
                    style="
                        flex: 1;
                        min-width: 280px;
                        background: white;
                        border: 1px solid var(--jira-border);
                        border-radius: 3px;
                        padding: 12px;
                        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
                    "
                >
                    <h3 style="
                        margin: 0 0 16px 0;
                        padding-bottom: 8px;
                        border-bottom: 2px solid var(--jira-border);
                        font-size: 14px;
                        font-weight: 600;
                        color: var(--jira-text);
                    "><?= Html::encode($title) ?></h3>

                    <div class="kanban-items" style="min-height: 80px;">
                        <?php
                        $colIssues = array_filter($issues, fn($i) => $i->status_id == $statusId);
                        foreach ($colIssues as $issue):
                        ?>
                            <div 
                                class="kanban-card" 
                                draggable="true"
                                data-issue-id="<?= $issue->id ?>"
                                style="
                                    background: white;
                                    border: 1px solid var(--jira-border);
                                    border-radius: 3px;
                                    padding: 12px;
                                    margin-bottom: 8px;
                                    cursor: move;
                                    transition: box-shadow 0.2s;
                                "
                            >
                                <div style="font-weight: 600; color: var(--jira-primary);">
                                    <?= Html::a(
                                        Html::encode($issue->issue_key),
                                        ['issue/view', 'id' => $issue->id],
                                        ['style' => 'text-decoration: none; color: var(--jira-primary);']
                                    ) ?>
                                </div>
                                <div style="margin-top: 4px; font-size: 13px; color: var(--jira-text);">
                                    <?= Html::encode($issue->summary) ?>
                                </div>
                                <?php if ($issue->assignee): ?>
                                    <div style="margin-top: 8px; font-size: 12px; color: var(--jira-text-secondary);">
                                        Назначена: <?= Html::encode($issue->assignee->username) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Drag & Drop + AJAX -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const columns = document.querySelectorAll('.kanban-column');

    columns.forEach(col => {
        col.addEventListener('dragover', e => {
            e.preventDefault();
            col.style.background = 'var(--jira-hover)';
        });

        col.addEventListener('dragleave', () => {
            col.style.background = 'white';
        });

        col.addEventListener('drop', e => {
            e.preventDefault();
            col.style.background = 'white';

            const card = document.querySelector('.dragging');
            if (!card) return;

            const issueId = card.dataset.issueId;
            const newStatusId = col.dataset.status;

            fetch('<?= Url::to(['/issue/update-status']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>'
                },
                body: JSON.stringify({ id: issueId, status_id: newStatusId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    col.querySelector('.kanban-items').appendChild(card);
                } else {
                    alert('Ошибка при обновлении задачи');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Не удалось обновить статус');
            });
        });
    });

    document.querySelectorAll('.kanban-card').forEach(card => {
        card.addEventListener('dragstart', () => {
            card.classList.add('dragging');
            card.style.opacity = '0.7';
        });
        card.addEventListener('dragend', () => {
            card.classList.remove('dragging');
            card.style.opacity = '1';
        });
    });
});
</script>