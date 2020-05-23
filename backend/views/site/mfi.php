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

  <div class="col-lg-12 grid-margin stretch-card" style="padding-left:0px;" >
    <div class="card">
      <div class="card-body">
        <h4 class="card-title col-sm-10">File Management</h4>
        <div class="col-sm-2">

          <a href="<?= Url::toRoute(['site/msme','id'=>$id,'type'=>$type,'mon'=>$mon,'export' => 'yes']);  ?>" class="btn btn-success">Export Report</a>
        </div>
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

              
              <th>Payment Status</th>
              <th>Payment Amount</th>
              <?php }?>

               <?php if ($approve == 0 ) {?>
              <th>Action</th>
              <?php  }else{?>
               <th>TinyUrl</th>
            <?php }
            ?> 
             <th>SMS Status</th>
            <?php if ($approve == 0 ) {?>
              <th>ErrorMsg</th>
              <?php  }?>
            </tr>
            <?php
            $smsStatus=0;
            foreach ($details as $key => $value) {
              $smsStatus=$value->SmsStatus;
              ?>
              <tr style="<?php if ($value->errorMsg!='' || $value->BranchName == '' || $value->Cluster == '' || $value->State == '' || $value->ClientId == '' || $value->ClientName == '' || $value->SpouseName ==''|| $value->VillageName ==''|| $value->Center ==''|| $value->GroupName =='' || $value->EmiSrNo =='' || $value->LoanAccountNo == '' || $value->MobileNo =='' || $value->DemandDate =='' || $value->LastMonthDue =='' || $value->CurrentMonthDue == '' || $value->LatePenalty=='' || $value->NextInstallmentDate =='' || $value->UploadMonth == '' || $value->ProductVertical == '' ) {?>background-color: #ff000045;<?php }else{echo "";} ?>">
                <td><?= $value->BranchName ?></td>
                <td><?= $value->Cluster ?></td>
                <td><?= $value->State ?></td>
                <td><?= $value->ClientId ?></td>
                <td><?= $value->LoanAccountNo ?></td>
                <td><?= $value->ClientName ?></td>
                <td><?= $value->SpouseName ?></td>
                <td><?= $value->VillageName ?></td>
                <td><?= $value->Center ?></td>
                <td><?= $value->GroupName ?></td>
                <td><?= $value->MobileNo ?></td>
                <td><?= $value->EmiSrNo ?></td>
                <td><?= date('d-m-Y',strtotime($value->DemandDate)) ?></td>
                <td><?= $value->LastMonthDue ?></td>
                <td><?= $value->CurrentMonthDue ?></td>
                <td><?= $value->LatePenalty ?></td>
                <td><?=date('d-m-Y',strtotime( $value->NextInstallmentDate)) ?></td>
                <td><?= $value->UploadMonth ?></td>
                <td><?= $value->ProductVertical ?></td>
                <!--  -->
              <?php /*if($sms == '') {?>
                
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
                <?php }*/?>
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
              } else{
                 ?>
                 <td><?=$value->TinnyUrl?></td>
                <?php
              }
              ?>

             
              <td><?=($smsStatus==1)?'Initiated':'Not Initiated'?></td>
          <td><?= ( $approve == 0)?$value->errorMsg:'' ?></td>
          
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


                      <?php if ($getblankvalue>= 1 ) {?>
                          <?php echo "";?>
                       <?php }else{
                        ?>
                       <?php 
                       if($approve==0 ) {
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
                      elseif($approve>=1 && $smsstat->SmsStatus == 0){
             
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
                           
                          }elseif ($approve>=1 && $smsstat->SmsStatus == 2) {
                          echo "<td colspan='19' style='text-align:left;'>Processing</td>";
                            
                          }
                          else
                          {
                          echo "<td colspan='19' style='text-align:left;'>SMS already sent</td>";
                          }
                          }?>

                    </td>
                  </tr>
                </table>

              </div>

              <?php if (!is_numeric($pages))
              {
               ?>
               <div class="row">
                <div class="col-sm-6 text-left"></div>
                <div class="col-sm-6 text-right"><?= yii\widgets\LinkPager::widget(['pagination' => $pages]);?></div>
              </div>
              <?php
            }
            ?>
          </div>
        </div>
      </div>



    </div>

