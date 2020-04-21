<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Subadmin';

?>
<div class="site-login">
    <div class="row">
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <?php $form = ActiveForm::begin(['options' => ['class' => 'forms-sample','enctype' => 'multipart/form-data']]) ?>
                    <?= $form->field($model, 'BranchName')->textInput(['maxlength' => true,'required'=>true]) ?>

                    <div class="form-group">     
                        <?= $form->field($model, 'Cluster')->textInput(['maxlength' => true,'required'=>true]) ?>
                    </div>
                    <div class="form-group"> 
                        <?= $form->field($model, 'State')->textInput(['maxlength' => true,'required'=>true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'ClientId')->textInput(['maxlength' => true,'required'=>true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'LoanAccountNo')->textInput(['pattern'=>'^[a-zA-Z0-9_-]*$','maxlength' => true,'required'=>true]) ?>
                    </div>
                    <div class="form-group">
                        
                        <?= $form->field($model, 'ClientName')->textInput(['maxlength' => true,'required'=>true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'MobileNo')->textInput(['pattern'=>"[6789][0-9]{9}",'maxlength' => 10,'minlength'=>10,'title'=>'Mobile should be contain only number','required'=>true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'EmiSrNo')->textInput(['maxlength' => true,'required'=>true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'LastMonthDue')->textInput(['pattern'=>'^[0-9]+$','maxlength' => true,'title'=>'LastMonthDue should be contain only number','required'=>true]) ?>
                    </div> 
                    <div class="form-group">
                        <?= $form->field($model, 'CurrentMonthDue')->textInput(['pattern'=>'^[0-9]+$','maxlength' => true,'title'=>'CurrentMonthDue should be contain only number','required'=>true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'LatePenalty')->textInput(['pattern'=>'^[0-9]+$','maxlength' => true,'title'=>'LatePenalty should be contain only number','required'=>true]) ?>
                    </div>
                   
                   <?= Html::submitButton('Submit',['class'=>'btn btn-success']) ?>
                   <?php if ($type == 34) {?>
                     <button class="btn btn-danger" type="button"><a href="<?= Url::to(['site/msme','id' => $details,'type' => '34','mon'=>$mon]);  ?>" data-method="post"  style="float:left;color:#fff;">Back</a></button>
                  <? } ?>
                   <?php if ($type == 35) {?>
                     <button class="btn btn-danger" type="button"><a href="<?= Url::to(['site/msme','id' => $details,'type' => '35','mon'=>$mon]);  ?>" data-method="post"  style="float:left;color:#fff;">Back</a></button>
                  <? } ?>
                   <?php if ($type == 36) {?>
                     <button class="btn btn-danger" type="button"><a href="<?= Url::to(['site/msme','id' => $details,'type' => '36','mon'=>$mon]);  ?>" data-method="post"  style="float:left;color:#fff;">Back</a></button>
                  <? } ?>
                    <?php if ($type == 37) {?>
                     <button class="btn btn-danger" type="button"><a href="<?= Url::to(['site/msme','id' => $details,'type' => '37','mon'=>$mon]);  ?>" data-method="post"  style="float:left;color:#fff;">Back</a></button>
                  <? } ?>
               

                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>