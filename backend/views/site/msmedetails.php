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
<div class="site-login">


  
 <!--  -->
    

    
    <div class="col-lg-12 grid-margin stretch-card" style="padding-left:0px;" >
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">File Management</h4>
          <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <tr>
              <th>Branch Name</th>
              <th>Cluster</th>
              <th>State</th>
              <th>Client Id</th>
              <th>LoanAccountNo </th>
              <th>Client Name</th>
              <th>MobileNo</th>
              <th>EmiSrNo</th>
              <th>DemandDate</th>
              <th>LastMonthDue</th>
              <th>CurrentMonthDue</th>
              <th>LatePenalty</th>
              <th>NextInstallmentDate</th>
              <th>UploadMonth</th>
              <th>ProductVertical</th>
			       <th>TinyUrl Link</th> 
               
            </tr>
          <?php
            foreach ($details as $key => $value) {
            //var_dump($value);  
            
            ?>
              <tr>
                <td><?= $value->BranchName ?></td>
                <td><?= $value->Cluster ?></td>
                <td><?= $value->State ?></td>
                <td><?= $value->ClientId ?></td>
                <td><?= $value->LoanAccountNo ?></td>
                <td><?= $value->ClientName ?></td>
                <td><?= $value->MobileNo ?></td>
                <td><?= $value->EmiSrNo ?></td>
                <td><?= $value->DemandDate ?></td>
                <td><?= $value->LastMonthDue ?></td>
                <td><?= $value->CurrentMonthDue ?></td>
                <td><?= $value->LatePenalty ?></td>
                <td><?= $value->NextInstallmentDate ?></td>
                <td><?= $value->UploadMonth ?></td>
                <td><?= $value->ProductVertical ?></td>
			         	<td><?= $value->TinnyUrl ?></td>
                
              </tr>
              
              <?php
            }

            ?>
			
			<tr>
				 <?php if ($type==34) {?>
           <td colspan="19" style="text-align:left;"> <a href="<?= Url::toRoute(['site/sendsms','id'=>$RecordId,'type'=>34,'mon'=>$mon]); ?>"><button class="btn btn-success" type="button" > Send SMS to All</button></a>
       <?php  } ?>
       <?php if ($type==35) {?>
           <td colspan="19" style="text-align:left;"> <a href="<?= Url::toRoute(['site/sendsms','id'=>$RecordId,'type'=>35,'mon'=>$mon]); ?>"><button class="btn btn-success" type="button" > Send SMS to All</button></a>
       <?php  } ?>
       <?php if ($type==36) {?>
           <td colspan="19" style="text-align:left;"> <a href="<?= Url::toRoute(['site/sendsms','id'=>$RecordId,'type'=>36,'mon'=>$mon]); ?>"><button class="btn btn-success" type="button" > Send SMS to All</button></a>
       <?php  } ?>
       <?php if ($type==37) {?>
           <td colspan="19" style="text-align:left;"> <a href="<?= Url::toRoute(['site/sendsms','id'=>$RecordId,'type'=>37,'mon'=>$mon]); ?>"><button class="btn btn-success" type="button" > Send SMS to All</button></a>
       <?php  } ?>
        <?php if ($type==39) {?>
           <td colspan="19" style="text-align:left;"> <a href="<?= Url::toRoute(['site/sendsms','id'=>$RecordId,'type'=>39,'mon'=>$mon]); ?>"><button class="btn btn-success" type="button" > Send SMS to All</button></a>
       <?php  } ?>
          
		  		</td>
			</tr>
          </table>

	
        </div>
        </div>
      </div>
    </div>
    


  </div>
<script type="text/javascript">
    history.pushState(null, null, location.href);
    history.back();
    history.forward();
    window.onpopstate = function () { history.go(1); };
</script>
