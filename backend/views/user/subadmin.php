<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Subadmin';

?>
<div class="site-login">

		<div class="row">


			<div class="col-lg-6 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Create Subadmin</h4>

						<?php $form = ActiveForm::begin(['options' => ['class' => 'forms-sample','enctype' => 'multipart/form-data']]) ?>
							<div class="form-group">
								<!-- <label for="exampleInputEmail1">Name</label> -->
								<?= $form->field($model, 'UserName')->textInput(['maxlength' => true]) ?>
							</div>
							<div class="form-group">
								<!-- <label for="exampleInputEmail1">Login ID</label> -->
								<?= $form->field($model, 'Password')->passwordInput(['maxlength' => true]) ?>
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Confirm Password</label>
								<input type="Password" id='txtConfirmPassword' name="confirm" class="form-control" onblur="check(this.value)" required>
							</div>
							
							<div class="form-group">
								<?= $form->field($model, 'Type')->dropDownList(
								$type,  ['prompt'=>'Select File Type']); ?>

							</div>

							<div class="form-group">
								<?= $form->field($model, 'ViewReports')->checkbox(['value' => "1"]);  ?>
								<?= $form->field($model, 'UploadRecords')->checkbox(['value' => "1"]);  ?>

							</div>
							
							
							
							<label id="msg" text=""></label>
							<div id="show" >
							<?= Html::submitButton('Save', ['class' => 'btn btn-success'])  ?>
							<!-- <button class="btn btn-light">Cancel</button> -->
						</div>
							<?php ActiveForm::end() ?>
					</div>
				</div>
			</div>


		</div>

		<div class="row">


			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">View List Of Subadmin</h4>
						<!-- <div style="color:#fff; text-align:right;">
							<a href="/onlineportal/backend/site/process" style="color:#fff;" >Approve Records - Initiate Process</a>
						</div> -->
						<table class="table table-striped table-bordered">
							<thead>

								<tr>
									<th>No.</th>
									<th>User Name</th>
									<th>Login ID</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$a=1;
								foreach ($details as $key => $value) {
								
							
								 ?>
								<tr>
									<td><?= $a ?></td>
									<td><?= $value->UserName ?></td>
									<td><?= $value->LoginId ?></td>
									<td><a href="<?= Url::toRoute(['user/delete','id' => $value->UserId]);  ?>" data-method="post" data-confirm="Are you sure to delete the account"><i class="mdi mdi-delete"></i></a>
										<a href="<?= Url::toRoute(['user/edit','id' => $value->UserId]);  ?>" data-method="post" ><i class="mdi mdi-pencil"></i></a></td>
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
<script type="text/javascript">
	function check(value){
		var psw = $("#user-password").val();

		if(psw != value){

			$("#msg").text("password does not match");
			$("#show").css("display","none");

		}

		else{

			$("#show").css("display","block");
			$("#msg").text("");
		}
	}
</script>
