<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Update MSME Details';

?>
<style type="text/css">
.stretch-card label{
  display: block;
  font-weight: 100;
}
.hide-calendar .ui-datepicker-calendar{
  display: none;
}
</style>
<div class="site-login">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <?php $form = ActiveForm::begin(['options' => ['class' => 'forms-sample','enctype' => 'multipart/form-data']]) ?>
                    <div class="col-sm-6"> 
                    <?= $form->field($model, 'BranchName')->textInput(['maxlength' => true,'required'=>true]) ?>
                    </div>
                    <div class="col-sm-6">     
                        <?= $form->field($model, 'Cluster')->textInput(['maxlength' => true,'required'=>true]) ?>
                    </div>
                    <div class="col-sm-6"> 
                        <?= $form->field($model, 'State')->textInput(['maxlength' => true,'required'=>true]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'ClientId')->textInput(['maxlength' => true,'required'=>true]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'LoanAccountNo')->textInput(['pattern'=>'^[a-zA-Z0-9_-]*$','maxlength' => true,'required'=>true]) ?>
                    </div>
                    <div class="col-sm-6">
                        
                        <?= $form->field($model, 'ClientName')->textInput(['maxlength' => true,'required'=>true]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'MobileNo')->textInput(['pattern'=>"[6789][0-9]{9}",'maxlength' => 10,'minlength'=>10,'title'=>'Mobile should be contain only number and 10 digit','required'=>true]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'EmiSrNo')->textInput(['maxlength' => true,'required'=>true]) ?>
                    </div>
                    <div class="col-sm-6">
                        <label>Demand Date</label>
                        <input type="text" name="DemandDate" id="datepicker" placeholder="dd-mm-yy" class="form-control" value="<?= date('d-m-Y',strtotime($model->DemandDate)) ?>" autocomplete="off">
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'LastMonthDue')->textInput(['pattern'=>'^[0-9]+$','maxlength' => true,'title'=>'LastMonthDue should be contain only number','required'=>true]) ?>
                    </div> 
                    <div class="col-sm-6">
                        <?= $form->field($model, 'CurrentMonthDue')->textInput(['pattern'=>'^[0-9]+$','maxlength' => true,'title'=>'CurrentMonthDue should be contain only number','required'=>true]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'LatePenalty')->textInput(['pattern'=>'^[0-9]+$','maxlength' => true,'title'=>'LatePenalty should be contain only number','required'=>true]) ?>
                    </div>
                    <div class="col-sm-6">
                        <label>Next Installment Date</label>
                        <input type="text" name="NextInstallmentDate" id="datepicker1" placeholder="dd-mm-yy" class="form-control" value="<?= date('d-m-Y',strtotime($model->NextInstallmentDate)) ?>" autocomplete="off">
                    </div>
                    <div class="col-sm-6">
                        <?php
                        $string = "`";
                        $string1 = "'";
                        if(strpos($model->UploadMonth, $string) !== false){
                            $yeararray = explode("`", $model->UploadMonth);
                        } else{
                            $yeararray = explode("'", $model->UploadMonth);
}
                        //$yeararray = explode("`", $model->UploadMonth);
                         $month=$yeararray[0];
                          $year=DateTime::createFromFormat('y', $yeararray[1])->format('Y');
                          $upldmnth=$month." ".$year;?>
                        <label>Upload Month</label>
                        <input type="text" name="UploadMonth" id="datepicker2" placeholder="MM-yy" class="form-control" value="<?= $upldmnth ?>" autocomplete="off">
                    </div>
                     <div class="col-sm-6" style="margin-top:10px;">
                        <?= $form->field($model, 'ProductVertical')->textInput(['maxlength' => true,'required'=>true]) ?>
                        
                    </div>
                    <div class="form-group col-sm-12" style="margin-top:20px;">
                   <?= Html::submitButton('Submit',['class'=>'btn btn-success','name'=>'update','value'=>'update']) ?>
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
               </div>

                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
    $this->registerJs('
   
    $( document ).ready(function() {
        $("#datepicker").datepicker({
            dateFormat: "dd-mm-yy"
        });
        $("#datepicker1").datepicker({
            dateFormat: "dd-mm-yy"
        });
    
    $("#datepicker2").datepicker({ 
        dateFormat: "MM yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,

        onClose: function(dateText, inst) {  
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val(); 
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val(); 
            $(this).val($.datepicker.formatDate("MM yy", new Date(year, month, 1)));
        }
    });

    $("#datepicker2").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });    
    });




    });
');
  ?>