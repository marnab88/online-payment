<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\UploadRecords;
use yii\helpers\Url;
//use yii\widgets\ActiveForm;
$this->title = 'Customer Details';

?>
<div class="site-login">
    <div class="col-lg-12 grid-margin stretch-card" style="padding-left:0px;" >
      <div class="card">
        <div class="card-body">
          <h4 class="card-title col-sm-6">Customer Details</h4>
          <?php if ($details) {?>
             <div class="col-sm-6">
                <input type="button" value="Export Report" class="btn btn-success exportToExcel" style="float:right;">
            </div>
          <?php } ?>
         
          <div class="table-responsive">
          <table class="table table-striped table-bordered" id="table_emp">
            <tr>
              <th>Sl. No.</th>
              <th>Type</th>
              <th>Branch Name</th>
              <th>Cluster</th>
              <th>State</th>
              <th>Client Id</th>
              <th>LoanAccountNo </th>
              <th>Client Name</th>
              <?php if ($type == 'MFI') {?>
              <th>Spouse Name</th>
              <th>Village Name</th>
              <th>Center</th>
              <th>Group Name</th>
             <?php }else{echo'';}?>
              <th>MobileNo</th>
              <th>EmiSrNo</th>
              <th>DemandDate</th>
              <th>LastMonthDue</th>
              <th>CurrentMonthDue</th>
              <th>LatePenalty</th>
              <th>NextInstallmentDate</th>
              <th>UploadMonth</th>
              <th>Transaction Date</th>
              <th>Wallet Bank Ref. No.</th>
              <th>Receipt Mode</th>
              <th>Receipt Amount</th>
              <th>Due Amount</th>
             
            </tr>
          <?php
            foreach ($details as $key => $value) {
            ?>

              <tr>

                <td><?= $key+1 ?></td>
                <td><?= $value->Type ?></td>
                <td><?= $value->BranchName ?></td>
                <td><?= $value->Cluster ?></td>
                <td><?= $value->State ?></td>
                <td><?= $value->ClientId ?></td>
                <td><?= $value->LoanAccountNo ?></td>
                <td><?= $value->ClientName ?></td>
                <?php if ($type == 'MFI') {?>
                <td><?= $value->SpouseName ?></td>
                <td><?= $value->VillageName ?></td>
                <td><?= $value->Center ?></td>
                <td><?= $value->GroupName ?></td>
                <?php }else{echo'';}?>
                <td><?= $value->MobileNo ?></td>
                <td><?= $value->EmiSrNo ?></td>
                <td><?= date('d-m-Y',strtotime($value->DemandDate)) ?></td>
                <td><?= number_format($value->LastMonthDue,2); ?></td>
                <td><?= number_format($value->CurrentMonthDue,2); ?></td>
                <td><?= number_format($value->LatePenalty,2); ?></td>
                <td><?= date('d-m-Y',strtotime($value->NextInstallmentDate)) ?></td>
                <td><?= $value->UploadMonth ?></td>

                <td>
                  <?php
                        
                        $txdt=isset($value->Eid) ? $paymetdetails[$value->Eid]->TXN_DATE:$paymetdetails[$value->Mid]->TXN_DATE;
                        if($txdt){
                         echo date('d-m-Y H:i:s',strtotime($txdt));
                        }else{
                        echo '';
                      }
                ?>
                </td>

                
                <td>
                  <?=isset($value->Mid)?$paymetdetails[$value->Mid]->WALLET_BANK_REF:$paymetdetails[$value->Eid]->WALLET_BANK_REF;?>
                </td>
                <td>
                  <?=isset($value->Mid)?$paymetdetails[$value->Mid]->PG_MODE:$paymetdetails[$value->Eid]->PG_MODE;?>
                </td>
                <td><?php
                        
                        $paid=isset($value->Eid) ? $paymetdetails[$value->Eid]->TXN_AMT:$paymetdetails[$value->Mid]->TXN_AMT;
                        if(!$paid){
                         $paid=0;
                        }
                        echo number_format($paid,2);
                ?></td>
                <td>
                  <?= number_format(($value->LastMonthDue + $value->CurrentMonthDue + $value->LatePenalty)-$paid,2) ?>
                </td>
              </tr>
              
              <?php
            }
            ?>
          </table>
        </div>
        </div>
      </div>
    </div>
  </div>


 <?php
    $this->registerJs('
    $(".exportToExcel").click(function(e){
          var table = $("#table_emp");
          if(table && table.length){
            var preserveColors = (table.hasClass("table2excel_with_colors") ? true : false);
            $("#table_emp").table2excel({
              exclude: ".noExl",
              name: "Report",
              filename: "Report" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
              fileext: ".xls",
              exclude_img: true,
              exclude_links: true,
              exclude_inputs: true,
              preserveColors: preserveColors
            });
          }
        });
');
  ?>










