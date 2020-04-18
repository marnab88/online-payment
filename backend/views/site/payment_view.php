use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Payment_view';

?>
<div class="site-login">
	<div class="row">
		
		
		<div class="col-lg-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Previous Records</h4>
					<table class="table table-striped table-bordered">
						<tr>
							<th>File Name</th>
							<th>Date/Time</th>
							<th>Uploaded By</th>
							<th>Month</th>
							<th>Total Due </th>
							<th>Total Collected</th>
							<th>Paid Online</th>
							<th>Action</th>
						</tr>
						<tr>
							<td>File1.xlsx</td>
							<td>Jan 20 2020 : 12:10 PM</td>
							<td>Subadmin 1</td>
							<td>Feb 2020</td>
							<td>1,064,000 </td>
							<td><span style="color:#00CC00">2,34,550</span></td>
							<td>65</td>
							<td><a href="previousrecords.html" style="float:left; margin-top:5px;">View Details</a></td>
						</tr>
						<tr>
							<td>File2.xlsx</td>
							<td>Feb 20 2020 : 11:30 PM</td>
							<td>Subadmin 2</td>
							<td>Feb 2020</td>
							<td>1,78,789 </td>
							<td><span style="color:#00CC00">1,68,580</span></td>
							<td>92</td>
							<td><a href="previousrecords.html" style="float:left; margin-top:5px;">View Details</a></td>
						</tr>
						
					</table>
					
					
				</div>
			</div>
		</div>
		
		
	</div>
	
</div>