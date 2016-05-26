<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1 class="page-header">
        <?= $this->title ?>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    /** @var $model \app\models\User */
                    return $model->getStatusLabel();
                },
            ],
            'created_at:datetime',
            'updated_at:datetime',

            ['class' => 'app\components\ActionButtonGroupColumn'],
        ],
    ]); ?>
</div>
