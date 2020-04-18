<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// $this->title = 'Users';

?>
<div class="user-index">

    

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'UserId',
            'UserName',
            'Password',
            'Role',
            'LoginId',
            //'OnDate',
            //'UpdatedDate',
            //'IsDelete',
            //'Status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
