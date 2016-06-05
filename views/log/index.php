<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\helpers\StateHelper;
use app\models\Log;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Логи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-index">

    <h1 class="page-header"><?= $this->title ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summaryOptions' => ['class' => 'alert alert-info'],
        'layout' => '{summary}<div class="table-responsive">{items}</div>{pager}',
        'columns' => [
            'id',
            [
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var Log $model */
                    return Html::a($model->user->username, ['user/view', 'id' => $model->user->id], [
                        'target' => '_blank',
                    ]);
                },
            ],
            [
                'attribute' => 'type',
                'filter' => Log::getTypesArray(),
                'value' => function ($model) {
                    /** @var Log $model */
                    return $model->getTypeLabel();
                },
            ],
            [
                'attribute' => 'value',
                'filter' => StateHelper::getIntStatesArray(),
                'value' => function ($model) {
                    /** @var Log $model */
                    return $model->value ? StateHelper::getIntStateLabel($model->value) : null;
                },
            ],
            [
                'attribute' => 'item_id',
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var Log $model */
                    return $model->item ? Html::a($model->item->title, ['item/view', 'id' => $model->item->id], [
                        'target' => '_blank',
                    ]) : null;
                },
            ],
            'date:datetime',
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
