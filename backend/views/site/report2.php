<?php

use yii\helpers\Html;

use yii\bootstrap\ActiveForm;
use common\models\UploadRecords;
use yii\helpers\Url;
use kartik\date\DatePicker;

//use yii\widgets\ActiveForm;
$this->title = 'Report';

?>
<style>
.report label{
  display: block;
  font-weight: 100;
}
.report .col-sm-3 {
    float: left;
}
.has-success .help-block, .has-success .control-label, .has-success .radio, .has-success .checkbox, .has-success .radio-inline, .has-success .checkbox-inline, .has-success.radio label, .has-success.checkbox label, .has-success.radio-inline label, .has-success.checkbox-inline label {
    color: #fff;
}
.pagination {
    float: right;
}

</style>
<div class="site-login">
    <div class="col-lg-12 grid-margin stretch-card report" style="padding-left:0px;" >
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Transaction history Report</h4>
          <?php $form = ActiveForm::begin(['id' => 'report_form']); ?>
          <div class="row">
           <div class="col-sm-3">
            <label>Type</label>
            <select class="form-control" name="type">
              <option value="">---Select Type---</option>
              <option value="MSME" <?= ($type == 'MSME')?'selected':''; ?>>MSME</option>
              <option value="MFI" <?= ($type == 'MFI')?'selected':''; ?>>MFI</option>
            </select>
           </div>
           <div class="col-sm-3" style="padding-top:10px;">
            <?= $form->field($model, 'BranchName')->dropDownList($branchnnm,['label'=>'Branch Name','prompt'=>'---Select Branch---']
          ) ?>
           
           </div>
           <div class="col-sm-3">
            <label>From Date</label>
              <input type="text" name="fromdate" id="datepicker" placeholder="dd-mm-yy" class="form-control" value="<?=date('01-m-Y')?>" autocomplete="off">
            </div>
            <div class="col-sm-3">
              <label>To Date</label>
              <input type="text" name="todate" id="datepicker1" placeholder="dd-mm-yy" class="form-control" value="<?=date('d-m-Y')?>" autocomplete="off">
             </div>
            </div>
           <div class="row">


             <div class="col-sm-3" style="padding-top: 34px;">
              <button type="submit" class="btn btn-success" name="report_search">Search</button>
             </div>
           </div>
           
          

           <?php ActiveForm::end(); ?>
        </div>
      </div>
    </div>
    <?php
if($allcustomer){
?>
    <div class="col-lg-12 grid-margin stretch-card" style="padding-left:0px;" >
      <div class="card">
        <div class="card-body">
          <h4 class="card-title col-sm-6">Report Details</h4>
          <div class="col-sm-3 text-right">
                <a href="<?= Url::toRoute(['site/cust-payment','branch' => '','fromdt' =>$fromdate,'todt' =>$todate,'export' => 'yes']);  ?>" class="btn btn-success">Export All</a>
            </div>
          <div class="col-sm-3 text-right">
                <a href="<?= Url::toRoute(['site/report2','export' => 'yes']);  ?>" class="btn btn-success">Export Summery</a>
            </div>
          <div class="table-responsive">
          <table class="table table-striped table-bordered" id="table_emp">
            <tr>
             <th>Sl. no.</th>
               <th>State</th>
              <th>Cluster</th>
              <th>Branch</th>
              <th>Type</th>
              <th>Month</th>
              <th>Amount Collected</th>
              <th>No. of Customers</th>
              <th>Customers Paid</th>
            </tr>
            <tbody>
              <?php foreach ($allcustomer as $key => $value) {?>
              <tr>
              <td><?= $key+1 ?></td>
              <td><?= $value->State ?></td>
              <td><?= $value->Cluster ?></td>
              <td><?= $value->BranchName ?></td>
              <td><?= $value->ProductVertical ?></td>
              <td><?= date('M-Y', strtotime($value->DemandDate)) ?></td>  
              <td><?=$branchpayment[$value->BranchName]->amountcollected?></td>
              <td>
                <a href="<?= Url::toRoute(['site/cust-payment','branch' => $value->BranchName,'fromdt' =>$fromdate,'todt' =>$todate,'pagination'=>'true']);  ?>"><?= $value->totalcustomer ?></a>
              </td>
              <td><?=$branchpayment[$value->BranchName]->customerpaid?></td>
              <tr>
              <?php }?>
            </tbody>
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
<?php } ?>



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



    $( document ).ready(function() {
        $("#datepicker").datepicker({
            maxDate: 0,
            dateFormat: "dd-mm-yy"
        });
        $("#datepicker1").datepicker({
            maxDate: 0,
            dateFormat: "dd-mm-yy"
        });
    });
');
  ?>