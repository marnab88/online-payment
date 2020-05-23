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
            <td>
            	<?php
            if ($details->Type == 'MFI') {?>

                <span style="color:#FF3333;">
                	<?php if ($details->Status == -1){ ?>
                	<?php echo "File Upload Error"; ?>
                	<?php }else if($details->Status != 1){?>
                		<?php echo"Processing";?>
                		<?php }else{ echo $details->Count;}?>
                </span>
             <?php	
             } 
             else{
                ?>

                <span style="color:#FF3333;">
                	<?php if ($details->Status == -1){ ?>
                	<?php echo "File Upload Error"; ?>
                	<?php }else if($details->Status != 1){?>
                		<?php echo"Processing";?>
                		<?php }else{ echo $details->Count;}?>
                </span>
             <?php	
             }
              ?>
          </td>
            
            <td>
            	<span style="color:#00CC00">
            		<?php if ($details->Status == -1){ ?>
                	<?php echo "File Upload Error"; ?>
                	<?php }else if($details->Status != 1){?>
                		<?php echo"Processing";?>
                		<?php }else{ echo $details->Count-($details->Mismatch+$details->MobileCount);}?>
            		
            	</span>
            </td>
            <td>
                <?php
                if ($details->Status == -1){ ?>
                	<span style="color:#FF3333;">File Upload Error</span>
                	<?php }else if($details->Status != 1){?>
                		<span style="color:#FF3333;">Processing</span>
                	<?php }else{
                    if ($details->Type == 'MFI') {?>
                        <span style="color:#FF3333;"><?=($details->Mismatch+$details->MobileCount>0)?Html::a($details->Mismatch+$details->MobileCount, ['site/mfi','id' => $details->RecordId,'mon'=>$details->MonthYear,'type' =>35,'error'=>'true'],['target'=>'_blank']):'0'?></span>
                    <?php
                    }
                    else{
                        ?>
                        <span style="color:#FF3333;"><?=($details->Mismatch+$details->MobileCount>0) ? Html::a($details->Mismatch+$details->MobileCount, ['site/msme','id' => $details->RecordId,'mon'=>$details->MonthYear,'type' =>35,'error'=>'true'],['target'=>'_blank']):'0'?></span>
                    <?php
                    }
            	}
                ?>
            </td>
            <td>
            <?php 
            if($details->Status == 1 && $details->Status != -1)
            {
            ?>
            <a href="<?php
            if(Yii::$app->user->identity->Type == "MFI"){
             echo Url::toRoute(['site/mfi','id' => $details->RecordId,'mon'=>$details->MonthYear,'type' =>35,'pagination'=>'show']);}
             elseif(Yii::$app->user->identity->Type == "MSME"){
             echo Url::toRoute(['site/msme','id' => $details->RecordId,'mon'=>$details->MonthYear,'type' =>35,'pagination'=>'show']);}
             else{
                echo Url::toRoute(['site/both','id' => $details->RecordId,'mon'=>$details->MonthYear,'type' =>35,'pagination'=>'show']);
             }							 
            ?>" data-method="post"  style="float:left;color:#ff3300; margin-top:5px;">View Details</a>
            &nbsp;&nbsp;
            <?php } 
            if ($details->IsApproved == '0') { ?>
            	<a href="<?=Url::toRoute(['site/deleterecord','recordid' => $details->RecordId,'type'=>$details->Type])?>"> delete</a>
           <?php }
            ?>
            
            
            </td>
        </tr>
        <?php
        }
	}
?>