<?php
/* @var $this yii\web\View */
/* @var $model Log */

use app\helpers\StateHelper;
use app\models\Log;
use YoHang88\LetterAvatar\LetterAvatar;

$avatar = new LetterAvatar('a d', 'square');
?>

<div class="history-item">
    <img src="<?= $avatar ?>" class="history-item-image">

    <div class="history-item-content">
            <strong><?= $model->user->username ?></strong>

        <?php if ($model->type === Log::TYPE_STATE): ?>

            <?php if (StateHelper::intToBool($model->value)): ?>
                включил
            <?php else: ?>
                выключил
            <?php endif ?>

            устройство

            <strong><?= $model->item->title ?></strong>

        <?php elseif ($model->type === Log::TYPE_LOGIN): ?>
            вошел в аккаунт
        <?php elseif ($model->type === Log::TYPE_LOGOUT): ?>
            вышел из аккаунта
        <?php endif; ?>
        <span class="history-item-date text-muted"><?= Yii::$app->formatter->asDatetime($model->date) ?></span>
    </div>
</div>