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
					
					<?php $form = ActiveForm::begin(['action'=>['site1/paymentdetails'],'options'=>['class' => 'form-horizontal']]); ?>
					<div class="col-sm-4">
					 		<input type="text" class="form-control" value="<?= $lnacno ?>" autocomplete="off" required name="loanacno" placeholder="Enter LoanAccountNo"> 
					</div>
					<div class="col-sm-2"> 
					 		<input class="btn btn-success" type="submit" value="Search" name="search" class="btn btn-success exportToExcel">
		            </div>
		            <?php ActiveForm::end(); ?>
					 <div class="col-sm-2">
					 	 <?php if ($getallreport) {?>
				              <a href="<?= Url::toRoute(['site1/paymentdetails','export' => 'yes']);  ?>" class="btn btn-success">Export Report</a>
				          <?php } ?>
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
								<th>Total Amount Paid</th>
								<th>Previously Receipt Amount</th>
								<th>Receipt Amount</th>
								<th>Receipt Mode</th>
								<th>Next Installment Date</th>
								<th>Due Amount</th>
								<th>Status</th>
						</tr>
						<?php
						if ($getallreport) {
				              $amount=[];
				            foreach ($getallreport as $key => $value) {
				            	// var_dump($value);
				              $totamnt=($value->usermser->LastMonthDue+$value->usermser->LatePenalty+$value->usermser->CurrentMonthDue);
				              //$dueamt=$totamnt-($value->TXN_AMT);
				              if($value->TXN_STATUS == 1){
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

				                  $dueamt=$totamnt-($amount[$value->LOAN_ID]);


				              ?>
				              <tr>
				                <td><?= $key+1 ?></td>
				                <td><?= $value->TYPE ?></td>
				                <td><?= $value->usermser->BranchName ?></td>
				                <td><?= $value->USER_ID ?></td>
				                <td><?= $value->usermser->ClientName ?></td>
				                <td><?= $value->usermser->MobileNo ?></td>
				                <td><?= $value->LOAN_ID ?></td>
				                <td><?= date('d-m-Y H:i:s',strtotime($value->TXN_DATE)) ?></td>
				                <td><?= (isset($value->transactionres->WALLET_BANK_REF))?$value->transactionres->WALLET_BANK_REF:'' ?></td>
				                <td><?= date('d-m-Y',strtotime($value->usermser->DemandDate)) ?></td>
				                <td><?= number_format($totamnt,2) ?></td>
				                <td><?= number_format($amount[$value->LOAN_ID],2) ?></td>
				                <td><?= number_format($pervamnt,2) ?></td>
				                <td><?= number_format($value->TXN_AMT,2) ?></td>
				                <td><?= (isset($value->transactionres->PG_MODE))?$value->transactionres->PG_MODE:'' ?></td>
				                <td><?= date('d-m-Y',strtotime($value->usermser->NextInstallmentDate)) ?></td>
				                <td><?= (($value->TXN_STATUS) == 1)?number_format($dueamt,2):number_format($totamnt,2) ?></td>
				                <td><?= ($value->TXN_STATUS == 1)?'<b>success</b>':'<b style="color:#ff0000">failed</b>' ?></td>
				                
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


