<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */

$this->title = 'Home';
 	
?>
<style type="text/css">
/* Chrome, Safari, Edge, Opera */
#amount::-webkit-outer-spin-button,
#amount::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
#mob::-webkit-outer-spin-button,
#mob::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
<div class="site-index">
	<div class="container-fluid">
		<div class="">
			<div class="row align-items-center">
				<div class="col-md-5 page" style=" padding-top:50px;margin-top: 0px;margin-bottom: 0px;" >
					<p><img src="<?= Yii::getAlias('@frontendUrl'); ?>/images/logo.png" height="70" /></p>
					<h3 style="margin-bottom: 30px;">Change Mobile Number</h3>
					<div class="box">
						<div class="row">
						<?php $form = ActiveForm::begin(['action'=>['site/changemobile'],'options'=>['class' => 'form-horizontal']]); ?>

						<div class="form-group">
							
							<div class="col-sm-12">
								<input type="number" onkeypress="return isNumber(event)"  name="mob" id="mob" class="form-control" placeholder="New Mobile Number">
							</div>
						</div>

						<div class="form-group otpsuccess" style="margin-top:15px !important;display: none;">
							
							<div class="col-sm-12">
								<input type="text" name="otp" id="otp" onkeypress="return isNumber(event)" class="form-control onlynumber" placeholder="OTP">
							</div>
							<div class="form-group">
							<div class="col-sm-offset-4 col-sm-7" style="margin-top:30px;">
								
								<button type="submit" class="btn btn-primary" id="newmob" name="submit" style="width:150px;">Submit</button>
							</div>
						</div>
						</div>
						<div class="form-group">
						<div class="col-sm-offset-4 col-sm-7" style="margin-top:30px;">
						<button type="button" class="btn btn-primary" id="newotp"  onclick="gotp();">Generate OTP</button>
						</div>
					</div>					
					<?php ActiveForm::end(); ?>
					<div class="row  col-sm-offset-4 col-sm-7 col-xs-12" style="margin-top:20px;">
							<a href="<?= Url::to(['site/home']);  ?>" data-method="post"><button type="submit" class="btn btn-success"  style="width:150px;"><i class="fa fa-home"></i> Home</button></a>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

						
						
						
				
	
</div>
</div>

  

<script type="text/javascript">
  
  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
	function gotp() {
		var mobileno = $('#mob').val();
		// alert(mobileno);
		$.ajax({
			url: "<?= Url::toRoute(['site/generateotp']); ?>?mobileno=" + mobileno,
			success: function(results) {
				if (results == 0) {
					alert("Wrong mobile no");
				} else {
					$('.otpsuccess').show();
					$('#newotp').hide();
					$('#newmob').show();
				}
			}
		});
    
	}
</script>