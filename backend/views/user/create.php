<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Create User';
// $this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    

    <?= $this->render('subadmin', [
        'model' => $model,
        'details' => $details,
		'type'=>$type,
    ]) ?>

</div>
