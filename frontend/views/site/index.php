<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Рабочий стол';

// Функция рендеринга списка задач
$renderIssueList = function ($issues) {
    if (empty($issues)) {
        return '<p>Нет задач</p>';
    }
    $lines = [];
    foreach ($issues as $issue) {
        $lines[] = Html::a(
            Html::encode($issue->issue_key . ': ' . $issue->summary),
            Url::to(['issue/view', 'id' => $issue->id]),
            ['style' => 'display: block; margin-bottom: 4px; color: var(--jira-text);']
        );
    }
    return implode('', $lines);
};

// Лента активности
$activityContent = '<p><em>Пока нет активности</em></p>';
if (!empty($activities)) {
    $lines = [];
    foreach ($activities as $act) {
        $user = $act->user ? Html::encode($act->user->username) : 'система';
        if ($act->field === 'created') {
            $text = 'Создана задача ' . Html::a($act->issue->issue_key, ['issue/view', 'id' => $act->issue->id]);
        } else {
            $fieldLabel = $act->issue->getAttributeLabel($act->field);
            $old = $act->old_value ?? '(пусто)';
            $new = $act->new_value ?? '(пусто)';
            $text = "Изменено «{$fieldLabel}» у " . Html::a($act->issue->issue_key, ['issue/view', 'id' => $act->issue->id]) . ": {$old} → {$new}";
        }
        $time = Yii::$app->formatter->asRelativeTime($act->created_at);
        $lines[] = "<p><strong>{$user}</strong> — {$text} <em>({$time})</em></p>";
    }
    $activityContent = implode('', $lines);
}
?>

<div class="aui-page-panel">
    <div class="aui-page-panel-inner">
        <div class="aui-page-header">
            <div class="aui-page-header-inner">
                <div class="aui-page-header-main">
                    <h1>Рабочий стол</h1>
                </div>
            </div>
        </div>

        <div class="dashboard-columns">
            <!-- Левая колонка -->
            <div class="dashboard-column">
                <?= $this->render('_gadget', [
                    'title' => 'Лента активности',
                    'content' => $activityContent
                ]) ?>
                <?= $this->render('_gadget', [
                    'title' => 'Наблюдаемые запросы',
                    'content' => $renderIssueList($watched)
                ]) ?>
            </div>

            <!-- Правая колонка -->
            <div class="dashboard-column">
                <?= $this->render('_gadget', [
                    'title' => 'Назначенные мне',
                    'content' => $renderIssueList($assigned)
                ]) ?>
                <?= $this->render('_gadget', [
                    'title' => 'Запросов в работе',
                    'content' => $renderIssueList($inProgress)
                ]) ?>
                <?= $this->render('_gadget', [
                    'title' => 'Круговая диаграмма',
                    'content' => '<div style="height: 220px;"><canvas id="issuesPieChart"></canvas></div>'
                ]) ?>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php if (!empty($statusData)): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('issuesPieChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_column($statusData, 'status'), JSON_UNESCAPED_UNICODE) ?>,
            datasets: [{
                data: <?= json_encode(array_column($statusData, 'count')) ?>,
                backgroundColor: ['#0052CC', '#00875A', '#DE350B', '#FF8F00', '#6554C0', '#00C7E6'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
</script>
<?php else: ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('issuesPieChart');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#666';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.font = '14px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        ctx.fillText('Нет данных', canvas.width / 2, canvas.height / 2);
    }
});
</script>
<?php endif; ?>