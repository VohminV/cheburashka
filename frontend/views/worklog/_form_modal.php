<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \common\models\IssueWorklog */
/* @var $issueId int */
?>
<div class="log-work-dialog jira-dialog">
    <form id="log-work-form" action="<?= Url::to(['worklog/create', 'issue_id' => $issueId]) ?>">
        <?= Html::hiddenInput('issue_id', $issueId) ?>
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

        <div class="form-body">

            <!-- Затраченное время -->
            <div class="field-group">
                <label for="log-work-time-logged">
                    Затраченное время
                    <span class="aui-icon icon-required" title="требуется">требуется</span>
                </label>
                <input type="text"
                       id="log-work-time-logged"
                       name="timeLogged"
                       class="text short-field"
                       placeholder="Например, 3w 4d 12h">
                <span class="aui-form example">(Например, 3w 4d 12h)</span>
                <div class="description">Оцените, сколько времени вы потратили на работу.</div>
            </div>

            <!-- Дата начала -->
            <div class="field-group">
                <label for="log-work-date-logged">
                    Дата начала
                    <span class="aui-icon icon-required" title="требуется">требуется</span>
                </label>
                <input type="text"
                       id="log-work-date-logged"
                       name="startDate"
                       class="text medium-field"
                       value="<?= date('d/m/Y H:i') ?>">
                <a href="#" id="log-work-date-logged-icon" title="Выберите дату" style="margin-left: 6px;">
                    <span class="aui-icon icon-default aui-icon-small aui-iconfont-calendar"></span>
                </a>
            </div>

            <!-- Оставшееся время -->
            <fieldset class="group">
                <legend><span>Оставшееся время</span></legend>

                <div class="radio">
                    <input type="radio" name="adjustEstimate" value="auto" id="log-work-adjust-estimate-auto" checked>
                    <label for="log-work-adjust-estimate-auto">Уменьшить автоматически</label>
                    <div class="description">оценка времени будет уменьшена до тех пор, пока не станет нулевой.</div>
                </div>

                <div class="radio">
                    <input type="radio" name="adjustEstimate" value="leave" id="log-work-adjust-estimate-leave">
                    <label for="log-work-adjust-estimate-leave">Оставить неизменной</label>
                </div>

                <div class="radio">
                    <input type="radio" name="adjustEstimate" value="new" id="log-work-adjust-estimate-new">
                    <label for="log-work-adjust-estimate-new">Установить в</label>
                    <input type="text"
                           name="newEstimate"
                           id="log-work-adjust-estimate-new-value"
                           class="text short-field"
                           disabled>
                    <span class="aui-form example">(Например, 3w 4d 12h)</span>
                </div>

                <div class="radio">
                    <input type="radio" name="adjustEstimate" value="manual" id="log-work-adjust-estimate-manual">
                    <label for="log-work-adjust-estimate-manual">Уменьшить на</label>
                    <input type="text"
                           name="adjustmentAmount"
                           id="log-work-adjust-estimate-manual-value"
                           class="text short-field"
                           disabled>
                    <span class="aui-form example">(Например, 3w 4d 12h)</span>
                </div>
            </fieldset>

            <!-- Описание работы -->
            <div class="field-group aui-field-wikiedit comment-input">
                <label for="comment">Описание работы</label>
                <div class="jira-wikifield" field-id="comment" renderer-type="atlassian-wiki-renderer" issue-key="CHEB-<?= $issueId ?>">
                    <div class="wiki-edit-content">
                        <textarea
                            id="comment"
                            name="comment"
                            class="textarea long-field worklog-textarea"
                            data-projectkey="CHEB"
                            data-issuekey="CHEB-<?= $issueId ?>"
                            placeholder="Добавьте описание работы..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Кнопки -->
            <div class="buttons-container form-footer" style="margin-top: 24px; display: flex; gap: 8px;">
                <button type="submit" class="aui-button aui-button-primary">Записать</button>
                <button type="button" class="aui-button" onclick="closeWorklogModal()">Отмена</button>
            </div>

        </div>
    </form>
</div>

<?php
$js = <<<JS
// Переключение полей "Оставшееся время"
document.querySelectorAll('input[name="adjustEstimate"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('log-work-adjust-estimate-new-value').disabled = this.value !== 'new';
        document.getElementById('log-work-adjust-estimate-manual-value').disabled = this.value !== 'manual';
    });
});
JS;
$this->registerJs($js);
?>