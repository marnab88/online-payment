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
<style type="text/css">
  select.form-control {
    height: 34px !important;
}
.pagination {
    float: right;
}
</style>
<div class="site-login">
    <div class="col-lg-12 grid-margin stretch-card" style="padding-left:0px;" >
      <div class="card">
        <div class="card-body">
          <h4 class="card-title col-sm-8">
          Loan Wise Payment Report
        </h4>
        <div class="col-sm-2">
           <select class="form-control" onchange="show_pay(this.value);" style="color:#000;">
            <option value="all">All</option>
            <option value="Partial">Partial</option>
            <option value="Complete">Complete</option>
            <option value="Nil">Nil</option>
           </select>
        </div>

          <?php if ($details) {?>
             <div class="col-sm-2">
              <a href="<?= Url::toRoute(['site/customerdetails','branch' => $branch,'fromdt' =>$fromdt,'todt' =>$todt,'export' => 'yes']);  ?>" class="btn btn-success">Export Report</a>
                
            </div>
          <?php } ?>
         <!-- <input type="button" value="Export Report" class="btn btn-success exportToExcel" style="float:right;"> -->
          <div class="table-responsive">
           
          <table class="table table-striped table-bordered" id="table_emp">
            <tr>
              <th>Sl. No.</th>
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
              <th>Transaction Date</th>
              <th>Bank Ref. No.</th>
              <th>Receipt Mode</th>
              <th>Receipt Amount</th>
              <th>Due Amount</th>
              <th>Type</th>
              <th>Status</th>
            </tr>
          <?php
            foreach ($details as $key => $value) {
              $totalamt=$value->LastMonthDue+$value->CurrentMonthDue+$value->LatePenalty;
              $paid=isset($value->Eid) ? $paymetdetails[$value->Eid]->TXN_AMT:$paymetdetails[$value->Mid]->TXN_AMT;
              if(!$paid){
               $paid=0;
              }
              $due=$totalamt-$paid;
             if($due>0)
              $pay_status='Partial';
             elseif($due<=0)
             $pay_status='Complete';
             if($paid==0)
             $pay_status='Nill';
            ?>

              <tr class="<?=$pay_status?> all">

                <td><?= $key+1 ?></td>
                <td><?= $value->BranchName ?></td>
                <td><?= $value->Cluster ?></td>
                <td><?= $value->State ?></td>
                <td><?= $value->ClientId ?></td>
                <td><?= $value->LoanAccountNo ?></td>
                <td><?= $value->ClientName ?></td>
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
                  if ($paymetdetails[$value->Mid]->TXN_DATE) {?>
                    <?= date('d-m-Y H:i:s',strtotime($paymetdetails[$value->Mid]->TXN_DATE)) ?>
                 <?php }else{echo "";}?>
                  
                 
                </td>

                
                <td>
                  <?= $paymetdetails[$value->Mid]->WALLET_BANK_REF ?>
                </td>
                <td>
                  <?= $paymetdetails[$value->Mid]->PG_MODE ?>
                </td>
                <td><?php echo number_format($paymetdetails[$value->Mid]->TXN_AMT,2);?></td>
                <td>
                  <?= number_format(($value->LastMonthDue + $value->CurrentMonthDue + $value->LatePenalty)-$paid,2) ?>
                </td>
                <td><?= $value->Type ?></td>
                <td><?=$pay_status ?></td>
              </tr>
              
              <?php
            }
            ?>
          </table>
        </div>
        </div>
        <?php if (!is_numeric($pages))
        {
         ?>
          <div class="col-sm-12">
            <div class="col-sm-6 text-left"></div>
            <div class="col-sm-6"><?= yii\widgets\LinkPager::widget(['pagination' => $pages]);?></div>
          </div>
       <?php
        }
        ?>
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
  <script type="text/javascript">
 function show_pay(val){
  $('.all').addClass('dln');
  $('.all').hide();
  $('.'+val).show();
  $('.'+val).removeClass('dln');
    }
</script>



 <!-- $(".exportToExcel").click(function(e){
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
        }); -->










