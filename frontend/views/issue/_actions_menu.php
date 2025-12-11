<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \app\models\Issue $model */
?>

<!-- Секция: Работа -->
<aui-section resolved="" class="aui-dropdown2-section">
    <span aria-hidden="true" class="aui-dropdown2-heading"></span>
    <div class="aui-dropdown2-item-group" role="group">
        <aui-item-link href="#" id="log-work" class="issueaction-log-work" title="Сделать запись о работе по этому запросу" resolved="">
            <a role="menuitem" tabindex="-1" href="#"
                onclick="showModalForm('<?= Url::to(['worklog/create', 'issue_id' => $model->id]) ?>', 'Вести журнал работы'); return false;">
                <span class="trigger-label">Вести журнал работы</span>
            </a>
        </aui-item-link>
    </div>
</aui-section>

<!-- Секция: Файлы -->
<aui-section resolved="" class="aui-dropdown2-section">
    <span aria-hidden="true" class="aui-dropdown2-heading"></span>
    <div class="aui-dropdown2-item-group" role="group">
        <aui-item-link href="#" id="attach-file" class="unified-attach-file" title="Приложить один или несколько файлов к этой проблеме" resolved="">
            <a role="menuitem" tabindex="-1" href="#"
                onclick="showModalForm('<?= Url::to(['attachment/upload', 'issue_id' => $model->id]) ?>', 'Прикрепить файлы'); return false;">
                <span class="trigger-label">Прикрепить файлы</span>
            </a>
        </aui-item-link>
        <aui-item-link href="#" id="attach-screenshot-html5" class="issueaction-attach-screenshot-html5" resolved="">
            <a role="menuitem" tabindex="-1" href="#"
                onclick="alert('Скриншоты — позже! Пока используй файлы.'); return false;">
                <span class="trigger-label">Приложить скриншоты</span>
            </a>
        </aui-item-link>
    </div>
</aui-section>

<!-- Секция: Наблюдение -->
<aui-section resolved="" class="aui-dropdown2-section">
    <span aria-hidden="true" class="aui-dropdown2-heading"></span>
    <div class="aui-dropdown2-item-group" role="group">
        <aui-item-link href="<?= Url::to(['watch/toggle', 'id' => $model->id]) ?>" id="toggle-watch-issue" class="issueaction-watch-issue" title="Начать наблюдение за этой задачей" resolved="">
            <a role="menuitem" tabindex="-1" href="<?= Url::to(['watch/toggle', 'id' => $model->id]) ?>">
                <span class="trigger-label">Наблюдать за задачей</span>
            </a>
        </aui-item-link>
        <aui-item-link href="<?= Url::to(['watch/manage', 'id' => $model->id]) ?>" id="manage-watchers" class="issueaction-manage-watchers" title="Редактировать наблюдателей этого запроса" resolved="">
            <a role="menuitem" tabindex="-1" href="<?= Url::to(['watch/manage', 'id' => $model->id]) ?>">
                <span class="trigger-label">Наблюдатели</span>
            </a>
        </aui-item-link>
    </div>
</aui-section>

<!-- Секция: Подзадачи -->
<aui-section resolved="" class="aui-dropdown2-section">
    <span aria-hidden="true" class="aui-dropdown2-heading"></span>
    <div class="aui-dropdown2-item-group" role="group">
        <aui-item-link href="#" id="create-subtask" class="issueaction-create-subtask" title="Создать подзадачу для этого запроса" resolved="">
            <a role="menuitem" tabindex="-1" href="#"
                onclick="showModalForm('<?= Url::to(['issue/create-subtask', 'parent_id' => $model->id]) ?>', 'Создать подзадачу'); return false;">
                <span class="trigger-label">Создать подзадачу</span>
            </a>
        </aui-item-link>
        <!-- Преобразовать в подзадачу — пока опционально -->
        <!--
        <aui-item-link href="#" id="issue-to-subtask" class="issueaction-issue-to-subtask" title="Конвертировать этот запрос в подзадачу" resolved="">
            <a role="menuitem" tabindex="-1" href="#"><span class="trigger-label">Преобразовать в подзадачу</span></a>
        </aui-item-link>
        -->
    </div>
</aui-section>

<!-- Секция: Прочие действия -->
<aui-section resolved="" class="aui-dropdown2-section">
    <span aria-hidden="true" class="aui-dropdown2-heading"></span>
    <div class="aui-dropdown2-item-group" role="group">
        <aui-item-link href="#" id="create-linked-issue" class="issueaction-create-linked-issue" resolved="">
            <a role="menuitem" tabindex="-1" href="#"
                onclick="showModalForm('<?= Url::to(['link/create', 'issue_id' => $model->id]) ?>', 'Создать связанную задачу'); return false;">
                <span class="trigger-label">Создать связанную задачу</span>
            </a>
        </aui-item-link>
        <aui-item-link href="<?= Url::to(['issue/move', 'id' => $model->id]) ?>" id="move-issue" class="issueaction-move-issue" title="Переместить этот запрос в другой проект или тип запроса" resolved="">
            <a role="menuitem" tabindex="-1" href="<?= Url::to(['issue/move', 'id' => $model->id]) ?>">
                <span class="trigger-label">Переместить</span>
            </a>
        </aui-item-link>
        <aui-item-link href="<?= Url::to(['link/create', 'issue_id' => $model->id]) ?>" id="link-issue" class="issueaction-link-issue" title="Сделать ссылку этой задачи на другую задачу или пункт" resolved="">
            <a role="menuitem" tabindex="-1" href="<?= Url::to(['link/create', 'issue_id' => $model->id]) ?>">
                <span class="trigger-label">Связать</span>
            </a>
        </aui-item-link>
        <aui-item-link href="<?= Url::to(['issue/clone', 'id' => $model->id]) ?>" id="clone-issue" class="issueaction-clone-issue" title="Клонировать этот запрос" resolved="">
            <a role="menuitem" tabindex="-1" href="<?= Url::to(['issue/clone', 'id' => $model->id]) ?>">
                <span class="trigger-label">Клонировать</span>
            </a>
        </aui-item-link>
        <aui-item-link href="<?= Url::to(['label/edit', 'id' => $model->id]) ?>" id="edit-labels" class="issueaction-edit-labels" title="Редактировать эту метку запроса ( Нажмите 'l' )" resolved="">
            <a role="menuitem" tabindex="-1" href="<?= Url::to(['label/edit', 'id' => $model->id]) ?>">
                <span class="trigger-label">Метки</span>
            </a>
        </aui-item-link>
    </div>
</aui-section>