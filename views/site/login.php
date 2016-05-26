<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use app\assets\LoginAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

LoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="robots" content="noindex">
    <?= Html::csrfMetaTags() ?>

    <title>Авторизация - <?= Yii::$app->name ?></title>

    <?php $this->head() ?>
</head>

<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="card card-raised card-login">
                <div class="card-title">
                    <h1>SmartHome</h1>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'username')->textInput() ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <?= Html::submitButton('Войти', [
                        'class' => 'btn btn-primary btn-block',
                    ]) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="credentials">
    By <a href="//solomaha.com">Alex Solomaha</a>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
