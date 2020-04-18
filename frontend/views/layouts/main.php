<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
<!--  <div style="width:100%; height:60px; padding:10px; float:left; margin-bottom:50px; background:rgba(0,0,0, 0.6) url(../images/border.jpg) no-repeat; background-repeat: repeat-x; background-position:bottom;">
	<h1>Annapurna Finance
	<span style="float:right;margin-top:-5px;">
	<a class="nav-link" style="color:#fff; font-size:14px; " href="<?= Url::to(['site/logout']) ?>" data-method="post" data-confirm="you are succesfully log out">
				<i class="fa fa-sign-out"></i> Log Out</a></span>
	</h1>
 </div> -->
<a class="logout" style="position: absolute; right: 0; top: 0;position: absolute;
    right: 4px;
    top: 3px;
    background: #FF9800;
    color: #fff;
    padding: 10px;
    border-radius: 6px;" href="<?= Url::to(['site/logout']) ?>"> <i class="fa fa-sign-out"></i> Log Out</a> </a>
    <div class="container">
	
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
