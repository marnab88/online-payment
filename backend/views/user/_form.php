<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'UserName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Password')->passwordInput(['maxlength' => true]) ?>
	

    <?= $form->field($model, 'Role')->textInput() ?>

    <?= $form->field($model, 'LoginId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'OnDate')->textInput() ?>

    <?= $form->field($model, 'IsDelete')->textInput() ?>
	
	<?= $form->field($model, 'type')->dropDownList($type,  ['prompt'=>'Select File Type']); ?>
	
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
