<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Issue */
/* @var $commentModel common\models\IssueComment */
?>

<?= $this->render('_project_sidebar', ['project' => $model->project]) ?>

<div class="aui-page-panel">
    <div class="aui-page-panel-inner">

        <!-- ЗАГОЛОВОК -->
        <div class="aui-page-header">
            <div class="aui-page-header-inner">
                <!-- Аватар проекта -->
                <div class="aui-page-header-image">
                    <a href="<?= Url::to(['/project/view', 'id' => $model->project->id]) ?>" title="<?= Html::encode($model->project->name) ?>">
                        <span class="aui-avatar aui-avatar-project" style="
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background-color: var(--jira-primary);
                            color: white;
                            font-weight: bold;
                            width: 40px;
                            height: 40px;
                            border-radius: 4px;
                        ">
                            <?= strtoupper(mb_substr(Html::encode($model->project->project_key ?? $model->project->name), 0, 1, 'UTF-8')) ?>
                        </span>
                    </a>
                </div>

                <!-- Основной контент заголовка -->
                <div class="aui-page-header-main">
                    <ol class="aui-nav aui-nav-breadcrumbs">
                        <li><?= Html::a(Html::encode($model->project->name), ['/project/view', 'id' => $model->project->id]) ?></li>
                        <li><?= Html::encode($model->issue_key) ?></li>
                    </ol>
                    <h1><?= Html::encode($model->summary) ?></h1>
                </div>

                <!-- Действия -->
                <div class="aui-page-header-actions">
                    <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'aui-button aui-button-subtle']) ?>
                </div>
            </div>
        </div>

        <!-- ПАНЕЛЬ ДЕЙСТВИЙ -->
        <div class="command-bar">
            <div class="aui-toolbar2">
                <div class="aui-toolbar2-inner">
                    <div class="aui-toolbar2-primary">
                        <?= Html::a('<span class="aui-icon aui-icon-small">✎</span> Редактировать', ['update', 'id' => $model->id], [
                            'class' => 'aui-button toolbar-trigger',
                            'encode' => false
                        ]) ?>
                        <?= Html::button('<span class="aui-icon aui-icon-small">💬</span> Комментарий', [
                            'class' => 'aui-button toolbar-trigger',
                            'onclick' => 'document.getElementById("comment-form").scrollIntoView({behavior: "smooth"});',
                            'encode' => false
                        ]) ?>
                    </div>
					<div class="aui-toolbar2-secondary">
						<?= Html::a('📤 Экспорт', '#', ['class' => 'aui-button aui-button-subtle']) ?>

						<!-- Контейнер для кнопки и выпадающего меню -->
						<div style="position: relative; display: inline-block;">
							<!-- Триггер меню -->
							<a href="#"
							   class="aui-button aui-button-light aui-dropdown2-trigger js-issue-actions-trigger"
							   aria-haspopup="true"
							   aria-expanded="false"
							   role="button"
							   style="text-decoration: none;">
								<span class="aui-icon aui-icon-small">⋯</span>
								<span class="aui-icon-dropdown" style="margin-left: 4px; vertical-align: middle;"></span>
							</a>

							<!-- Выпадающее меню (сразу после кнопки, внутри relative-контейнера) -->
							<div class="aui-dropdown2 js-issue-actions-dropdown"
								 style="display: none; position: absolute; top: 100%; left: 0; z-index: 1000;
										min-width: 200px; margin-top: 4px;
										box-shadow: 0 4px 12px rgba(0,0,0,0.15);
										border: 1px solid #dfe1e6;
										background: #fff;
										border-radius: 3px;">
								<!-- Section 1: Работа -->
								<div class="aui-dropdown2-section">
									<div class="aui-dropdown2-item-group" role="group">
										<?= Html::a(
											'<span class="trigger-label">Вести журнал работы</span>',
											'#',
											[
												'class' => 'aui-dropdown2-item issueaction-log-work',
												'role' => 'menuitem',
												'tabindex' => '-1',
												'data-url' => Url::to(['worklog/create', 'issue_id' => $model->id]),
												'data-title' => 'Вести журнал работы',
												'style' => 'display: block; padding: 6px 12px; color: #172b4d; text-decoration: none;',
											]
										) ?>
									</div>
								</div>

								<!-- Section 2: Вложения -->
								<div class="aui-dropdown2-section">
									<div class="aui-dropdown2-item-group" role="group">
										<?= Html::a('<span class="trigger-label">Прикрепить файлы</span>', Url::to(['attachment/upload', 'id' => $model->id]), [
											'class' => 'aui-dropdown2-item',
											'role' => 'menuitem',
											'tabindex' => '-1',
											'style' => 'display: block; padding: 6px 12px; color: #172b4d; text-decoration: none;',
										]) ?>
										<?= Html::a('<span class="trigger-label">Прикрепить скриншоты</span>', '#', [
											'class' => 'aui-dropdown2-item',
											'role' => 'menuitem',
											'tabindex' => '-1',
											'style' => 'display: block; padding: 6px 12px; color: #172b4d; text-decoration: none;',
											'onclick' => 'alert("Скриншоты — в будущем!"); return false;',
										]) ?>
									</div>
								</div>

								<!-- Section 3: Наблюдатели -->
								<div class="aui-dropdown2-section">
									<div class="aui-dropdown2-item-group" role="group">
										<?= Html::a('<span class="trigger-label">Наблюдать за задачей</span>', '#', [
											'class' => 'aui-dropdown2-item',
											'role' => 'menuitem',
											'tabindex' => '-1',
											'style' => 'display: block; padding: 6px 12px; color: #172b4d; text-decoration: none;',
										]) ?>
										<?= Html::a('<span class="trigger-label">Наблюдатели</span>', Url::to(['issue/manage-watchers', 'id' => $model->id]), [
											'class' => 'aui-dropdown2-item',
											'role' => 'menuitem',
											'tabindex' => '-1',
											'style' => 'display: block; padding: 6px 12px; color: #172b4d; text-decoration: none;',
										]) ?>
									</div>
								</div>

								<!-- Section 4: Подзадачи -->
								<div class="aui-dropdown2-section">
									<div class="aui-dropdown2-item-group" role="group">
										<?= Html::a('<span class="trigger-label">Создать подзадачу</span>', Url::to(['issue/create-subtask', 'parentId' => $model->id]), [
											'class' => 'aui-dropdown2-item',
											'role' => 'menuitem',
											'tabindex' => '-1',
											'style' => 'display: block; padding: 6px 12px; color: #172b4d; text-decoration: none;',
										]) ?>
										<?= Html::a('<span class="trigger-label">Преобразовать в подзадачу</span>', '#', [
											'class' => 'aui-dropdown2-item',
											'role' => 'menuitem',
											'tabindex' => '-1',
											'style' => 'display: block; padding: 6px 12px; color: #172b4d; text-decoration: none;',
										]) ?>
									</div>
								</div>

								<!-- Section 5: Прочее -->
								<div class="aui-dropdown2-section">
									<div class="aui-dropdown2-item-group" role="group">
										<?= Html::a('<span class="trigger-label">Создать связанную задачу</span>', '#', [
											'class' => 'aui-dropdown2-item',
											'role' => 'menuitem',
											'tabindex' => '-1',
											'style' => 'display: block; padding: 6px 12px; color: #172b4d; text-decoration: none;',
										]) ?>
										<?= Html::a('<span class="trigger-label">Переместить</span>', Url::to(['issue/move', 'id' => $model->id]), [
											'class' => 'aui-dropdown2-item',
											'role' => 'menuitem',
											'tabindex' => '-1',
											'style' => 'display: block; padding: 6px 12px; color: #172b4d; text-decoration: none;',
										]) ?>
										<?= Html::a('<span class="trigger-label">Связать</span>', '#', [
											'class' => 'aui-dropdown2-item',
											'role' => 'menuitem',
											'tabindex' => '-1',
											'style' => 'display: block; padding: 6px 12px; color: #172b4d; text-decoration: none;',
										]) ?>
										<?= Html::a('<span class="trigger-label">Клонировать</span>', Url::to(['issue/clone', 'id' => $model->id]), [
											'class' => 'aui-dropdown2-item',
											'role' => 'menuitem',
											'tabindex' => '-1',
											'style' => 'display: block; padding: 6px 12px; color: #172b4d; text-decoration: none;',
										]) ?>
										<?= Html::a('<span class="trigger-label">Метки</span>', '#', [
											'class' => 'aui-dropdown2-item',
											'role' => 'menuitem',
											'tabindex' => '-1',
											'style' => 'display: block; padding: 6px 12px; color: #172b4d; text-decoration: none;',
										]) ?>
									</div>
								</div>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>

        <!-- ОСНОВНОЙ КОНТЕНТ -->
        <div class="aui-group issue-body">

            <!-- ЛЕВАЯ КОЛОНКА -->
            <div class="aui-item issue-main-column">

                <!-- ДЕТАЛИ -->
                <div class="module toggle-wrap">
                    <div class="mod-header">
                        <h4>Детали задачи</h4>
                    </div>
                    <div class="mod-content">
                        <ul class="property-list two-cols">
                            <li class="item">
                                <div class="wrap">
                                    <span class="name">Тип:</span>
                                    <span class="value">
                                        <?php if ($model->issueType): ?>
                                            <span style="display: inline-flex; align-items: center; gap: 6px; color: var(--jira-text-secondary);">
                                                <span><?= mb_substr($model->issueType->name, 0, 1) ?></span>
                                                <?= Html::encode($model->issueType->name) ?>
                                            </span>
                                        <?php else: ?>—<?php endif; ?>
                                    </span>
                                </div>
                            </li>
                            <li class="item">
                                <div class="wrap">
                                    <span class="name">Статус:</span>
                                    <span class="value">
                                        <?php if ($model->status): ?>
                                            <span class="aui-lozenge" style="
                                                background-color: <?= match($model->status->name) {
                                                    'Готово' => '#e3fcef',
                                                    'В работе' => '#deebff',
                                                    'Открыто' => '#fff0b3',
                                                    'Закрыто' => '#e9ecef',
                                                    default => '#f4f5f7'
                                                }; ?>;
                                                color: <?= match($model->status->name) {
                                                    'Готово' => '#006e52',
                                                    'В работе' => 'var(--jira-primary)',
                                                    'Открыто' => '#573900',
                                                    'Закрыто' => 'var(--jira-text-secondary)',
                                                    default => 'var(--jira-text-secondary)'
                                                }; ?>;
                                            "><?= Html::encode($model->status->name) ?></span>
                                        <?php else: ?>—<?php endif; ?>
                                    </span>
                                </div>
                            </li>
                            <li class="item">
                                <div class="wrap">
                                    <span class="name">Приоритет:</span>
                                    <span class="value"><?= $model->priority ? Html::encode($model->priority->name) : '—' ?></span>
                                </div>
                            </li>
                            <li class="item">
                                <div class="wrap">
                                    <span class="name">Решение:</span>
                                    <span class="value"><?= $model->resolution ? Html::encode($model->resolution->name) : 'Нет решения' ?></span>
                                </div>
                            </li>
                            <li class="item" style="grid-column: span 2;">
                                <div class="wrap">
                                    <span class="name">Метки:</span>
                                    <span class="value">
                                        <?php if (!empty($model->labels)): ?>
                                            <?php foreach (explode(',', $model->labels) as $label): ?>
                                                <span class="aui-lozenge" style="background: #e0e0e0; color: #333; margin-right: 4px; margin-top: 4px; display: inline-block;">
                                                    <?= Html::encode(trim($label)) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php else: ?>—<?php endif; ?>
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- ОПИСАНИЕ -->
                <div class="module toggle-wrap">
                    <div class="mod-header">
                        <h4>Описание</h4>
                    </div>
                    <div class="user-content-block mod-content">
                        <?= $model->description ? nl2br(Html::encode($model->description)) : '<em>Нет описания</em>' ?>
                    </div>
                </div>

                <!-- ВЛОЖЕНИЯ -->
				<?= $this->render('_attachments', ['model' => $model]) ?>

                <!-- КОММЕНТАРИИ -->
                <?= $this->render('_comments', ['model' => $model, 'commentModel' => $commentModel]) ?>

            </div>

            <!-- ПРАВАЯ КОЛОНКА -->
            <div class="aui-item issue-side-column">

                <!-- Люди -->
                <div class="module toggle-wrap">
                    <div class="mod-header">
                        <h4 class="toggle-title">Люди</h4>
                    </div>
                    <div class="mod-content">
                        <dl>
                            <dt>Исполнитель:</dt>
                            <dd>
                                <?php if ($model->assignee): ?>
                                    <span class="view-issue-field editable-field inactive">
                                        <span class="aui-avatar aui-avatar-small">
                                            <span class="aui-avatar-inner">
                                                <span style="
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    width: 100%;
                                                    height: 100%;
                                                    background: var(--jira-primary);
                                                    color: white;
                                                    font-size: 10px;
                                                    border-radius: 50%;
                                                "><?= mb_substr(Html::encode($model->assignee->username), 0, 1, 'UTF-8') ?></span>
                                            </span>
                                        </span>
                                        <?= Html::encode($model->assignee->username) ?>
                                        <span class="overlay-icon aui-icon aui-icon-small aui-iconfont-edit"></span>
                                    </span>
                                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->id != $model->assignee_id): ?>
                                        <span class="assign-to-me-link">
                                            <?= Html::a('Назначить меня', ['assign-to-me', 'id' => $model->id]) ?>
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="unassigned">—</span>
                                    <?php if (!Yii::$app->user->isGuest): ?>
                                        <span class="assign-to-me-link">
                                            <?= Html::a('Назначить меня', ['assign-to-me', 'id' => $model->id]) ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </dd>

                            <dt>Автор:</dt>
                            <dd>
                                <?php if ($model->reporter): ?>
                                    <span class="view-issue-field">
                                        <span class="aui-avatar aui-avatar-small">
                                            <span class="aui-avatar-inner">
                                                <span style="
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    width: 100%;
                                                    height: 100%;
                                                    background: var(--jira-text-secondary);
                                                    color: white;
                                                    font-size: 10px;
                                                    border-radius: 50%;
                                                "><?= mb_substr(Html::encode($model->reporter->username), 0, 1, 'UTF-8') ?></span>
                                            </span>
                                        </span>
                                        <?= Html::encode($model->reporter->username) ?>
                                    </span>
                                <?php else: ?>—<?php endif; ?>
                            </dd>

                            <dt>Наблюдатели:</dt>
                            <dd>
                                <a href="#" id="view-watcher-list" aria-label="Посмотреть наблюдателей">
                                    <span class="aui-badge"><?= count($model->getWatchers()->all()) ?></span>
                                </a>
                                <?php if (!Yii::$app->user->isGuest): ?>
                                    <?php
                                    $isWatching = \common\models\IssueWatcher::find()
                                        ->where(['issue_id' => $model->id, 'user_id' => Yii::$app->user->id])
                                        ->exists();
                                    ?>
                                    <a id="watching-toggle"
                                       class="<?= $isWatching ? 'watch-state-on' : 'watch-state-off' ?>"
                                       href="<?= Url::to(['issue/watch', 'id' => $model->id]) ?>">
                                        <?= $isWatching ? 'Прекратить наблюдение' : 'Начать наблюдение за задачей' ?>
                                    </a>
                                <?php endif; ?>
                            </dd>
                        </dl>
                    </div>
                </div>

                <!-- Даты -->
                <div class="module toggle-wrap">
                    <div class="mod-header">
                        <h4 class="toggle-title">Даты</h4>
                    </div>
                    <div class="mod-content">
                        <dl class="dates">
                            <dt>Создано:</dt>
                            <dd title="<?= Yii::$app->formatter->asDatetime($model->created_at, 'php:d.m.Y H:i:s') ?>">
                                <time datetime="<?= date(DATE_W3C, strtotime($model->created_at)) ?>">
                                    <?= Yii::$app->formatter->asDatetime($model->created_at, 'php:d M Y H:i') ?>
                                </time>
                            </dd>
                        </dl>
                        <dl class="dates">
                            <dt>Обновлено:</dt>
                            <dd title="<?= Yii::$app->formatter->asDatetime($model->updated_at, 'php:d.m.Y H:i:s') ?>">
                                <time datetime="<?= date(DATE_W3C, strtotime($model->updated_at)) ?>">
                                    <?= Yii::$app->formatter->asDatetime($model->updated_at, 'php:d M Y H:i') ?>
                                </time>
                            </dd>
                        </dl>
                    </div>
                </div>

            </div>
        </div>
    </div>
	<!-- Выпадающее меню — ОБЯЗАТЕЛЬНО ВНЕ .command-bar -->
