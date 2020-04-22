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
          <?php
          if($error)
          {?>
          <input type="button" value="Export" class="btn btn-success exportToExcel" style="float:right;">
          <a href="<?=Url::toRoute(['site/deleterecord','recordid' => $details[0]->RecordId,'type'=>$type,'dlt'=>'only'])?>"><input type="button" value="delete" class="btn btn-danger" style="float:right;"></a>
          <?php
          }
          ?>
          <div class="table-responsive">
          <table class="table table-striped table-bordered" id="table_emp">
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

              <?php if($sms == ''){?>
                 
              <?php }else{?> 
                  <!--<th>Sms Status</th>
               <th>TinyUrl Link</th>
               <th>Payment Status</th>
               <th>Payment Amount</th>-->
              <?php }?>
             <?php if ( $approve == 0) {?>
              <th>Action</th>
            <?php  }
            else{
            ?>
            <th>TinyUrl</th>
            <?php }
            ?>
            <th><?=( $approve == 0)?'ErrorMsg':''?></th>
            </tr>
          <?php
         
            foreach ($details as $key => $value) {
            //var_dump($value); 

            
            ?>
              <tr style="<?php if ($value->errorMsg!='' || $value->BranchName == '' || $value->Cluster == '' || $value->State == '' || $value->ClientId == '' || $value->ClientName == '' || $value->EmiSrNo =='' || $value->LoanAccountNo == '' || $value->MobileNo =='' || $value->DemandDate =='' || $value->LastMonthDue =='' || $value->CurrentMonthDue == '' || $value->LatePenalty=='' || $value->NextInstallmentDate =='' || $value->UploadMonth == '' || $value->ProductVertical == '' ) {?>background-color: #ff000045;<?php }else{echo "";} ?>">
                <td><?= $value->BranchName ?></td>
                <td><?= $value->Cluster ?></td>
                <td><?= $value->State ?></td>
                <td><?= $value->ClientId ?></td>
                 <td> <?= $value->LoanAccountNo ?></td>
                <td><?= $value->ClientName ?></td>
                <td ><?= $value->MobileNo ?></td>
                <td><?= $value->EmiSrNo ?></td>
                <td><?= date('d-m-Y',strtotime($value->DemandDate)) ?></td>
                <td><?= $value->LastMonthDue ?></td>
                <td><?= $value->CurrentMonthDue ?></td>
                <td><?= $value->LatePenalty ?></td>
                <td><?=date('d-m-Y',strtotime( $value->NextInstallmentDate)) ?></td>
                <td><?= $value->UploadMonth ?></td>
                <td><?= $value->ProductVertical ?></td>

              <?php /* if($sms == '') {?>

                
                 <?php }elseif($value->SmsStatus == 1){?>
                 <td><label class="badge badge-success">Delivered</label></td>
                
                <td><a href="<?= $value->TinnyUrl ?>" target="_blank"><?= $value->TinnyUrl ?></a></td>
                <td>
                <?php if ($paymetdetails->TXN_STATUS == 1) {?>
                  <label class="badge badge-success">Paid</label>
               <?php  }else{ ?>
                <label class="badge badge-danger">Not Paid</label>
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
                <td><label class="badge badge-danger">Not Paid</label></td>
                
                <td><a href="#" class="badge badge-danger">N/A</a></td>
                 <?php }*/?>
                <?php if ($approve == 0) { ?>
                <?php if ($type == 34) {?>
                   <td>  <a href="<?= Url::toRoute(['site/updatemsme','id' => $value->Mid,'type'=>34,'mon'=>$mon]);  ?>"   style="float:left;">EDIT</a></td>
               <?php  } ?>
                <?php if ($type == 35) {?>
                   <td>  <a href="<?= Url::toRoute(['site/updatemsme','id' => $value->Mid,'type'=>35,'mon'=>$mon]);  ?>"   style="float:left;">EDIT</a></td>
               <?php  } ?>
                <?php if ($type == 36) {?>
                   <td>  <a href="<?= Url::toRoute(['site/updatemsme','id' => $value->Mid,'type'=>36,'mon'=>$mon]);  ?>"   style="float:left;">EDIT</a></td>
               <?php  } ?>
               <?php if ($type == 37) {?>
                 <td>  <a href="<?= Url::toRoute(['site/updatemsme','id' => $value->Mid,'type'=>37,'mon'=>$mon]);  ?>"   style="float:left;">EDIT</a></td>
              <?php } ?>
                <?php if ($type == 39) {?>
                 <td>  <a href="<?= Url::toRoute(['site/updatemsme','id' => $value->Mid,'type'=>39,'mon'=>$mon]);  ?>"   style="float:left;">EDIT</a></td>
              <?php } ?>
                 
                  <?php
                
              }
              else{
                 ?>
                 <td><?=$value->TinnyUrl?></td>
                <?php
              }
              ?>
          <td><?= ( $approve == 0)?$value->errorMsg:'' ?></td>
              </tr>
              
              <?php
            }
            ?>
        
      <tr>
        <?php if ($type==34) {?>
          <td colspan="19" style="text-align:left;"><button class="btn btn-danger" type="button"><a href="<?= Url::to(['site/adminprev']);  ?>" data-method="post"  style="float:left;color:#fff; ">Back</a></button>
        
      <?php  }  ?>
       <?php if ($type==35) {?>
          <td colspan="19" style="text-align:left;"><a href="<?= Url::to(['site/index']);  ?>" data-method="post"  style="float:left;color:#fff; "><button class="btn btn-danger" type="button">Back</button></a>
        
      <?php  }  ?>
      <?php if ($type==36) {?>
          <td colspan="19" style="text-align:left;"><a href="<?= Url::to(['site/adminview']);  ?>" data-method="post"  style="float:left;color:#fff; "><button class="btn btn-danger" type="button">Back</button></a>
        
      <?php  }  ?>
      <?php if ($type == 37) {?>
         <td colspan="19" style="text-align:left;"><a href="<?= Url::to(['site/previous']);  ?>" data-method="post"  style="float:left;color:#fff; "><button class="btn btn-danger" type="button">Back</button></a>
      <?php } ?>
      <?php if ($type == 39) {?>
         <td colspan="19" style="text-align:left;"><a href="<?= Url::to(['site/previous']);  ?>" data-method="post"  style="float:left;color:#fff; "><button class="btn btn-danger" type="button">Back</button></a>
      <?php } ?>

        
          <?php
          
         if ($getblankvalue>= 1 ) {?>
              <?php echo "";?>
           <?php }else{
            ?>
           <?php if($approve==0 ){?>
            <?php if ($type == 34) {?>
               <a href="<?= Url::toRoute(['site/msmedetails','id' => $id,'type'=>34,'mon'=>$mon]);  ?>" data-method="post"  style="float:left;color:#fff;"  data-confirm="Warning: Once confirmed approval, this step cannot be reverted back."><button class="btn btn-success" type="button" name="approve">Approve</button></a>
          <?php  } ?>
          <?php if ($type == 35) {?>
               <a href="<?= Url::toRoute(['site/msmedetails','id' => $id,'type'=>35,'mon'=>$mon]);  ?>" data-method="post"  style="float:left;color:#fff;"  data-confirm="Warning. Once confirmed approval, this step cannot be reverted back. "><button class="btn btn-success" type="button" name="approve">Approve</button></a>
          <?php  } ?>
          <?php if ($type == 36) {?>
               <a href="<?= Url::toRoute(['site/msmedetails','id' => $id,'type'=>36,'mon'=>$mon]);  ?>" data-method="post"  style="float:left;color:#fff;"  data-confirm="Warning. Once confirmed approval, this step cannot be reverted back."><button class="btn btn-success" type="button" name="approve">Approve</button></a>
          <?php  } ?>
          <?php if ($type == 37) {?>
              <a href="<?= Url::toRoute(['site/msmedetails','id' => $id,'type'=>37,'mon'=>$mon]);  ?>" data-method="post"  style="float:left;color:#fff;"  data-confirm="Warning. Once confirmed approval, this step cannot be reverted back."><button class="btn btn-success" type="button" name="approve">Approve</button></a>
          <?php } ?>
           <?php if ($type == 39) {?>
              <a href="<?= Url::toRoute(['site/msmedetails','id' => $id,'type'=>39,'mon'=>$mon]);  ?>" data-method="post"  style="float:left;color:#fff;"  data-confirm="Warning. Once confirmed approval, this step cannot be reverted back. "><button class="btn btn-success" type="button" name="approve">Approve</button></a>
          <?php } ?>
           
            <?php }
            elseif($approve==1){
             ?>
             <?php if ($type==34) {?>
           <td colspan="19" style="text-align:left;"> <a href="<?= Url::toRoute(['site/sendsms','id'=>$id,'type'=>34,'mon'=>$mon]); ?>"><button class="btn btn-success" type="button" > Send SMS to All</button></a>
       <?php  } ?>
       <?php if ($type==35) {?>
           <td colspan="19" style="text-align:left;"> <a href="<?= Url::toRoute(['site/sendsms','id'=>$id,'type'=>35,'mon'=>$mon]); ?>"><button class="btn btn-success" type="button" > Send SMS to All</button></a>
       <?php  } ?>
       <?php if ($type==36) {?>
           <td colspan="19" style="text-align:left;"> <a href="<?= Url::toRoute(['site/sendsms','id'=>$id,'type'=>36,'mon'=>$mon]); ?>"><button class="btn btn-success" type="button" > Send SMS to All</button></a>
       <?php  } ?>
       <?php if ($type==37) {?>
           <td colspan="19" style="text-align:left;"> <a href="<?= Url::toRoute(['site/sendsms','id'=>$id,'type'=>37,'mon'=>$mon]); ?>"><button class="btn btn-success" type="button" > Send SMS to All</button></a>
       <?php  } ?>
        <?php if ($type==39) {?>
           <td colspan="19" style="text-align:left;"> <a href="<?= Url::toRoute(['site/sendsms','id'=>$id,'type'=>39,'mon'=>$mon]); ?>"><button class="btn btn-success" type="button" > Send SMS to All</button></a>
       <?php  } ?>
             
             <?php
             
            }
            
            }?>

      </td>
      </tr>

          </table>
          
        </div>
        </div>
      </div>
    </div>
    


  </div>
 
 
 <?php
$js = <<<JS
$(".exportToExcel").click(function(e){
          var table = $("#table_emp");
          if(table && table.length){
            var preserveColors = (table.hasClass("table2excel_with_colors") ? true : false);
            $("#table_emp").table2excel({
              exclude: ".noExl",
              name: "payment",
              filename: "failedUpload" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
              fileext: ".xls",
              exclude_img: true,
              exclude_links: true,
              exclude_inputs: true,
              preserveColors: preserveColors
            });
          }
          
        });
JS;
$this->registerJs($js);
?>
  

