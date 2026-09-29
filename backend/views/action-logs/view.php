<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ActionLog $model */

$this->title = 'Action Log #' . $model->id;

$oldValues = $model->old_values
        ? json_decode($model->old_values, true)
        : [];

$newValues = $model->new_values
        ? json_decode($model->new_values, true)
        : [];

$actionColors = [
        'create' => 'success',
        'update' => 'primary',
        'delete' => 'danger',
        'login' => 'info',
        'logout' => 'secondary',
        'status_change' => 'warning',
];

$badge = $actionColors[$model->action] ?? 'dark';
?>

<div class="action-log-view">

    <div class="mb-4">

        <div class="d-flex justify-content-between align-items-start">

            <div>
                <div class="d-flex align-items-center gap-2" style="gap:10px">

                    <span class="badge bg-<?= $badge ?>">
                        <?= Html::encode(strtoupper($model->action)) ?>
                    </span>

                    <?php if ($model->entity_type): ?>
                        <span class="fw-semibold">
                            <?= Html::encode(
                                    ucwords(str_replace('_', ' ', $model->entity_type))
                            ) ?>
                        </span>
                    <?php endif; ?>

                    <?php if ($model->entity_id): ?>
                        <span class="text-muted">
                            #<?= Html::encode($model->entity_id) ?>
                        </span>
                    <?php endif; ?>

                </div>
            </div>

            <div class="text-end text-muted">
                <div>
                    <?= Yii::$app->formatter->asDatetime(
                            $model->created_at,
                            'php:Y-m-d H:i:s'
                    ) ?>
                </div>
            </div>

        </div>

    </div>


    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">
                    <strong>Changes</strong>
                </div>

                <div class="card-body">

                    <?php if (!empty($oldValues) || !empty($newValues)): ?>

                        <?php
                        $fields = array_unique(
                                array_merge(
                                        array_keys($oldValues),
                                        array_keys($newValues)
                                )
                        );
                        ?>

                        <?php foreach ($fields as $field): ?>

                            <?php
                            $oldValue = $oldValues[$field] ?? null;
                            $newValue = $newValues[$field] ?? null;

                            if (is_array($oldValue)) {
                                $oldValue = json_encode(
                                        $oldValue,
                                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                                );
                            }

                            if (is_array($newValue)) {
                                $newValue = json_encode(
                                        $newValue,
                                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                                );
                            }
                            ?>

                            <div class="change-block">

                                <div class="change-title">
                                    <?= Html::encode(
                                            ucwords(str_replace('_', ' ', $field))
                                    ) ?>
                                </div>

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <div class="value-box value-old">

                                            <div class="value-label">
                                                Old value
                                            </div>

                                            <div class="value-content">
                                                <?= nl2br(Html::encode(
                                                        $oldValue !== null && $oldValue !== ''
                                                                ? (string)$oldValue
                                                                : '—'
                                                )) ?>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="value-box value-new">

                                            <div class="value-label">
                                                New value
                                            </div>

                                            <div class="value-content">
                                                <?= nl2br(Html::encode(
                                                        $newValue !== null && $newValue !== ''
                                                                ? (string)$newValue
                                                                : '—'
                                                )) ?>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="text-muted">
                            No changed values stored for this action.
                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <div class="col-lg-4">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">
                    <strong>Action Details</strong>
                </div>

                <div class="card-body">

                    <div class="detail-row">
                        <span>User</span>
                        <strong>
                            <?php $userData = $model->user->userAdditionalData ?? null; ?>
                            <?= Html::encode(
                                    ($userData->first_name ?? '-') . ' ' . ($userData->last_name ?? '-')
                            ) ?>
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>User type</span>
                        <strong>
                            <?php
                            $roles = Yii::$app->authManager->getRolesByUser($model->user_id);

                            if (!empty($roles)) {
                                echo implode(',', array_keys($roles));
                            }
                            ?>


                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>Action</span>
                        <strong>
                            <?= Html::encode($model->action) ?>
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>Entity</span>
                        <strong>
                            <?= Html::encode($model->entity_type ?: '—') ?>
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>Entity ID</span>
                        <strong>
                            <?= Html::encode($model->entity_id ?: '—') ?>
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>IP Address</span>
                        <strong>
                            <?= Html::encode($model->ip_address ?: '—') ?>
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>Method</span>
                        <strong>
                            <?= Html::encode($model->request_method ?: '—') ?>
                        </strong>
                    </div>

                </div>

            </div>


            <?php if ($model->description): ?>

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">
                        <strong>Description</strong>
                    </div>

                    <div class="card-body">
                        <?= nl2br(Html::encode($model->description)) ?>
                    </div>

                </div>

            <?php endif; ?>


            <?php if ($model->request_url): ?>

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">
                        <strong>Request URL</strong>
                    </div>

                    <div class="card-body">
                        <div class="small text-break">
                            <?= Html::encode($model->request_url) ?>
                        </div>
                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<style>

    .action-log-view {
        max-width: 1400px;
        margin: 0 auto;
    }

    .change-block {
        padding: 18px 0;
        border-bottom: 1px solid #eee;
    }

    .change-block:first-child {
        padding-top: 0;
    }

    .change-block:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .change-title {
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 10px;
        color: #343a40;
    }

    .value-box {
        height: 100%;
        border-radius: 8px;
        padding: 14px;
        border: 1px solid #e9ecef;
    }

    .value-old {
        background: #fff7f7;
        border-color: #ffd6d6;
    }

    .value-new {
        background: #f6fff8;
        border-color: #ccebd5;
    }

    .value-label {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 6px;
    }

    .value-content {
        white-space: normal;
        word-break: break-word;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-row:last-child {
        border-bottom: 0;
    }

    .detail-row span {
        color: #6c757d;
    }

</style>