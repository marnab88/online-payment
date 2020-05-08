<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;

$this->title = 'Login';

?>
<style>
	.form-horizontal .form-group{ margin: 5px 0px 0px 0px !important; margin-top:0px !important;margin-bottom: 8px !important;}
	input#mobilenum {
    background: #fff;box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
input#loanpaymentform-loanaccno {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
p.nrm {
    font-size: 14px;
    color: #8e8888;
    line-height: normal;
    margin-top: 10px;
}
.nav-item a{
background: transparent;
}
p.tl {
    margin-bottom: 0px;
    padding-bottom: 0px;
    font-size: 25px;
    line-height: normal;
    font-weight: 600;color: #565292;
    /* float: left; */
    /* width: 100%; */
}
.nav-tabs > li {
    width: 50%; border: 0px;text-align: center; margin-bottom: 0px;
}
/*.nav-tabs > li > a.active {
    background: #e45f11;
    color: #fff;
    border-radius: 0px;
    border: #e45f11;
   padding: 10px 15px;
    font-weight: 600;
    font-size: 20px;
    border: 0px solid #ddd;
    

}*/
.nav-tabs > li > a:hover{background-color: #f27024; color: #fff; padding: 10px 15px;}
.nav-tabs > li > a  {

    font-weight: 600; color: #e45f11;   padding: 10px 15px;
    font-size: 20px; padding-bottom: 0px;
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus{ border: 0px solid #ddd; border-bottom-color: 0px solid #ddd;background-color: #f27024; color: #fff;    padding: 10px 15px;}
.nav-tabs {
    border-bottom: 1px solid #f27024;}
input {
    border: none !important;
    background: transparent !important;
}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
label{font-weight:normal; opacity:0.6; color:#000;}
</style>

<div class="site-login">
	<div class="container-fluid">
			<div class="row align-items-center">
				<div class="col-md-6 mx-auto page" style=" padding-top:50px;margin-top: 0px;margin-bottom: 0px;background-size: contain;" >
					<p class="llg"><img src="<?= Yii::getAlias('@frontendUrl'); ?>/images/logo.png" height="70" /></p>
					<p class="tl"> Welcome to Loan Repayment Portal </p>
					<p class="nrm" style="margin-top:12px; margin-bottom:20px;">You Can Pay your Annapurna Finance Loan   EMI/Overdue Using UPI / Debit Card/ Internet Banking/Wallet Facility Offered By your bank .</p>

					<ul class="nav nav-tabs" role="tablist">
					  <li class="nav-item active">
					    <a class="nav-link active" href="#profile" role="tab" data-toggle="tab"> <i class="fa fa-money" aria-hidden="true"></i> Loan Account No</a>
					  </li>
					   <li class="nav-item">
					    <a class="nav-link" href="#buzz" role="tab" data-toggle="tab"> <i class="fa fa-mobile" aria-hidden="true"></i> Mobile No</a>
					  </li> 
					  
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<!-- for Loan Account login start -->
					  <div role="tabpanel" class="tab-pane fade in active" id="profile"> 
					  	<div class="formbox" style="margin-top:20px;">
							<?php
							if(isset($recordDetail->ClientName)){
							?>
							<p style="padding:0px; font-weight:bold; color:#000; text-align:left;">Welcome <?=$recordDetail->ClientName?></p>
							<?php }
							?>
						<?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'form-horizontal']]); ?>
						<div class="form-group">
							<div  class="row">
								<div class="col-md-10 col-xs-10">
								 <label>Loan Account No</label>
								<?php
								$hide='none';
								 if(isset($recordDetail->LoanAccountNo)){
									$hide='block';
									?>
									<?= $form->field($model, 'loanaccno')->textInput(['value'=>isset($recordDetail->LoanAccountNo)?$recordDetail->LoanAccountNo:'','placeholder'=>'Loan a/c Number ','readonly'=>'readonly'])->label(false) ?>
								<?php }else{?>
									<?= $form->field($model, 'loanaccno')->textInput(['value'=>isset($recordDetail->LoanAccountNo)?$recordDetail->LoanAccountNo:'','placeholder'=>'Loan a/c Number / last 6 digits of your loan a/c number', 'autocomplete'=>'off', 'onblur'=>'fetchmob()'])->label(false) ?>
								<?php }?>
								 </div>
								 <div class="col-md-2 col-xs-2">
									<div class="arow">
										<img src="<?= Yii::getAlias('@frontendUrl'); ?>/images/proceed.png" style="margin-top:30px;" />
										<p id="display_info" style="color:red;text-align:left;"></p>
									</div>
								</div>
								<div class="col-md-12">

								</div>
							</div>
						</div>
						<div class="form-group">
							<div  class="row">
								<div class="col-sm-12">
									<label id="mobl" style="display: none" >Mobile No</label>
									<?= $form->field($model, 'maskmobileno')->textInput(['id'=>'mobilenum','style'=>"display:$hide",'name'=>'mobilenum','value'=>isset($recordDetail->MobileNo)? str_repeat("X", strlen($recordDetail->MobileNo)-4) . substr($recordDetail->MobileNo, -4):'','type' => 'tel','placeholder'=>'Mobile No', 'readonly'=>isset($recordDetail->MobileNo)?'readonly':''])->label(false) ?>
									<p id="mob_info" style="color:red;text-align:left;"></p>
								</div>
							</div>
						</div>
						<?php if(isset($recordDetail->MobileNo)){?>
							<div class="form-group" id="generatebttn">
								<div class="col-sm-offset-6 col-sm-6">
									<button type="button" class="btn btn-primary" id="otpgen" onclick="generateotp(); $(this).html('Resend OTP')">Generate OTP</button>
								</div>
							</div>
						<?php }else{?>
							<div class="form-group" id="generatebttn" style="display:none;">
								<div  class="row">
									<div class="col-sm-offset-6 col-sm-6">
										<button type="button" class="btn btn-primary" id="otpgen" onclick="generateotp();  $(this).html('Resend OTP')">Generate OTP</button>
									</div>
								</div>
							</div>
						<?php } ?>
						<p id="displaymsg" style="display:none;">OTP Sent !</p>
						
						<div class="form-group otpsuccess" style="margin-top:15px !important;display: none;">
							<div class="row">
							<label class="control-label col-sm-4 col-xs-12" for="pwd">OTP:</label>
							<div class="col-sm-2 col-xs-3">
								<input type="number" maxlength="1" onkeypress="return isNumber(event)" class="form-control inputs onlynumber" autocomplete="off" id="pwd" placeholder="" name="otp[]" required="required">
							</div>
							<div class="col-sm-2 col-xs-3">
								<input type="number" maxlength="1" onkeypress="return isNumber(event)" class="form-control inputs onlynumber" autocomplete="off" id="pwd" placeholder="" name="otp[]" required="required">
							</div>
							<div class="col-sm-2 col-xs-3">
								<input type="number" maxlength="1" onkeypress="return isNumber(event)" class="form-control inputs onlynumber" autocomplete="off" id="pwd" placeholder="" name="otp[]" required="required">
							</div>
							<div class="col-sm-2 col-xs-3">
								<input type="number" maxlength="1" onkeypress="return isNumber(event)" class="form-control inputs onlynumber" autocomplete="off" id="pwd" placeholder="" name="otp[]" required="required">
							</div>
						</div>
						</div>
						<div class="form-group otpsuccess" style="margin-bottom:30px;display: none;">

							<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
								'template' => '<div class="row"><div class="col-sm-4" id="newmf">{image}<a href="#" id="fox" title="refresh capcha"><i class="fa fa-refresh" aria-hidden="true"></i></a></div><div class="col-sm-8">{input}</div></div>'
							]) ?>
						</div>
						<div class="form-group otpsuccess" style="display: none;">
							<div class="row">

							<div class="col-sm-offset-5 col-sm-7">
								<button type="submit" name="authenticate" class="btn btn-success">Authenticate/Submit</button>
							</div>
						</div>
						</div>
						<?php ActiveForm::end(); ?>
					</div>

   				</div>
				   <!-- for Loan Account login end -->

				   <!-- for mobile login start -->
				  <div role="tabpanel" class="tab-pane fade" id="buzz">
					  	<div class="formbox" style="margin-top:20px;">
							<?php
							if(isset($recordDetail->ClientName)){
								?>
							<p style="padding:0px; font-weight:bold; color:#000; text-align:left;">Welcome <?=$recordDetail->ClientName?></p>
						<?php }
						?>
						<form id="login-formm" method="post" action="<?= Url::toRoute(['site1/login']); ?>">
						
						<div class="form-group">
							<div  class="row">
								<div class="col-md-10 col-xs-10">
								 	<label>Mobile No</label>
									<?php
									$hide='none';
									 if(isset($recordDetail->MobileNo)){
										$hide='block';
										?>
										<?= $form->field($model, 'MobileNo')->textInput([ 'type'=>'number','name'=>'mobnum','id'=>'mobnum','value'=>isset($recordDetail->MobileNo)?$recordDetail->MobileNo:'','placeholder'=>'Mobile Number ','readonly'=>'readonly'])->label(false) ?>
									<?php }else{?>
										<?= $form->field($model, 'MobileNo')->textInput(['type'=>'number','name'=>'mobnum','id'=>'mobnum','value'=>isset($recordDetail->MobileNo)?$recordDetail->MobileNo:'','placeholder'=>'Mobile Number', 'autocomplete'=>'off', 'onblur'=>'fetchloan()'])->label(false) ?>
									<?php }?>
								 </div>
								 <div class="col-md-2 col-xs-2">
								 	<div class="arow">
										<img src="<?= Yii::getAlias('@frontendUrl'); ?>/images/proceed.png" style="margin-top:30px;" />
										<!-- <p id="display_info" style="color:red;text-align:left;"></p> -->
									</div>
								</div>
								<div class="col-md-12">

								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12">
									<label id="loanlabel" style="display: none" >Loan Account No</label>
									<?= $form->field($model, 'loanaccno')->textInput(['id'=>'loannum','style'=>"display:$hide",'placeholder'=>'LoanAccount No', 'readonly'=>isset($recordDetail->LoanAccountNo)?'readonly':''])->label(false) ?>
								</div>
							</div>
						</div>
						<?php if(isset($recordDetail->LoanAccountNo)){?>
							<div class="form-group" id="lgeneratebttn">
								<div class="col-sm-offset-6 col-sm-6">
									<button type="button" class="btn btn-primary" id="lotpgen" onclick="lgenerateotp(); $(this).html('Resend OTP')">Generate OTP</button>
								</div>
							</div>
						<?php }else{?>
							<div class="form-group" id="lgeneratebttn" style="display:none;">
								<div  class="row">
									<div class="col-sm-offset-6 col-sm-6">
										<button type="button" class="btn btn-primary" id="lotpgen" onclick="lgenerateotp();  $(this).html('Resend OTP')">Generate OTP</button>
									</div>
								</div>
							</div>
						<?php } ?>
						<p id="ldisplaymsg" style="display:none;">OTP Sent !</p>
						
						<div class="form-group lotpsuccess" style="margin-top:15px !important;display: none;">
							<div class="row">
							<label class="control-label col-sm-4 col-xs-12" for="pwd">OTP:</label>
							<div class="col-sm-2 col-xs-3">
								<input type="number" maxlength="1" onkeypress="return isNumber(event)" class="form-control inputs onlynumber" autocomplete="off" id="pwd" placeholder="" name="otp[]" required="required">
							</div>
							<div class="col-sm-2 col-xs-3">
								<input type="number" maxlength="1" onkeypress="return isNumber(event)" class="form-control inputs onlynumber" autocomplete="off" id="pwd" placeholder="" name="otp[]" required="required">
							</div>
							<div class="col-sm-2 col-xs-3">
								<input type="number" maxlength="1" onkeypress="return isNumber(event)" class="form-control inputs onlynumber" autocomplete="off" id="pwd" placeholder="" name="otp[]" required="required">
							</div>
							<div class="col-sm-2 col-xs-3">
								<input type="number" maxlength="1" onkeypress="return isNumber(event)" class="form-control inputs onlynumber" autocomplete="off" id="pwd" placeholder="" name="otp[]" required="required">
							</div>
						</div>
						</div>
						<div class="form-group lotpsuccess" style="margin-bottom:30px;display: none;">
							<?= Captcha::widget([
                'name' => 'ContactForm[verifyCode]',
            ]); ?>
						
						</div>
						<div class="form-group lotpsuccess" style="display: none;">
							<div class="row">

							<div class="col-sm-offset-5 col-sm-7">
								<button type="submit" name="authenticate" class="btn btn-success">Authenticate/Submit</button>
							</div>
						</div>
						</div>
						</form>
					</div>
				  </div>
				  <!-- for mobile login end -->

				</div>
			</div>
		</div>
	</div>
