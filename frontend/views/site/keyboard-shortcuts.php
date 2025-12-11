<?php
use yii\helpers\Html;

$this->title = 'Горячие клавиши';
?>

<div class="aui-page-panel">
    <div class="aui-page-panel-inner">
        <div class="aui-page-header">
            <div class="aui-page-header-inner">
                <div class="aui-page-header-main">
                    <h1>Горячие клавиши</h1>
                </div>
            </div>
        </div>

        <div class="dashboard-gadget">
            <div class="mod-content">
                <p style="color: var(--jira-text-secondary); margin-bottom: 16px;">
                    Ускорьте работу с помощью клавиатурных сокращений.
                </p>

                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr>
                            <th style="text-align: left; padding: 8px 0; border-bottom: 2px solid var(--jira-border); color: var(--jira-text-secondary);">Действие</th>
                            <th style="text-align: left; padding: 8 0; border-bottom: 2px solid var(--jira-border); color: var(--jira-text-secondary);">Клавиша</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid var(--jira-border);">
                            <td style="padding: 12px 0;">Открыть поиск задач</td>
                            <td style="padding: 12px 0;"><kbd style="background: #f4f5f7; border: 1px solid var(--jira-border); border-radius: 3px; padding: 2px 6px; font-family: monospace;">/</kbd></td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--jira-border);">
                            <td style="padding: 12px 0;">Создать задачу</td>
                            <td style="padding: 12px 0;"><kbd style="background: #f4f5f7; border: 1px solid var(--jira-border); border-radius: 3px; padding: 2px 6px; font-family: monospace;">c</kbd></td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--jira-border);">
                            <td style="padding: 12px 0;">Перейти к задаче по ключу</td>
                            <td style="padding: 12px 0;"><kbd style="background: #f4f5f7; border: 1px solid var(--jira-border); border-radius: 3px; padding: 2px 6px; font-family: monospace;">g</kbd> + <kbd>t</kbd></td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--jira-border);">
                            <td style="padding: 12px 0;">Открыть рабочий стол</td>
                            <td style="padding: 12px 0;"><kbd style="background: #f4f5f7; border: 1px solid var(--jira-border); border-radius: 3px; padding: 2px 6px; font-family: monospace;">g</kbd> + <kbd>d</kbd></td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--jira-border);">
                            <td style="padding: 12px 0;">Открыть проекты</td>
                            <td style="padding: 12px 0;"><kbd style="background: #f4f5f7; border: 1px solid var(--jira-border); border-radius: 3px; padding: 2px 6px; font-family: monospace;">g</kbd> + <kbd>p</kbd></td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0;">Эта страница</td>
                            <td style="padding: 12px 0;"><kbd style="background: #f4f5f7; border: 1px solid var(--jira-border); border-radius: 3px; padding: 2px 6px; font-family: monospace;">?</kbd></td>
                        </tr>
                    </tbody>
                </table>

                <p style="margin-top: 20px; font-size: 13px; color: var(--jira-text-secondary);">
                    Горячие клавиши работают только при фокусе на странице (не в полях ввода).
                </p>
            </div>
        </div>
    </div>
</div>