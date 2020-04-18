use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'record_update';

?>

<div class="site-login">
	<div class="row">
		
		
		<div class="col-lg-6 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Update User Data</h4>
					
					
					<form class="forms-sample">
						<div class="form-group">
							<label for="exampleInputEmail1">Name</label>
							<input type="text" class="form-control" value="Devendra Baliarsingh">
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">Mobile No:</label>
							<input type="text" class="form-control" value="9876789876">
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">Amount:</label>
							<input type="text"class="form-control" value="1250.64 INR">
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">Due Date:</label>
							<input type="text"class="form-control" value="26/02/2020">
						</div>
						<button type="button" class="btn btn-success mr-2" onClick="location.href='details.html';">Update</button>
						<button type="button" class="btn btn-light" onClick="location.href='details.html';">Back to User List</button>
					</form>
				</div>
			</div>
		</div>
		
		
	</div>
</div>