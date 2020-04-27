<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\UploadRecords;
use yii\helpers\Url;



$this->title = 'Payment Details';

?>
<div class="site-login">
	<div class="row">
		<div class="col-lg-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title col-sm-4">Payment Details</h4>
					
					<?php $form = ActiveForm::begin(['action'=>['site/paymentdetails'],'options'=>['class' => 'form-horizontal']]); ?>
					<div class="col-sm-4">
					 		<input type="text" class="form-control" value="<?= $lnacno ?>" name="loanacno" placeholder="Enter LoanAccountNo"> 
					</div>
					<div class="col-sm-2"> 
					 		<input class="btn btn-success" type="submit" value="Search" name="search" class="btn btn-success exportToExcel">
		            </div>
		            <?php ActiveForm::end(); ?>
					 <div class="col-sm-2">
					 	<?php if ($getallreport) {?>
					 		<input type="button" value="Export" class="btn btn-success exportToExcel" style="float:right;">
					 	<?php }else{'';}?>
		                
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
								<th>Receipt Amount</th>
								<th>Receipt Mode</th>
								<th>Next Installment Date</th>
								<th>Due Amount</th>
								<th>Status</th>
						</tr>
						<?php
						if ($getallreport) {
							
						foreach ($getallreport as $key => $value) {

							?>
							<tr>
								<td><?= $key+1 ?></td>
								<td><?= $value['TYPE'] ?></td>
								<td><?= $value['BranchName'] ?></td>
								<td><?= $value['mid'] ?></td>
								<td><?= $value['ClientName'] ?></td>
								<td><?= $value['MobileNo'] ?></td>
								<td><?= $value['LoanAccountNo'] ?></td>
								<td><?= $value['TXN_DATE'] ?></td>
								<td><?= ($value['WALLET_BANK_REF']!='')?$value['WALLET_BANK_REF']:'' ?></td>
								<td><?= $value['DEMAND_DATE'] ?></td>
								<td><?= $value['DEMAND_AMT']  ?></td>
								<td><?= $value['RECPT_AMT'] ?></td>
								<td><?= $value['RCPT_MODE'] ?></td>
								<td><?= $value['NXT_INST_DATE'] ?></td>
								<td><?= $value['DUE_AMT']  ?></td>
								<td><?= ($value['WALLET_BANK_REF']!='')?'<b>Success</b>':'<b style="color:red">Failed</b>' ?></td>
								
							</tr>
							<?php
								}}else{?>
									<tr><td colspan="16">Record Not Found</td></tr>

							<?php	}
								?>
						</tbody>
					</table>
					</div>
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
              name: "payment",
              filename: "paymentreceipt" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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