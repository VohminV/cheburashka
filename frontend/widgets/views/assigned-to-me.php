<div class="dashboard-gadget">
    <h3>Назначенные мне</h3>
    <ul>
        <?php foreach ($issues as $issue): ?>
            <li><?= $issue->issue_key ?>: <?= $issue->summary ?></li>
        <?php endforeach; ?>
    </ul>
</div>