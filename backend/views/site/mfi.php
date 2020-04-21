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
              <th>Spouse Name</th>
              <th>Village Name</th>
              <th>Center</th>
              <th>Group Name</th>
              <th>MobileNo</th>
              <th>EmiSrNo</th>
              <th>DemandDate</th>
              <th>LastMonthDue</th>
              <th>CurrentMonthDue</th>
              <th>LatePenalty</th>
              <th>NextInstallmentDate</th>
              <th>UploadMonth</th>
              <th>ProductVertical</th>
             <?php if($sms == ''){?>
                 
              <?php }else{?> 
                  <th>Sms Status</th>
               <th>TinyUrl Link</th>
               <th>Payment Status</th>
               <th>Payment Amount</th>
              <?php }?>
               
              <?php if ($approve == 0 ) {?>
              <th>Action</th>
            <?php  }?> 
            </tr>
          <?php
            foreach ($details as $key => $value) {
            ?>
              <tr>
                <td><?= $value->BranchName ?></td>
                <td><?= $value->Cluster ?></td>
                <td><?= $value->State ?></td>
                <td><?= $value->ClientId ?></td>
               <td style="<?php if (in_array("$value->Eid", $getid)){echo "color:#F7330A";}else{echo "";} ?>"><?= $value->LoanAccountNo ?></td>
                <td><?= $value->ClientName ?></td>
                <td><?= $value->SpouseName ?></td>
                <td><?= $value->VillageName ?></td>
                <td><?= $value->Center ?></td>
                <td><?= $value->GroupName ?></td>
                 <td style="<?php if (strlen($value->MobileNo) !=10 ) {echo "color:#F7330A";}else{echo "";} ?>"><?= $value->MobileNo ?></td>
                <td><?= $value->EmiSrNo ?></td>
                <td><?= date('d-m-Y',strtotime($value->DemandDate)) ?></td>
                <td><?= $value->LastMonthDue ?></td>
                <td><?= $value->CurrentMonthDue ?></td>
                <td><?= $value->LatePenalty ?></td>
                <td><?=date('d-m-Y',strtotime( $value->NextInstallmentDate)) ?></td>
                <td><?= $value->UploadMonth ?></td>
                <td><?= $value->ProductVertical ?></td>
                <!--  -->
              <?php if($sms == '') {?>
                
                 <?php }elseif($value->SmsStatus == 1){?>
                 <td><label class="badge badge-success">Delivered</label></td>
                
                <td><a href="<?= $value->TinnyUrl ?>" target="_blank"><?= $value->TinnyUrl ?></a></td>
                <td>
                <?php if ($paymetdetails->TXN_STATUS == 1) {?>
                  <label class="badge badge-success">Success</label>
               <?php  }else{ ?>
                <label class="badge badge-danger">Failed</label>
               <?php } ?>
                </td>
               <?php if ($paymetdetails->TXN_AMT == "") {?>
                  <td><a href="#" class="badge badge-danger">N/A</a></td>
              <?php  } else{?>
                <td><?= $paymetdetails->TXN_AMT ?></td>
            <?php  } ?>
                 <?php }else{ ?>
                  <td><label class="badge badge-danger">Failed</label></td>
                
                <td><a href="#" class="badge badge-danger">N/A</a></td>
                 <?php }?>
                <?php if ($approve == 0) {?>
                  <?php if ($type == 34) {?>
                     <td>  <a href="<?= Url::toRoute(['site/updatemfi','id' => $value->Eid,'type'=>34,'mon'=>$mon]);  ?>"   style="float:left;">EDIT</a></td>
                 <?php  } ?>
                 <?php if ($type == 35) {?>
                     <td>  <a href="<?= Url::toRoute(['site/updatemfi','id' => $value->Eid,'type'=>35,'mon'=>$mon]);  ?>"   style="float:left;">EDIT</a></td>
                 <?php  } ?>
                 <?php if ($type == 36) {?>
                     <td>  <a href="<?= Url::toRoute(['site/updatemfi','id' => $value->Eid,'type'=>36,'mon'=>$mon]);  ?>"   style="float:left;">EDIT</a></td>
                 <?php  } ?>
                  <?php if ($type == 37) {?>
                 <td>  <a href="<?= Url::toRoute(['site/updatemsme','id' => $value->Mid,'type'=>37,'mon'=>$mon]);  ?>"   style="float:left;">EDIT</a></td>
              <?php } ?>
               <?php if ($type == 38) {?>
                 <td>  <a href="<?= Url::toRoute(['site/updatemsme','id' => $value->Mid,'type'=>38,'mon'=>$mon]);  ?>"   style="float:left;">EDIT</a></td>
              <?php } ?>
                
                <?php
              }
                ?>
               

              </tr>
              
              <?php
            }
            ?>
				
			<tr>
        <?php if ($type==34) {?>
          <td colspan="19" style="text-align:left;"><a href="<?= Url::to(['site/adminprev']);  ?>" data-method="post"  style="float:left;color:#fff; "><button class="btn btn-danger" type="button">Back</button></a>
        
      <?php  }  ?>
       <?php if ($type==35) {?>
          <td colspan="19" style="text-align:left;"><a href="<?= Url::to(['site/index']);  ?>" data-method="post"  style="float:left;color:#fff; "><button class="btn btn-danger" type="button">Back</button></a>
        
      <?php  }  ?>
      <?php if ($type==36) {?>
          <td colspan="19" style="text-align:left;"><a href="<?= Url::to(['site/adminview']);  ?>" data-method="post"  style="float:left;color:#fff; "><button class="btn btn-danger" type="button">Back</button></a>
        
      <?php  }  ?>
      <?php if ($type==37) {?>
          <td colspan="19" style="text-align:left;"><a href="<?= Url::to(['site/previous']);  ?>" data-method="post"  style="float:left;color:#fff; "><button class="btn btn-danger" type="button">Back</button></a>
        
      <?php  }  ?>
      <?php if ($type==38) {?>
          <td colspan="19" style="text-align:left;"><a href="<?= Url::to(['site/previous']);  ?>" data-method="post"  style="float:left;color:#fff; "><button class="btn btn-danger" type="button">Back</button></a>
        
      <?php  }  ?>
        
          <?php
        if ($approve == 0) {
          ?>
          <?php if ($type == 34) {?>
           <a href="<?= Url::toRoute(['site/mfidetails','id' => $id,'type'=>34,'mon'=>$mon]);  ?>" data-method="post"  style="float:left;color:#fff;"><button class="btn btn-success" type="button">Approve</button></a>
        <?php  } ?>
         <?php if ($type == 35) {?>
           <a href="<?= Url::toRoute(['site/mfidetails','id' => $id,'type'=>35,'mon'=>$mon]);  ?>" data-method="post"  style="float:left;color:#fff;"><button class="btn btn-success" type="button">Approve</button></a>
        <?php  } ?>
         <?php if ($type == 36) {?>
           <a href="<?= Url::toRoute(['site/mfidetails','id' => $id,'type'=>36,'mon'=>$mon]);  ?>" data-method="post"  style="float:left;color:#fff;"><button class="btn btn-success" type="button">Approve</button></a>
        <?php  } ?>
        <?php if ($type == 37) {?>
              <a href="<?= Url::toRoute(['site/msmedetails','id' => $id,'type'=>37,'mon'=>$mon]);  ?>" data-method="post"  style="float:left;color:#fff;"  data-confirm="Warning are you sure to approve this file because file once can't be editable "><button class="btn btn-success" type="button" name="approve">Approve</button></a>
          <?php } ?>
            <?php if ($type == 38) {?>
              <a href="<?= Url::toRoute(['site/msmedetails','id' => $id,'type'=>38,'mon'=>$mon]);  ?>" data-method="post"  style="float:left;color:#fff;"  data-confirm="Warning are you sure to approve this file because file once can't be editable "><button class="btn btn-success" type="button" name="approve">Approve</button></a>
          <?php } ?>
           
          
         <?php 
        }
       
        ?>

      </td>
      </tr>
          </table>
          
        </div>
        </div>
      </div>
    </div>
    


  </div>

