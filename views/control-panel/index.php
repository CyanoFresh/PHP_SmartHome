<?php

/* @var $this yii\web\View */

use app\assets\ControlPanelAsset;
use app\models\Item;

ControlPanelAsset::register($this);

$this->title = 'Панель Управления';
?>
<div class="control-panel">
    <div id="loader">
        <div class="loader-spinner">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"/>
            </svg>
        </div>

        <div class="loader-text">
            <h1 class="loader-status">Загрузка</h1>

            <div class="loader-status-more">Ожидание загрузки страницы...</div>
        </div>
    </div>

    <div id="content">
        <h1 class="page-header">Панель Управления</h1>

        <div id="no-connection" class="alert alert-danger">
            Отсутствует подключение к Arduino
        </div>

        <div class="row items">
            <?php foreach (Item::findAll(['type' => Item::TYPE_RELAY]) as $item): ?>
                <div class="col-sm-3 col-md-4 item-<?= $item->name ?>">
                    <div class="panel panel-default">
                        <div class="panel-body text-center">
                            <h3><?= $item->title ?></h3>

                            <div class="item-icon">
                                <i class="fa fa-<?= $item->icon ?>"></i>
                            </div>
                        </div>

                        <div class="panel-footer">
                            <a class="btn btn-success btn-block btn-lg btn-toggle" data-item="<?= $item->id ?>">включить</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>