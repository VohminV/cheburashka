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
	<meta name="csrf-token" content="<?= Yii::$app->request->csrfToken ?>">
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
						<img src="<?= Yii::getAlias('@web') ?>/images/logo.png"
							 alt="Чебурашка"
							 style="height: 28px; vertical-align: middle; margin-right: 8px;">
						<span class="aui-header-logo-text">Чебурашка</span>
					</a>
				</span>

                    <ul class="aui-nav">
						<li>
							<?= Html::a('Рабочий стол', ['/'], [
								'class' => 'aui-nav-link'
							]) ?>
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
                            <div class="aui-dropdown2 aui-style-default" id="find_link-content">
								<!-- Поиск и текущий запрос -->
								<div class="aui-dropdown2-section">
									<ul class="aui-list-truncate">
										<li><a href="<?= \yii\helpers\Url::to(['/issue']) ?>">Поиск задач</a></li>
										<!-- Можно добавить "Текущий поиск", если поддерживается -->
									</ul>
								</div>

								<!-- Недавние задачи -->
								<?php
								// Получаем последние 5 задач, просматриваемых пользователем (или просто последние созданные)
								$recentIssues = \common\models\Issue::find()
									->with('project')
									->orderBy('updated_at DESC')
									->limit(5)
									->all();
								?>
								<?php if (!empty($recentIssues)): ?>
									<div class="aui-dropdown2-section">
										<strong>Недавние задачи</strong>
										<ul class="aui-list-truncate">
											<?php foreach ($recentIssues as $issue): ?>
												<li>
													<a href="<?= \yii\helpers\Url::to(['/issue/view', 'id' => $issue->id]) ?>"
													   class="aui-icon-container issue-link"
													   data-issue-key="<?= $issue->issue_key ?>">
														<?= $issue->issue_key ?> <?= \yii\helpers\Html::encode($issue->summary) ?>
													</a>
												</li>
											<?php endforeach; ?>
											<li><a href="<?= \yii\helpers\Url::to(['/issue']) ?>" class="filter-link">еще...</a></li>
										</ul>
									</div>
								<?php endif; ?>

								<!-- Фильтры -->
								<div class="aui-dropdown2-section">
									<strong>Фильтры</strong>
									<ul class="aui-list-truncate">
										<li><a href="<?= \yii\helpers\Url::to(['/issue', 'assignee_id' => Yii::$app->user->id, 'status_id' => 2]) ?>" class="filter-link">Мои задачи в работе</a></li>
										<li><a href="<?= \yii\helpers\Url::to(['/issue', 'reporter_id' => Yii::$app->user->id]) ?>" class="filter-link">Сообщённые мной</a></li>
									</ul>
								</div>

								<!-- Управление (опционально) -->
								<div class="aui-dropdown2-section">
									<ul class="aui-list-truncate">
										<li><a href="#" style="color: #6b778c; cursor: not-allowed;" title="Планируется в будущем">Импортировать из CSV</a></li>
										<li><a href="<?= \yii\helpers\Url::to(['/issue']) ?>">Управление фильтрами</a></li>
									</ul>
								</div>
							</div>
                        </li>
						<li>
							<a href="#" class="aui-nav-link aui-dropdown2-trigger" id="greenhopper_menu" aria-haspopup="true" aria-controls="greenhopper_menu-content">Доски</a>
							<div class="aui-dropdown2 aui-style-default" id="greenhopper_menu-content">
								<?php
								// Получаем до 3 последних досок
								$recentBoards = \common\models\Board::find()
									->orderBy('updated_at DESC')
									->limit(5)
									->all();
								?>
								<?php if (!empty($recentBoards)): ?>
									<div class="aui-dropdown2-section">
										<strong>Недавние доски</strong>
										<ul class="aui-list-truncate">
											<?php foreach ($recentBoards as $board): ?>
												<li>
													<a href="<?= \yii\helpers\Url::to(['/board/view', 'id' => $board->id]) ?>">
														<?= \yii\helpers\Html::encode($board->name) ?>
													</a>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif; ?>

								<div class="aui-dropdown2-section">
									<ul class="aui-list-truncate">
										<li>
											<a href="<?= \yii\helpers\Url::to(['/board']) ?>">Просмотр всех досок</a>
										</li>
									</ul>
								</div>
							</div>
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
							<li id="quicksearch-menu" class="aui-quicksearch-item">
								<form action="<?= Url::to(['/issue/search']) ?>" method="get" class="aui-quicksearch">
									<input type="text"
										   name="q"
										   placeholder="Поиск"
										   class="search"
										   value="<?= htmlspecialchars(Yii::$app->request->get('q', '')) ?>"
										   autocomplete="off" />
									<button type="submit" class="aui-quicksearch-button" aria-label="Поиск">
										🔍
									</button>
								</form>
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

<script>
document.addEventListener('keydown', function (e) {
    // Игнорируем, если фокус в input, textarea, select
    if (['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) {
        return;
    }

    // Игнорируем, если нажаты Ctrl/Cmd/Alt (чтобы не конфликтовать с системными)
    if (e.ctrlKey || e.metaKey || e.altKey) {
        return;
    }

    const key = e.key.toLowerCase();

    // Открыть поиск задач
    if (key === '/') {
        e.preventDefault();
        const searchInput = document.querySelector('.aui-quicksearch .search');
        if (searchInput) {
            searchInput.focus();
        }
        return;
    }

    // Справка (эта страница)
    if (key === '?') {
        e.preventDefault();
        window.location.href = '/index.php?r=site%2Fkeyboard-shortcuts';
        return;
    }

    // Создать задачу
    if (key === 'c') {
        e.preventDefault();
        window.location.href = '/index.php?r=issue%2Fcreate';
        return;
    }

    // Перейти (Go to...)
    if (key === 'g') {
        e.preventDefault();
        // Ждём вторую клавишу в течение 1.5 сек
        let secondKey = null;
        const timeout = setTimeout(() => {
            // Если вторая клавиша не нажата — ничего не делаем
        }, 1500);

        const handler = function (event) {
            secondKey = event.key.toLowerCase();
            clearTimeout(timeout);
            document.removeEventListener('keydown', handler);

            switch (secondKey) {
                case 'd': // Dashboard
                    window.location.href = '/index.php?r=';
                    break;
                case 'p': // Projects
                    window.location.href = '/index.php?r=project';
                    break;
                case 't': // Go to issue by key (откроем форму поиска)
                    const search = document.querySelector('.aui-quicksearch .search');
                    if (search) {
                        search.focus();
                    }
                    break;
            }
        };

        document.addEventListener('keydown', handler);
    }
});
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>