</div>

<!-- Модальное окно: Вести журнал работы -->
<div id="worklog-modal" style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 1050;
    display: none;
    align-items: center;
    justify-content: center;
">
    <!-- ДОБАВЬТЕ ЭТОТ КОНТЕЙНЕР -->
    <div class="modal-dialog" style="max-width: 800px; width: 90%;">
        <div style="
            background: white;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-height: 90vh;
            overflow: auto;
        ">
            <div style="
                padding: 16px;
                border-bottom: 1px solid #dfe1e6;
                display: flex;
                justify-content: space-between;
                align-items: center;
            ">
                <h5 id="worklog-modal-title">Загрузка...</h5>
                <button id="worklog-modal-close" type="button" style="
                    background: none;
                    border: none;
                    font-size: 24px;
                    cursor: pointer;
                    color: #5e6c84;
                ">×</button>
            </div>
            <div id="worklog-modal-body" style="padding: 20px;">
                <div class="text-center">Загрузка формы...</div>
            </div>
        </div>
    </div> <!-- /.modal-dialog -->
</div>

<!-- Затемнение фона -->
<div id="worklog-backdrop" style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.6);
    z-index: 1040;
    display: none;
"></div>

<?php
$js = <<<JS
(function () {
    const dropdown = document.querySelector('.js-issue-actions-dropdown');
    const trigger = document.querySelector('.js-issue-actions-trigger');
    const modal = document.getElementById('worklog-modal');
    const backdrop = document.getElementById('worklog-backdrop');
    const closeBtn = document.getElementById('worklog-modal-close');

    if (!modal || !backdrop || !closeBtn) return;

    function closeModal() {
        modal.style.display = 'none';
        backdrop.style.display = 'none';
        if (dropdown) {
            dropdown.style.display = 'none';
            trigger.setAttribute('aria-expanded', 'false');
        }
    }

    function openModal(title, url) {
        document.getElementById('worklog-modal-title').textContent = title;
        document.getElementById('worklog-modal-body').innerHTML = '<div class="text-center">Загрузка...</div>';
        modal.style.display = 'flex';
        backdrop.style.display = 'block';

        fetch(url, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.ok ? response.text() : Promise.reject('Ошибка загрузки'))
        .then(html => {
            document.getElementById('worklog-modal-body').innerHTML = html;

            // Инициализация переключателей "Оставшееся время"
            const radios = document.querySelectorAll('input[name="adjustEstimate"]');
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    document.getElementById('log-work-adjust-estimate-new-value').disabled = this.value !== 'new';
                    document.getElementById('log-work-adjust-estimate-manual-value').disabled = this.value !== 'manual';
                });
            });

            // AJAX-отправка формы (делегированный обработчик)
            const form = document.getElementById('log-work-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(form);
                    const actionUrl = form.getAttribute('action') || window.location.href;

                    fetch(actionUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeModal();
                            // Опционально: обновить страницу или блок журнала работ
                            // location.reload();
                        } else {
                            alert('Ошибка сохранения: ' + (data.message || ''));
                        }
                    })
                    .catch(err => {
                        console.error('Worklog POST error:', err);
                        alert('Не удалось отправить данные');
                    });
                });
            }
        })
        .catch(err => {
            document.getElementById('worklog-modal-body').innerHTML = '<div style="color: #d04437; padding: 10px;">Не удалось загрузить форму</div>';
            console.error('Worklog error:', err);
        });
    }

    // Закрытие по крестику или клику на фон
    closeBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);

    // Обработчик пункта меню
    document.addEventListener('click', function (e) {
        const link = e.target.closest('.issueaction-log-work');
        if (link) {
            e.preventDefault();
            const url = link.getAttribute('data-url');
            const title = link.getAttribute('data-title') || 'Вести журнал работы';
            if (url) openModal(title, url);
            return;
        }

        // Закрытие dropdown при клике вне
        if (!trigger || !dropdown) return;
        const isClickOnTrigger = trigger.contains(e.target) || e.target === trigger;
        const isClickInsideDropdown = dropdown.contains(e.target);
        if (isClickOnTrigger) {
            e.preventDefault();
            const isVisible = dropdown.style.display === 'block';
            dropdown.style.display = isVisible ? 'none' : 'block';
            trigger.setAttribute('aria-expanded', String(!isVisible));
        } else if (!isClickInsideDropdown) {
            dropdown.style.display = 'none';
            trigger.setAttribute('aria-expanded', 'false');
        }
    });
})();
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>