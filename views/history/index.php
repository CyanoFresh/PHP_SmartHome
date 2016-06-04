<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'История';
?>

<div class="history-index">
    <h1 class="page-header">
        <?= $this->title ?>
        <?= Html::a('Очистить', ['flush'], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите все удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </h1>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        'layout' => '{summary}<div class="history-items">{items}</div>{pager}',
        'itemOptions' => [
            'tag' => false,
        ],
        'summaryOptions' => [
            'class' => 'alert alert-info'
        ],
    ]) ?>
</div>
