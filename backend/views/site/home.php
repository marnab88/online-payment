<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\UploadRecords;
use yii\helpers\Url;
//use yii\widgets\ActiveForm;
$this->title = 'Home';

?>
<!--  -->
<div class="site-login">

<style type="text/css"> 
.big {
    font-size: 20px;
    margin-left: 10px;
    color: #fff700;
    line-height: 14px;
}

.help-block-error{
	color:#fff !important;
}

.control-label, .radio-inline{
	color:#fff !important;
}
</style>
	<?php if(Yii::$app->user->identity->Role == 1 || Yii::$app->user->identity->UploadRecords == 1){ ?>
	<div class="row">
		
		
		<div class="col-lg-7 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Upload Monthly Data</h4>
					
					<?php $form = ActiveForm::begin(['options' => ['class' => 'forms-sample','enctype' => 'multipart/form-data']]) ?>
					
					<div class="form-group">


						<?php
						echo $form->field($model, 'MonthYear')->dropDownList(
							$MonthYear,  ['prompt'=>'Select Month/Year']); 
							?>
							
						</div>
						<div class="form-group">
							<?= $form->field($model, 'File')->fileInput() ?>
						</div>

						<div class="form-group">
							

							<?php if(Yii::$app->user->identity->Type == "MFI"){
							echo $form->field($model, 'Type')->inline()->radioList(['MFI' => 'MFI'], ['separator' => '&nbsp; &nbsp;']); 
						}
						elseif(Yii::$app->user->identity->Type == "MSME"){
							echo $form->field($model, 'Type')->inline()->radioList(['MSME' => 'MSME'], ['separator' => '&nbsp; &nbsp;']); 
						}
						else{
							echo $form->field($model, 'Type')->inline()->radioList(['MSME' => 'MSME', 'MFI' => 'MFI'], ['separator' => '&nbsp; &nbsp;']); 
						}

							?>
						
							
							
						</div>
						<button type="submit" class="btn btn-success mr-2" >Submit</button>
						<button class="btn btn-light">Cancel</button>
                       
						<?php ActiveForm::end() ?>
						<div style="width:400px; float:left; margin-top:20px;">
                         <a href="<?= Yii::getAlias('@backendUrl').'/assets/sample/testmfi.xlsx' ?>" target="_blank">
						<button type="submit" class="btn btn-danger mr-2" >Sample MFI <span class="mdi mdi-cloud-download big"></span>  </button>
                        </a>
                        <a href="<?= Yii::getAlias('@backendUrl').'/assets/sample/testmsme.xlsx' ?>" target="_blank">
						<button type="submit" class="btn btn-danger mr-2" >Sample MSME <span class="mdi mdi-cloud-download big"></span>  </button>
                        </a>
						</div>
					</div>
				</div>
			</div>
			
			
		</div>
		<?php } ?>

		<?php if(Yii::$app->user->identity->Role == 1 || Yii::$app->user->identity->ViewReports == 1){ ?>
		<div class="col-lg-12 grid-margin stretch-card" style="padding-left:0px;" >
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">last Uploaded File </h4>
					<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<tr>
							<th>Download File</th>
							<th>File Type</th>
							<th>Date</th>
							<th>Uploaded By</th>
							<th>Month</th>
							<th>Number of Records </th>
							<th>Cleared</th>
							<th>Mismatch</th>
							<th>View Details</th>
						</tr>
						<?php
						if ($alldetails) {
							
                            foreach($alldetails as $details)
                                {
                                ?>
                                <tr>
                                    <td><a href="<?= Yii::getAlias('@storageUrl').'/uploads/'.$details->File?>" target="_blank" style="color:#d89d51;">Download</a></td>
                                    <td><?= $details->Type ?></td>
                                    <td><?= date('d-m-Y',strtotime($details->OnDate)) ?></td>
                                    <td><?= ($details->user)?$details->user->LoginId:''; ?></td>
                                    <td><?= $details->MonthYear  ?></td>
                                    <td><?php
                                    if ($details->Type == 'MFI') {?>
    
                                        <span style="color:#FF3333;"><?= $details->Count?></span>
                                     <?php	
                                     } 
                                     else{
                                        ?>
    
                                        <span style="color:#FF3333;"><?=$details->Count?></span>
                                     <?php	
                                     }
                                      ?></td>
                                    
                                    <td><span style="color:#00CC00"><?=$details->Count-($details->Mismatch+$details->MobileCount) ?></span></td>
                                    <td>
                                        <?php
                                        if ($details->Type == 'MFI') {?>
                                            <span style="color:#FF3333;"><?=($details->Mismatch+$details->MobileCount>0)?Html::a($details->Mismatch+$details->MobileCount, ['site/mfi','id' => $details->RecordId,'mon'=>$details->MonthYear,'type' =>35,'error'=>'true'],['target'=>'_blank']):'0'?></span>
                                        <?php
                                        }
                                        else{
                                            ?>
                                            <span style="color:#FF3333;"><?=($details->Mismatch+$details->MobileCount>0) ? Html::a($details->Mismatch+$details->MobileCount, ['site/msme','id' => $details->RecordId,'mon'=>$details->MonthYear,'type' =>35,'error'=>'true'],['target'=>'_blank']):'0'?></span>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td><a href="<?php
                                    if(Yii::$app->user->identity->Type == "MFI"){
                                     echo Url::toRoute(['site/mfi','id' => $details->RecordId,'mon'=>$details->MonthYear,'type' =>35]);}
                                     elseif(Yii::$app->user->identity->Type == "MSME"){
                                     echo Url::toRoute(['site/msme','id' => $details->RecordId,'mon'=>$details->MonthYear,'type' =>35]);}
                                     else{
                                        echo Url::toRoute(['site/both','id' => $details->RecordId,'mon'=>$details->MonthYear,'type' =>35]);
                                     }							 
                                    ?>" data-method="post"  style="float:left;color:#ff3300; margin-top:5px;">View Details</a>
                                    &nbsp;&nbsp;
                                    <a href="<?=Url::toRoute(['site/deleterecord','recordid' => $details->RecordId,'type'=>$details->Type])?>"> delete</a>
                                    
                                    </td>
                                </tr>
                                <?php
                                }
							}
						?>		
					</table>
					</div>
					

				</div>
			</div>
		</div>
		
		<?php } ?>

	</div>

