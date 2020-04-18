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
						<h4 class="card-title">Reset Password For Subadmin</h4>

						<?php $form = ActiveForm::begin(['options' => ['class' => 'forms-sample','enctype' => 'multipart/form-data']]) ?>
							<div class="form-group">
								<!-- <label for="exampleInputEmail1">Name</label> -->
								<label for="exampleInputEmail1">Select Subadmin</label>
								<select class="form-control" name="subadd">
									<option value=""> Select Subadmin</option>
									<?php
										foreach ($subadmin as $key => $value) { ?>
											<option value="<?=$value->UserId?>"> <?=$value->UserName?></option>
									<?php	}
									?>
								</select>
							</div>
							<div class="form-group">
								<!-- <label for="exampleInputEmail1">Login ID</label> -->
								<label for="exampleInputEmail1">New Password</label>
								<input type="password" name="npsw" class="form-control">
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Confirm Password</label>
								<input type="password" name="cpsw" class="form-control">
							</div>

							
							
							<!-- <label id="msg" text=""></label> -->
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
