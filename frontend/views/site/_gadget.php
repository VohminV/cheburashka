<!-- frontend/views/site/_gadget.php -->
<?php
use yii\helpers\Html;
?>

<div class="dashboard-gadget">
    <h3><?= Html::encode($title) ?></h3>
    <?= $content ?>
</div>