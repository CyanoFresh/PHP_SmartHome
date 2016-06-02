<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Компоненты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">

    <h1 class="page-header">
        <?= $this->title ?>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summaryOptions' => ['class' => 'alert alert-info'],
        'layout' => '{summary}<div class="table-responsive">{items}</div>{pager}',
        'columns' => [
            'id',
            'type',
            'name',
            'pin',
            'title',
            'icon',

            ['class' => 'app\components\ActionButtonGroupColumn'],
        ],
    ]); ?>
</div>
