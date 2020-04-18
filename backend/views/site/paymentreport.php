<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\UploadRecords;
use yii\helpers\Url;



$this->title = 'Payment Report';

?>
<div class="site-login">
	<div class="row">
		<div class="col-lg-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Payment Report</h4>
					<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
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
				              <th>ProductVertical</th>
				              <th>Payment Amount</th>
							
								
						
							
						</tr>
						<?php
						
						foreach ($getallreport as $key => $value) {
							
							
							?>
							<tr>
								<td><?= $value->Type ?></td>
								<td><?= date('d-m-Y',strtotime($value->OnDate))  ?></td>
								<td><?= ($value->user)?$value->user->UserName:''; ?></td>
								<td><?= $value->MonthYear  ?></td>
								<td><?php
								if ($value->Type == 'MFI') {?>
									<span style="color:#FF3333;"><?=$value->Count?></span>
								 <?php	
								 } 
								 else{
								 	?>

									<span style="color:#FF3333;"><?=$value->Count?></span>
								 <?php	
								 }
								  ?></td>
								<td><span style="color:#00CC00"><?=$value->Count-($value->Mismatch+$value->MobileCount) ?></span></td>
								<td><?php
								if ($value->Type == 'MFI') {?>
								<span style="color:#FF3333;"><?=$value->Mismatch+$value->MobileCount?></span>

								<?php
									}
								else{
									?>
									<span style="color:#FF3333;"><?=$value->Mismatch+$value->MobileCount?></span>

								
								<?php
								}
								?>
									
								
								
								</tr>
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