<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'uploaded_view';

?>
<div class="site-login">

	<div class="row">


		<div class="col-lg-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">File Management</h4>
					<table class="table table-striped table-bordered">
						<tr>
							<th>Download File</th>
							<th>Date/Time</th>
							<th>Uploaded By</th>
							<th>Month</th>
							<th>Number of Records </th>
							<th>Cleared</th>
							<th>Mismatch</th>
						</tr>
						<tr>
							<td>File1.xlsx</td>
							<td>Feb 20 2020 : 12:10 PM</td>
							<td>Subadmin 1</td>
							<td>Feb 2020</td>
							<td>1064 </td>
							<td><span style="color:#00CC00">1058</span></td>
							<td><span style="color:#FF3333;">6</span></td>
						</tr>

					</table>
					<a href="details.html" style="float:left;color:#fff; margin-top:5px;">View Details</a>

				</div>
			</div>
		</div>


	</div>

	<!-- content-wrapper ends -->
	<!-- partial: partials/_footer.html -->

	<!-- partial -->
</div>
<!-- row-offcanvas ends -->
</div>
<!-- page-body-wrapper ends -->
</div>	
</div>