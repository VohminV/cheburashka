<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="aui-layout aui-theme-default page-type-dashboard">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>Чебурашка</title>
    <?php $this->head() ?>
    <?= Html::cssFile('@web/css/jira.css') ?>
</head>
<body id="jira" class="aui-layout aui-theme-default page-type-dashboard">
<?php $this->beginBody() ?>

<div id="page">
    <header id="header" role="banner">
        <nav class="aui-header aui-dropdown2-trigger-group" aria-label="Site">
            <div class="aui-header-inner">
                <div class="aui-header-primary">
                    <span id="logo" class="aui-header-logo aui-header-logo-custom">
                        <a href="<?= Url::to(['/']) ?>" aria-label="На главную" class="aui-header-logo-text">
                            <span class="aui-header-logo-text">Чебурашка</span>
                        </a>
                    </span>

                    <ul class="aui-nav">
                        <li>
                            <?= Html::a('Рабочий стол', ['/'], [
                                'class' => 'aui-nav-link aui-dropdown2-trigger',
                                'id' => 'home_link',
                                'aria' => ['haspopup' => 'true', 'controls' => 'home_link-content']
                            ]) ?>
                            <div class="aui-dropdown2 aui-style-default" id="home_link-content"></div>
                        </li>
                        <li>
                            <?= Html::a('Проекты', ['/project'], [
                                'class' => 'aui-nav-link aui-dropdown2-trigger',
                                'id' => 'browse_link',
                                'aria' => ['haspopup' => 'true', 'controls' => 'browse_link-content']
                            ]) ?>
                            <div class="aui-dropdown2 aui-style-default" id="browse_link-content">
								<?php if (!Yii::$app->user->isGuest): ?>
									<div class="aui-dropdown2-section">
										<strong>Ваши проекты</strong>
										<ul class="aui-list-truncate">
											<?php foreach (Yii::$app->user->identity->getProjects() as $project): ?>
												<li>
													<?= Html::a(
														Html::encode($project->name) . ' (' . $project->project_key . ')',
														['/project/view', 'id' => $project->id],
														['class' => 'aui-icon-container']
													) ?>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif; ?>
								<div class="aui-dropdown2-section">
									<ul class="aui-list-truncate">
										<li><?= Html::a('Просмотр всех проектов', ['/project']) ?></li>
									</ul>
								</div>
							</div>
                        </li>
                        <li>
                            <?= Html::a('Задачи', ['/issue'], [
                                'class' => 'aui-nav-link aui-dropdown2-trigger',
                                'id' => 'find_link',
                                'aria' => ['haspopup' => 'true', 'controls' => 'find_link-content']
                            ]) ?>
                            <div class="aui-dropdown2 aui-style-default" id="find_link-content"></div>
                        </li>
                        <li>
                            <a href="#" class="aui-nav-link aui-dropdown2-trigger" id="greenhopper_menu" aria-haspopup="true" aria-controls="greenhopper_menu-content">Доски</a>
                            <div class="aui-dropdown2 aui-style-default" id="greenhopper_menu-content"></div>
                        </li>
                        <li id="create-menu">
                            <?= Html::a('Создать', ['/issue/create'], [
                                'class' => 'aui-button aui-button-primary',
                                'id' => 'create_link',
                                'title' => 'Создать новую задачу'
                            ]) ?>
                        </li>
                    </ul>
                </div>

                <div class="aui-header-secondary">
                    <ul class="aui-nav">
                        <li id="quicksearch-menu">
							<span class="aui-nav-link">
								<form action="<?= Url::to(['/issue/search']) ?>" method="get" class="aui-quicksearch">
									<input type="text" name="q" placeholder="Поиск" class="search" />
									<input type="submit" class="hidden" value="Поиск">
								</form>
							</span>
						</li>
                       <?php if (!Yii::$app->user->isGuest): ?>
							<li id="user-options">
								<a class="aui-dropdown2-trigger aui-dropdown2-trigger-arrowless" aria-haspopup="true" aria-controls="user-options-content">
									<span class="aui-avatar aui-avatar-small">
										<span class="aui-avatar-inner">
											<img src="https://www.gravatar.com/avatar/?d=mp&s=24" alt="<?= Yii::$app->user->identity->username ?>" />
										</span>
									</span>
								</a>
								<div id="user-options-content" class="aui-dropdown2 aui-style-default">
									<div class="aui-dropdown2-section">
										<ul class="aui-list-truncate">
											<li><?= Html::a('Профиль', ['/user/profile']) ?></li>
											<li><?= Html::a('Специальные возможности', ['/user/accessibility']) ?></li>
											<li><?= Html::a('Выйти', ['/site/logout'], ['data-method' => 'post']) ?></li>
										</ul>
									</div>
								</div>
							</li>
						<?php else: ?>
							<li><?= Html::a('Войти', ['/site/login'], ['class' => 'aui-nav-link']) ?></li>
						<?php endif; ?>
						<li id="system-help-menu">
                            <a class="aui-nav-link aui-dropdown2-trigger aui-dropdown2-trigger-arrowless" id="help_menu" aria-haspopup="true" aria-controls="system-help-menu-content">
                                <span class="aui-icon aui-icon-small aui-iconfont-question-filled">Справка</span>
                            </a>
                            <div id="system-help-menu-content" class="aui-dropdown2 aui-style-default">
                                <div class="aui-dropdown2-section">
                                    <ul id="jira-help" class="aui-list-truncate">
                                        <li><?= Html::a('Горячие клавиши', ['/site/keyboard-shortcuts']) ?></li>
                                        <li><?= Html::a('О Чебурашке', ['/site/about']) ?></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div id="content">
        <?= $content ?>
    </div>

    <footer id="footer" role="contentinfo">
        <section class="footer-body">
            <ul class="atlassian-footer">
                <li>Чебурашка • Jira-Style Dashboard</li>
            </ul>
        </section>
    </footer>
</div>

<!-- Исправленные CDN (без пробелов!) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {
    $('.aui-nav > li').hover(
        function() { $(this).find('.aui-dropdown2').show(); },
        function() { $(this).find('.aui-dropdown2').hide(); }
    );
});
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>