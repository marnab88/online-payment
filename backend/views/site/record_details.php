<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Record';

?>
<div class="site-login">
	<div class="row">


		<div class="col-lg-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Individual Records</h4>
					<div style="color:#fff; text-align:right;">
						<a href="process.html" style="color:#fff;" >Approve Records - Initiate Process</a>
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
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Devendra Baliarsingh</td>
								<td>UID001</td>
								<td>9876789876</td>
								<td>1250.64 INR</td>
								<td>26/02/2020</td>
								<td><a href="update.html">UPDATE</a></td>
							</tr>
							<tr>
								<td>2</td>
								<td>Santosh Rout</td>
								<td>UID002</td>
								<td>9875624224</td>
								<td>1150.00 INR</td>
								<td>25/02/2020</td>
								<td>UPDATE</td>
							</tr>
							<tr style="background:#ed6d6d;">
								<td>3</td>
								<td>Vivek Patnaik</td>
								<td>UID003</td>
								<td>982342542</td>
								<td>1000.00 INR</td>
								<td>23/02/2020</td>
								<td>UPDATE</td>
							</tr>
							<tr>
								<td>4</td>
								<td>Karan Patel</td>
								<td>UID004</td>
								<td>986323413</td>
								<td>950.00 INR</td>
								<td>24/02/2020</td>
								<td>UPDATE</td>
							</tr>
							<tr>
								<td>5</td>
								<td>Arjun Baldev</td>
								<td>UID005</td>
								<td>9833143446</td>
								<td>1150.64 INR</td>
								<td>21/02/2020</td>
								<td>UPDATE</td>
							</tr>
							<tr>
								<td>6</td>
								<td>Manoj Sethi</td>
								<td>UID006</td>
								<td>7764452423</td>
								<td>1500.64 INR</td>
								<td>16/02/2020</td>
								<td>UPDATE</td>
							</tr>
							<tr>
								<td>7</td>
								<td>Gurudev Pandey</td>
								<td>UID007</td>
								<td>7672342343</td>
								<td>900.00 INR</td>
								<td>11/02/2020</td>
								<td>UPDATE</td>
							</tr>
							<tr style="background:#ed6d6d;">
								<td>8</td>
								<td>Kailash Agarwal</td>
								<td>UID008</td>
								<td>754545254</td>
								<td>1300.00 INR</td>
								<td>05/01/2020</td>
								<td>UPDATE</td>
							</tr>
							<tr>
								<td>9</td>
								<td>Nilesh Mohanty</td>
								<td>UID009</td>
								<td>9945445254</td>
								<td>1700.00 INR</td>
								<td>15/01/2020</td>
								<td>UPDATE</td>
							</tr>

						</tbody>
					</table>

				</div>
			</div>
		</div>


	</div>
</div>