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
						<h4 class="card-title">Edit Subadmin</h4>

						<?php $form = ActiveForm::begin(['options' => ['class' => 'forms-sample','enctype' => 'multipart/form-data']]) ?>
							<div class="form-group">
								<!-- <label for="exampleInputEmail1">Name</label> -->
								<?= $form->field($model, 'UserName')->textInput(['maxlength' => true]) ?>
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

</div>
<!-- <script type="text/javascript">
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

 -->