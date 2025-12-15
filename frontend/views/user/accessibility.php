<?php
use yii\helpers\Html;

$this->title = 'Специальные возможности';
?>

<div class="aui-page-panel">
    <div class="aui-page-panel-inner">
        <div class="aui-page-header">
            <div class="aui-page-header-inner">
                <div class="aui-page-header-main">
                    <h1>Специальные возможности</h1>
                </div>
            </div>
        </div>

        <div class="dashboard-gadget">
            <div class="mod-content" style="line-height: 1.6; color: var(--jira-text);">
                <p>
                    Чебурашка поддерживает работу с вспомогательными технологиями:
                </p>

                <ul style="margin: 16px 0; padding-left: 24px;">
                    <li>Полная навигация с клавиатуры (включая горячие клавиши)</li>
                    <li>Поддержка экранных дикторов (семантическая разметка, ARIA-атрибуты)</li>
                    <li>Контрастная цветовая схема (можно изменить в настройках ОС)</li>
                    <li>Масштабирование интерфейса до 200% без потери функциональности</li>
                </ul>

                <h3 style="font-size: 16px; font-weight: 600; margin: 24px 0 12px; color: var(--jira-text);">
                    Горячие клавиши
                </h3>
                <p>
                    Ускорьте работу без мыши:
                    <br>• <kbd style="background: #f4f5f7; border: 1px solid var(--jira-border); border-radius: 3px; padding: 2px 6px; font-family: monospace;">c</kbd> — создать задачу
                    <br>• <kbd>/</kbd> — фокус в поиск
                    <br>• <kbd>g</kbd> + <kbd>d</kbd> — рабочий стол
                    <br>• <kbd>?</kbd> — справка по горячим клавишам
                </p>

                <p style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--jira-border); font-size: 13px; color: var(--jira-text-secondary);">
                    Система соответствует рекомендациям WCAG 2.1 (уровень A).
                </p>
            </div>
        </div>
    </div>
</div>