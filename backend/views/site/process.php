<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Payment_view';
$this->title = 'Process';
?>
<div class="site-login">
	<div class="row">


		<div class="col-lg-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Individual Records</h4>
					<div style="text-align:right;">
						Month: March 2020 &nbsp; &nbsp;  Total Due: 2,34,000 INR
					</div>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>No.</th>
								<th>User Name</th>
								<th>User ID</th>
								<th>Mobile No</th>
								<th>Amount</th>
								<th>Due Date</th>
								<th>Payment Link</th>
								<th>SMS Status</th>
								<th>Whatsapp Status</th>
								<th>Payment Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$a=1;
								foreach ($records as $key => $value) {
								
							
								 ?>
							<tr>
								<td>1</td>
								<td><?= $value->UserName ?></td>
								<td><?= $value->UserId ?></td>
								<td><?= $value->MobileNo ?></td>
								<td><?= $value->Amount ?></td>
								<td><?= $value->DueDate ?></td>
								<td><?= $value->PaymentLink ?></td>
								<td><label class="badge badge-warning"><?= $value->SmsStatus ?></label></td>
								<td><label class="badge badge-success"><?= $value->WhatsappStatus ?></label></td>
								<td><label class="badge badge-danger"><?= $value->PaymentStatus ?></label></td>
							</tr>
							
							<?php
									$a++;
								}
								?>
						</tbody>
					</table>

				</div>
			</div>
		</div>


	</div>
	
</div>