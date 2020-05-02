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
</style>
<div class="site-login">
	<div class="row">
		<div class="col-lg-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title col-sm-4">Payment Details</h4>
					
					<?php $form = ActiveForm::begin(['action'=>['site2/agentdata'],'options'=>['class' => 'form-horizontal']]); ?>
					<div class="col-sm-4">
					 		<input type="text" class="form-control"  autocomplete="off" required name="loanacno" placeholder="Enter LoanAccountNo"> 
					</div>
					<div class="col-sm-2"> 
					 		<input class="btn btn-success" type="submit" value="Search" name="search" class="btn btn-success exportToExcel">
		            </div>
		            <?php ActiveForm::end(); ?>
					
					<div class="table-responsive">
					<table class="table table-striped table-bordered" id="table_emp">
						<tbody>
						<thead>
								<tr>
								<th>Sl. No.</th>
								<th>Type</th>
								<th>Branch Name</th>
								<th>Client Name</th>
								<th>Mobile No</th>
								<th>Loan Account No.</th>
								<th>Transaction Date</th>
								<th>Demand Date</th>
								<th>Demand Amount</th>
								<th>Receipt Amount</th>
								<th>Receipt Mode</th>
								<th>Due Amount</th>
								<th>Status</th>
								</tr>
								<?php if ($getpayment) {
									 $amount=[];
				              $previouslypaid=[];
									 foreach ($getpayment as $key => $value) {	
									  $totamnt=($value->usermser->LastMonthDue+$value->usermser->LatePenalty+$value->usermser->CurrentMonthDue);

				                  if(!isset($previouslypaid[$value->LOAN_ID]) )
					                  $previouslypaid[$value->LOAN_ID]=0;
					               
					                $dueamt=$totamnt-($previouslypaid[$value->LOAN_ID]);								
									?>
									
								
								<tr>
				                <td><?= $key+1 ?></td>
				                <td><?= $value->TYPE ?></td>
				                <td><?=  $value->usermser->BranchName ?></td>
				                <td><?= $value->usermser->ClientName ?></td>
				                <td><?= $value->usermser->MobileNo ?></td>
				                <td><?= $value->LOAN_ID ?></td>
				                <td><?= date('d-m-Y',strtotime($value->TXN_DATE)) ?></td>
				                <td><?= date('d-m-Y',strtotime($value->usermser->DemandDate)) ?></td>
				                <td><?= number_format($totamnt,2) ?></td>
				                <td><?= number_format($value->TXN_AMT,2) ?></td>
				                <td><?= (isset($value->transactionres->PG_MODE))?$value->transactionres->PG_MODE:'' ?></td>
				                <td><?= number_format($dueamt,2) ?></td>
				                <td><?= ($value->TXN_STATUS == 1)?'<b>success</b>':'<b style="color:#ff0000">failed</b>' ?></td>
				              </tr>
								<?php }} ?>
				              			</thead>
										</tbody>
									</table>
									</div>
								</div>
				
			</div>
		</div>
		
		
	</div>
	
</div>


