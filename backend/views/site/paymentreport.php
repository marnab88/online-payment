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
					<h4 class="card-title">Payment Details</h4>
					 <div class="col-sm-6">
		                <input type="button" value="Export Report" class="btn btn-success exportToExcel" style="float:right;">
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
								<th>Wallet Bank Ref. No.</th>
								<th>Demand Date</th>
								<th>Demand Amount</th>
								<th>Receipt Amount</th>
								<th>Receipt Mode</th>
								<th>Next Installment Date</th>
								<th>Due Amount</th>
						</tr>
						<?php
						foreach ($getallreport as $key => $value) {
							?>
							<tr>
								<td><?= $key+1 ?></td>
								<td><?= $value['type'] ?></td>
								<td><?= $value['BranchName'] ?></td>
								<td><?= $value['MID'] ?></td>
								<td><?= $value['ClientName'] ?></td>
								
								<td><?= $value['MobileNo'] ?></td>
								<td><?= $value['LoanAccountNo'] ?></td>
								<td><?= $value['TXN_DATE'] ?></td>
								<td><?= $value['WALLET_BANK_REF'] ?></td>
								<td><?= $value['DEMAND_DATE'] ?></td>
								<td><?= $value['DEMAND_AMT']  ?></td>
								<td><?= $value['RECPT_AMT'] ?></td>
								<td><?= $value['RCPT_MODE'] ?></td>
								<td><?= $value['NXT_INST_DATE'] ?></td>
								<td><?= $value['DUE_AMT']  ?></td>
								
							</tr>
							<?php
								}
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