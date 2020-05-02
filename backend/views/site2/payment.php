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
					
					<?php $form = ActiveForm::begin(['action'=>['site/paymentdetails'],'options'=>['class' => 'form-horizontal']]); ?>
					<div class="col-sm-4">
					 		<input type="text" class="form-control" value="<?= $lnacno ?>" autocomplete="off" name="loanacno" placeholder="Enter LoanAccountNo"> 
					</div>
					<div class="col-sm-2"> 
					 		<input class="btn btn-success" type="submit" value="Search" name="search" class="btn btn-success exportToExcel">
		            </div>
		            <?php ActiveForm::end(); ?>
					<!--  <div class="col-sm-2">
					 	 <?php if ($getallreport) {?>
				              <a href="<?= Url::toRoute(['site/paymentdetails','export' => 'yes']);  ?>" class="btn btn-success">Export Report</a>
				          <?php } ?>
		            </div> -->
		            
					<div class="table-responsive">
					<table class="table table-striped table-bordered" id="table_emp">
						<thead>
							<tr>
								<th>Sl. No.</th>
								<th> Customer Name</th>
								<th> EMI Amount</th>
								<th>Due Date</th>
								<th>Given Date</th>
								<th>Payment Mode</th>
								<th>Payment Status</th>
						</tr>
						<?php
						if ($getallreport) {
				              $amount=[];
				            foreach ($getallreport as $key => $value) {
				              $totamnt=($value->usermser->LastMonthDue+$value->usermser->LatePenalty+$value->usermser->CurrentMonthDue);
				              //$dueamt=$totamnt-($value->TXN_AMT);
				              if($value->TXN_STATUS = 1){
				                    if (isset($amount[$value->LOAN_ID])) {
				                      $amount[$value->LOAN_ID]=$amount[$value->LOAN_ID]+$value->TXN_AMT;
				                    }else{
				                      $amount[$value->LOAN_ID]=$value->TXN_AMT;
				                    }
				                  }else {
				                      $amount[$value->LOAN_ID] = 0;
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
				                <td><?= number_format($value->TXN_AMT,2) ?></td>
				                <td><?= (isset($value->transactionres->PG_MODE))?$value->transactionres->PG_MODE:'' ?></td>
				                <td><?= date('d-m-Y',strtotime($value->usermser->NextInstallmentDate)) ?></td>
				                <td><?= (($value->TXN_STATUS) == 1)?number_format($dueamt,2):number_format($totamnt,2) ?></td>
				                <td><?= ($value->TXN_STATUS == '1')?'success':'failure'?></td>
				                
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


