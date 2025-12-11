<?php
/**
 * Вложения к задаче (Jira-style)
 * @var yii\web\View $this
 * @var common\models\Issue $model
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div id="attachmentmodule" class="issue-attachments" data-issue-id="<?= $model->id ?>">
    <!-- Заголовок -->
    <div id="attachmentmodule_heading" class="mod-header">
        <div class="toggle-title-group">
            <button class="aui-button toggle-title"
                    aria-label="Вложенные файлы"
                    aria-controls="attachmentmodule_content"
                    aria-expanded="true"
                    data-toggle-target="attachmentmodule_content">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14">
                    <g fill="none" fill-rule="evenodd">
                        <path d="M3.29175 4.793c-.389.392-.389 1.027 0 1.419l2.939 2.965c.218.215.5.322.779.322s.556-.107.769-.322l2.93-2.955c.388-.392.388-1.027 0-1.419-.389-.392-1.018-.392-1.406 0l-2.298 2.317-2.307-2.327c-.194-.195-.449-.293-.703-.293-.255 0-.51.098-.703.293z" fill="#344563"></path>
                    </g>
                </svg>
            </button>
            <h4 class="toggle-title">Вложенные файлы (<?= $model->getAttachments()->count() ?>)</h4>
        </div>

        <ul class="ops">
            <li class="drop">
				<div class="aui-dd-parent">
					<button type="button" class="aui-button aui-button-compact aui-button-subtle js-default-dropdown"
							aria-haspopup="true" aria-expanded="false">
						<span class="aui-icon aui-icon-small aui-iconfont-more">…</span>
					</button>
					<div class="aui-dropdown-content">
						<ul class="aui-list-section aui-first">
							<li><?= Html::a('<span>Сортировать по названию</span>', Url::to(['view', 'id' => $model->id, 'attachmentSortBy' => 'fileName']) . '#attachmentmodule', ['class' => 'aui-list-item-link']) ?></li>
							<li><?= Html::a('<span>Сортировать по дате</span>', Url::to(['view', 'id' => $model->id, 'attachmentSortBy' => 'dateTime']) . '#attachmentmodule', ['class' => 'aui-list-item-link']) ?></li>
						</ul>
						<ul class="aui-list-section">
							<li><?= Html::a('<span>По возрастанию</span>', Url::to(['view', 'id' => $model->id, 'attachmentOrder' => 'asc']) . '#attachmentmodule', ['class' => 'aui-list-item-link']) ?></li>
							<li><?= Html::a('<span>По убыванию</span>', Url::to(['view', 'id' => $model->id, 'attachmentOrder' => 'desc']) . '#attachmentmodule', ['class' => 'aui-list-item-link']) ?></li>
						</ul>
						<ul class="aui-list-section">
							<li><?= Html::a('<span>Эскизы</span>', Url::to(['view', 'id' => $model->id, 'attachmentViewMode' => 'gallery']) . '#attachmentmodule', ['class' => 'aui-list-item-link']) ?></li>
							<li><?= Html::a('<span>Список</span>', Url::to(['view', 'id' => $model->id, 'attachmentViewMode' => 'list']) . '#attachmentmodule', ['class' => 'aui-list-item-link']) ?></li>
						</ul>
						<ul class="aui-list-section aui-last">
							<li><?= Html::a('<span>Скачать все</span>', Url::to(['download-all', 'id' => $model->id]), ['class' => 'aui-list-item-link']) ?></li>
							<li><?= Html::a('<span>Управление вложениями</span>', Url::to(['manage-attachments', 'id' => $model->id]), ['class' => 'aui-list-item-link']) ?></li>
						</ul>
					</div>
				</div>
            </li>
        </ul>
    </div>

    <!-- Контент -->
    <div id="attachmentmodule_content" class="mod-content issue-drop-zone">
        <div duitype="dndattachment/dropzones/AttachmentsDropZone"
             class="issue-drop-zone -dui-type-parsed"
             data-upload-limit="10485760"
             data-upload-size="10.00 MB">
            <div class="issue-drop-zone__target"></div>
            <span class="issue-drop-zone__text">
                <span class="issue-drop-zone__drop-icon"></span>
                Перенесите файлы, чтобы прикрепить, или
                <button type="button" class="issue-drop-zone__button">обзор.</button>
                <?= Html::fileInput('files', null, [
                    'class' => 'issue-drop-zone__file ignore-inline-attach',
                    'multiple' => true,
                    'style' => 'display: none;',
                ]) ?>
            </span>
        </div>

        <?php if ($attachments = $model->getAttachments()->all()): ?>
            <ol id="attachment_thumbnails" class="item-attachments" data-sort-key="fileName" data-sort-order="asc">
                <?php foreach ($attachments as $attachment): ?>
                    <li class="attachment-content js-file-attachment" data-id="<?= $attachment->id ?>">
                        <?php if (strpos($attachment->mime_type, 'image/') === 0): ?>
                            <div class="attachment-thumb">
                                <a href="<?= Url::to(['/attachment/download', 'id' => $attachment->id]) ?>"
                                   title="<?= Html::encode($attachment->filename) ?> - <?= Yii::$app->formatter->asDatetime($attachment->created_at) ?>">
                                    <img src="<?= Url::to(['/attachment/thumbnail', 'id' => $attachment->id]) ?>" alt="<?= Html::encode($attachment->filename) ?>">
                                </a>
                            </div>
                        <?php endif; ?>
                        <dl>
                            <dt>
                                <a href="<?= Url::to(['/attachment/download', 'id' => $attachment->id]) ?>"
                                   class="attachment-title"
                                   title="<?= Html::encode($attachment->filename) ?> - <?= Yii::$app->formatter->asDatetime($attachment->created_at) ?>">
                                    <?= Html::encode($attachment->filename) ?>
                                </a>
                                <a href="#" class="attachment-delete"
                                   data-id="<?= $attachment->id ?>"
                                   title="Удалить вложение"
                                   style="margin-left: 8px; color: #d00; text-decoration: none; font-size: 16px; opacity: 0;">✕</a>
                            </dt>
                            <dd class="attachment-size"><?= Yii::$app->formatter->asShortSize($attachment->file_size) ?></dd>
                            <dd class="attachment-date"><?= Yii::$app->formatter->asDatetime($attachment->created_at) ?></dd>
                        </dl>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php else: ?>
            <p class="aui-message">Нет вложений</p>
        <?php endif; ?>
    </div>
</div>

<?php
// JavaScript (NOWDOC — полностью безопасен)
$js = <<<'JS'
(function () {
    const getCsrfToken = () => {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    };

    // Сворачивание
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.toggle-title[data-toggle-target]');
        if (btn) {
            const targetId = btn.getAttribute('data-toggle-target');
            const target = document.getElementById(targetId);
            const isExpanded = btn.getAttribute('aria-expanded') === 'true';
            if (target) {
                target.style.display = isExpanded ? 'none' : '';
                btn.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
            }
        }
    });

    // Кнопка "обзор."
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('issue-drop-zone__button')) {
            const input = e.target.closest('.issue-drop-zone').querySelector('.issue-drop-zone__file');
            if (input) input.click();
        }
    });

    // Загрузка файлов
    document.addEventListener('change', function (e) {
        const input = e.target;
        if (!input.classList.contains('issue-drop-zone__file')) return;
        const files = input.files;
        if (!files.length) return;

        const container = input.closest('.issue-attachments');
        const issueId = container ? container.dataset.issueId : null;
        if (!issueId) {
            alert('Не найден ID задачи');
            return;
        }

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }
        formData.append('issue_id', issueId);

        fetch('/index.php?r=issue%2Fupload-attachment', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-Token': getCsrfToken() }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) location.reload();
            else alert('Ошибка: ' + (data.message || 'неизвестная'));
        })
        .catch(err => {
            console.error('Upload error:', err);
            alert('Не удалось загрузить файл');
        })
        .finally(() => {
            input.value = '';
        });
    });

    // Дропдаун "…"
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-default-dropdown');
        if (btn) {
            e.stopPropagation();
            const dd = btn.closest('.aui-dd-parent').querySelector('.aui-dropdown-content');
            if (dd) {
                const isVisible = dd.style.display === 'block';
                dd.style.display = isVisible ? 'none' : 'block';
                btn.setAttribute('aria-expanded', !isVisible);
            }
        }
    });
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.aui-dd-parent')) {
            document.querySelectorAll('.aui-dropdown-content').forEach(el => {
                el.style.display = 'none';
                const btn = el.closest('.aui-dd-parent')?.querySelector('.js-default-dropdown');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            });
        }
    });

    // Удаление вложения — ИСПРАВЛЕНО: FormData вместо JSON
    document.addEventListener('click', function (e) {
        const delBtn = e.target.closest('.attachment-delete');
        if (delBtn) {
            e.preventDefault();
            if (!confirm('Удалить вложение?')) return;

            const id = delBtn.dataset.id;
            const formData = new FormData();
            formData.append('id', id);

            fetch('/index.php?r=issue%2Fdelete-attachment', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-Token': getCsrfToken() }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const li = delBtn.closest('.attachment-content');
                    if (li) li.remove();

                    // Обновить счётчик
                    const counter = document.querySelector('#attachmentmodule_heading .toggle-title');
                    if (counter) {
                        const text = counter.innerText;
                        const match = text.match(/\((\d+)\)/);
                        if (match) {
                            const newCount = parseInt(match[1]) - 1;
                            counter.innerText = text.replace(/\(\d+\)/, '(' + newCount + ')');
                        }
                    }

                    // Если вложений больше нет
                    const list = document.getElementById('attachment_thumbnails');
                    if (list && list.children.length === 0) {
                        list.insertAdjacentHTML('afterend', '<p class="aui-message">Нет вложений</p>');
                    }
                } else {
                    alert('Ошибка удаления');
                }
            })
            .catch(err => {
                console.error('Delete error:', err);
                alert('Не удалось удалить файл');
            });
        }
    });

    // Показ крестика при наведении
    document.querySelectorAll('.attachment-content').forEach(el => {
        el.addEventListener('mouseenter', () => {
            const del = el.querySelector('.attachment-delete');
            if (del) del.style.opacity = '1';
        });
        el.addEventListener('mouseleave', () => {
            const del = el.querySelector('.attachment-delete');
            if (del) del.style.opacity = '0';
        });
    });
})();
JS;

echo "<script>\n" . $js . "\n</script>";
?>