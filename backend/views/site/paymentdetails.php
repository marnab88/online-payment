<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\UploadRecords;
use yii\helpers\Url;



$this->title = 'Payment Details';

?>
<style type="text/css">
.pagination {
    float: right;
}
.report label{
  display: block;
  font-weight: 100;
}
</style>
<div class="site-login">

	<div class="col-lg-12 grid-margin stretch-card report" style="padding-left:0px;" >
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Payment Details Report</h4>
          <?php $form = ActiveForm::begin(['id' => 'report_form','method'=>'get']); ?>
          <div class="row">
           <div class="col-sm-3">
           	 <label>Loan Account Number</label>
				<input type="text" class="form-control" value="<?= $lnacno ?>" autocomplete="off" name="loanacno" placeholder="Enter LoanAccountNo"> 
           </div>
           <div class="col-sm-3" class="form-control">
           	 <label>Status</label>
            <select name="status" class="form-control">
            	<option value="">-- Select Status--</option>
            	<option value="1" <?= ($status == '1')?'selected':''?>>Success</option>
            	<option value="0" <?= ($status == '0')?'selected':''?>>Failed</option>
            </select>
           
           </div>
           <div class="col-sm-3">
            <label>From Date</label>
              <input type="text" name="fromdate" id="datepicker" placeholder="dd-mm-yy" class="form-control" value="<?= $frmdate ?>" autocomplete="off">
            </div>
            <div class="col-sm-3">
              <label>To Date</label>
              <input type="text" name="todate" id="datepicker1" placeholder="dd-mm-yy" class="form-control" value="<?= $tdate ?>" autocomplete="off">
             </div>
            </div>
           <div class="row">


             <div class="col-sm-3" style="padding-top: 34px;">
              <input class="btn btn-success" type="submit" value="Search" name="search" class="btn btn-success">
             </div>
           </div>
           
          

           <?php ActiveForm::end(); ?>
        </div>
      </div>
    </div>







<?php if ($getallreport) {?>
	<div class="row">
		<div class="col-lg-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title col-sm-10">Report Details</h4>
					 <div class="col-sm-2 text-right">
				              <a href="<?= Url::toRoute(['site/paymentdetails','status'=>$status,'loanacno' => $lnacno,'fromdate' =>$fromdate,'todate' =>$todate,'export' => 'yes']);  ?>" class="btn btn-success">Export Report</a>
				          
		            </div>
		            
					<div class="table-responsive">
					<table class="table table-striped table-bordered" id="table_emp">
						<thead>
							<tr>
								<th>Sl. No.</th>
								<th>Type</th>
								<th>Branch Name</th>
								<th>User Id</th>
								<th>Client Name</th>
								<th>Mobile No</th>
								<th>Loan Account No.</th>
								<th>Transaction Date</th>
								<th>Bank Ref. No.</th>
								<th>Demand Date</th>
								<th>Demand Amount</th>
								<th>Previously Received Amount</th>
								<th>Receipt Amount</th>
								<th>Receipt Mode</th>
								<th>Next Installment Date</th>
								<th>Due Amount</th>
								<th>Status</th>
						</tr>
						<?php
						if ($getallreport) {
				              $amount=[];
				              $previouslypaid=[];
				            foreach ($getallreport as $key => $value) {
				            	// var_dump($value);
				              $totamnt=($value->usermser->LastMonthDue+$value->usermser->LatePenalty+$value->usermser->CurrentMonthDue);
				              //$dueamt=$totamnt-($value->TXN_AMT);
				              /*if($value->TXN_STATUS == 1){
				                    if (isset($amount[$value->LOAN_ID])) {
				                      $amount[$value->LOAN_ID]=$amount[$value->LOAN_ID]+$value->TXN_AMT;
				                    }else{
				                      $amount[$value->LOAN_ID]=$value->TXN_AMT;
				                    }
				                    $pervamnt=$amount[$value->LOAN_ID]-($value->TXN_AMT);
				                  }else {
				                      $amount[$value->LOAN_ID] = (isset($amount[$value->LOAN_ID]))?$amount[$value->LOAN_ID]:0;
				                      $pervamnt=0;
				                  }

				                  $dueamt=$totamnt-($amount[$value->LOAN_ID]);*/

				                  if(!isset($previouslypaid[$value->LOAN_ID]) )
					                  $previouslypaid[$value->LOAN_ID]=0;
					               
					                $dueamt=$totamnt-($previouslypaid[$value->LOAN_ID]);


				              ?>
				              <tr>
				                <td><?= $key+1 ?></td>
				                <td><?= $value->TYPE ?></td>
				                <td><?= $value->usermser->BranchName ?></td>
				                <td><?= $value->USER_ID ?></td>
				                <td><?= $value->usermser->ClientName ?></td>
				                <td><?= $value->MOB_NO ?></td>
				                <td><?= $value->LOAN_ID ?></td>
				                <td><?= date('d-m-Y H:i:s',strtotime($value->TXN_DATE)) ?></td>
				                <td><?= (isset($value->transactionres->WALLET_BANK_REF))?$value->transactionres->WALLET_BANK_REF:'' ?></td>
				                <td><?= date('d-m-Y',strtotime($value->usermser->DemandDate)) ?></td>
				                <td><?= number_format($totamnt,2) ?></td>
				                <td><?= number_format($previouslypaid[$value->LOAN_ID],2); ?></td>
				                <td><?= number_format($value->TXN_AMT,2) ?></td>
				                <td><?= (isset($value->transactionres->PG_MODE))?$value->transactionres->PG_MODE:'' ?></td>
				                <td><?= date('d-m-Y',strtotime($value->usermser->NextInstallmentDate)) ?></td>
				                <td><?= number_format($dueamt,2) ?></td>
				                <td><?= ($value->TXN_STATUS == 1)?'<b>success</b>':'<b style="color:#ff0000">failed</b>' ?></td>
				                <?php if($value->TXN_STATUS==1){
					                $previouslypaid[$value->LOAN_ID]+=$value->TXN_AMT;
					               }?>
				              </tr>
				              <?php
				                }}else{?>
				                  <tr><td colspan="16">Record Not Found</td></tr>

				              <?php  }
												?>
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
	
</div>

<?php } ?>


<?php
    $this->registerJs('
   
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
