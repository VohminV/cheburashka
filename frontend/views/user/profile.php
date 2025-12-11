<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$this->title = 'Профиль';
?>

<div class="aui-page-panel">
    <div class="aui-page-panel-inner">
        <div class="aui-page-header">
            <div class="aui-page-header-inner">
                <div class="aui-page-header-main">
                    <h1>Профиль пользователя</h1>
                </div>
            </div>
        </div>

        <div class="dashboard-gadget">
            <div class="mod-content" style="line-height: 1.6; color: var(--jira-text);">
                <div style="display: flex; gap: 24px; align-items: flex-start;">
                    <!-- Аватар -->
                    <div>
                        <span class="aui-avatar aui-avatar-project" style="
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background-color: var(--jira-primary);
                            color: white;
                            font-weight: bold;
                            width: 64px;
                            height: 64px;
                            border-radius: 4px;
                            font-size: 28px;
                        ">
                            <?= strtoupper(mb_substr(Html::encode($user->username), 0, 1, 'UTF-8')) ?>
                        </span>
                    </div>

                    <!-- Данные -->
                    <div style="flex: 1;">
                        <dl style="margin: 0; display: grid; grid-template-columns: max-content 1fr; gap: 8px 16px;">
                            <dt style="font-weight: bold; color: var(--jira-text);">Имя пользователя:</dt>
                            <dd style="color: var(--jira-text-secondary); margin: 0;"><?= Html::encode($user->username) ?></dd>

                            <?php if (!empty($user->email)): ?>
                                <dt style="font-weight: bold; color: var(--jira-text);">Email:</dt>
                                <dd style="color: var(--jira-text-secondary); margin: 0;"><?= Html::encode($user->email) ?></dd>
                            <?php endif; ?>

                            <?php if (!empty($user->full_name)): ?>
                                <dt style="font-weight: bold; color: var(--jira-text);">Полное имя:</dt>
                                <dd style="color: var(--jira-text-secondary); margin: 0;"><?= Html::encode($user->full_name) ?></dd>
                            <?php endif; ?>

                            <dt style="font-weight: bold; color: var(--jira-text);">ID:</dt>
                            <dd style="color: var(--jira-text-secondary); margin: 0;"><?= Html::encode($user->id) ?></dd>

                            <dt style="font-weight: bold; color: var(--jira-text);">Роль:</dt>
                            <dd style="color: var(--jira-text-secondary); margin: 0;">Участник</dd>
                        </dl>
                    </div>
                </div>

                <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--jira-border);">
                    <a href="/index.php?r=site%2Flogout" data-method="post" class="aui-button aui-button-subtle" style="font-size: 14px;">
                        Выйти из системы
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>