</div>
<?php
$js = <<<JS
$(function(){
$(".inputs").keyup(function () {
        if (this.value.length == this.maxLength) {
          $(this).parent().next().find('.inputs').focus();
        }
      });

});

 $("#fox").click(function(event){
    event.preventDefault();
    $("img[id$='-verifycode-image']").click();
  });
$("#foxx").click(function(event){
    event.preventDefault();
    $("img[id$='-verifyccode-image']").click();
  });

$('#otpgen').click(function(){
	$("#displaymsg").show().delay(1000).fadeOut();
});
$('#lotpgen').click(function(){
	$("#ldisplaymsg").show().delay(1000).fadeOut();
});
JS;
$this->registerJs($js);
?>
<script type="text/javascript">
	function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

	function generateotp() {
		var loanno = $('#loanpaymentform-loanaccno').val();
		$.ajax({
			url: "<?= Url::toRoute(['site1/generateotp']); ?>?loanno=" + loanno,
			success: function(results) {
				if (results == 0) {
					alert("Wrong mobile no");
				} else {
					$('.otpsuccess').show();
				}
			}
		});
	}

	function lgenerateotp() {
		var loanno = $('#loannum').val();
		$.ajax({
			url: "<?= Url::toRoute(['site1/generateotp']); ?>?loanno=" + loanno,
			success: function(results) {
				if (results == 0) {
					alert("Wrong mobile no");
				} else {
					$('.lotpsuccess').show();
				}
			}
		});
	}

	function fetchmob(){
		var loannum = $("#loanpaymentform-loanaccno").val();
		/*console.log(loannum);
		console.log(loannum.length);*/
		if (loannum) {
			if (loannum.length > 5) {
				$.ajax({
					url: "<?= Url::toRoute(['site1/fetchmob']); ?>?loannum=" + loannum,
					success: function(results) {
						if (results) {
							var res=JSON.parse(results);
							console.log(res);
			               $('#mobilenum').show();
			                    $('#mobilenum').val(res.maskMobileNo);
													$('#loanpaymentform-loanaccno').val(res.LoanAccountNo);
													
								// $('#loanpaymentform-mobileno').val(res.MobileNo);
								$("#mobl").show();
								$('#generatebttn').show();
								$('#display_info').html('');
								$('#mob_info').html('');
			          
			                 if (res == '') {
			                 	$('#display_info').html('');
			                 	$('#mob_info').html('Mobile Number not Found');
			                 };
						}/*else if (results == []){
							$('#display_info').html('Mobile Number not Found');
						}else{
							$('#display_info').html('');
						}*/
					}
				});
			}else{
				$('#display_info').html('Please Enter Valid Loan Account No');
				$('#mobilenum').val('');
				$('#loanpaymentform-mobileno').val('');
				$('#generatebttn').hide();
				$("#mobl").hide();
				$('#mob_info').html('');
			}
		 }else{
			$('#mobilenum').val('');
			$('#loanpaymentform-mobileno').val('');
			$('#generatebttn').hide();
			$("#mobl").hide();
			$('#display_info').html('');
			$('#mob_info').html('');
		 }
	}

	function fetchloan(){
		var mobile = $("#mobnum").val();
		if (mobile) {
			if (mobile.length == 10) {
				$.ajax({
					url: "<?= Url::toRoute(['site1/fetchloan']); ?>?mobile=" + mobile,
					success: function(results) {
						if (results) {
							var res=JSON.parse(results);
								$('#loannum').show();
								$('#loannum').val(res.LoanAccountNo);
								$('#mobnum').val(res.MobileNo);								
								$("#loanlabel").show();
								$('#lgeneratebttn').show();
								$('#display_info').html(''); 
						}
					}
				});
			}else{
				$('#display_info').html('Please Enter Valid Mobile Number');
				$('#loannum').val('');
				$('#mobnum').val('');
				$('#lgeneratebttn').hide();
				$("#loanlabel").hide();
			}
		 }else{
			$('#display_info').html('');
			$('#loannum').val('');
				$('#mobnum').val('');
				$('#lgeneratebttn').hide();
				$("#loanlabel").hide();
		 }
	}
	
</script>
