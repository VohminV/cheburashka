<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div id="dashboard" class="dashboard">
    <div class="aui-page-header">
        <h1>Рабочий стол</h1>
    </div>
    <div class="dashboard-columns">
        <div style="flex:1">
            <?= $this->render('_gadget', ['title' => 'Лента активности', 'content' => '<p><em>Пока недоступно</em></p>']) ?>
            <?= $this->render('_gadget', ['title' => 'Наблюдаемые запросы', 'content' => '<p>Нет задач</p>']) ?>
        </div>
        <div style="flex:1">
            <?= $this->render('_gadget', ['title' => 'Назначенные мне', 'content' => '<p>Нет задач</p>']) ?>
            <?= $this->render('_gadget', ['title' => 'Запросов в работе', 'content' => '<p>Нет задач</p>']) ?>
            <?= $this->render('_gadget', [
                'title' => 'Круговая диаграмма',
                // Обёртка с фиксированной высотой — ключ к стабильности
                'content' => '<div style="height: 220px;"><canvas id="issuesPieChart" style="display:block; width:100%; height:200px;"></canvas></div>'
            ]) ?>
        </div>
    </div>
</div>

<?php if (!empty($statusData)): ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('issuesPieChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: { // ← ИСПРАВЛЕНО: добавлено "data:"
            labels: <?= json_encode(array_column($statusData, 'status'), JSON_UNESCAPED_UNICODE) ?>,
            datasets: [{
                data: <?= json_encode(array_column($statusData, 'count')) ?>,
                backgroundColor: ['#0052CC', '#00875A', '#DE350B', '#FF8F00', '#6554C0', '#00C7E6'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // ← КЛЮЧЕВОЙ ПАРАМЕТР — не даёт диаграмме "вылезать"
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
<?php else: ?>
<script>
// Заглушка: если данных нет — пишем текст на canvas
document.addEventListener('DOMContentLoaded', () => {
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