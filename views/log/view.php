<?php

/* @var $this yii\web\View */
/* @var $model app\models\Log */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-view">

    <h1 class="page-header">
        <?= Html::encode($this->title) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
        'confirm' => 'Are you sure you want to delete this item?',
        'method' => 'post',
        ],
        ]) ?>
    </h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'type',
            'user_id',
            'item_id',
            'date',
            'value',
        ],
    ]) ?>

</div>
