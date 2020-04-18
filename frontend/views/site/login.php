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
	.form-horizontal .form-group{ margin: 5px 0px 0px 0px !important;}
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
.nav-tabs > li > a.active {

    border-radius: 0px;
    border: #e45f11;
   padding: 10px 15px;
    font-weight: 600;
    font-size: 20px;
    border: 0px solid #ddd;
    

}
.nav-tabs > li > a:hover{background-color: #f27024; color: #fff; padding: 10px 15px;}
.nav-tabs > li > a  {

    font-weight: 600; color: #e45f11;   padding: 10px 15px;
    font-size: 20px; padding-bottom: 0px;
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus{ border: 0px solid #ddd; border-bottom-color: 0px solid #ddd;background-color: #f27024; color: #fff;    padding: 10px 15px;}
.nav-tabs {
    border-bottom: 1px solid #f27024;}
</style>
<div class="site-login">
	<div class="container-fluid">
		<div class="container">







			<div class="row align-items-center">
				<div class="col-md-6 mx-auto page" style=" padding-top:50px;margin-top: 0px;margin-bottom: 0px;background-size: contain;" >
					<p class="llg"><img src="<?= Yii::getAlias('@frontendUrl'); ?>/images/logo.png" height="70" /></p>
					<p class="tl"> Welcome to Our Online Payment Facility </p>
<p class="nrm">You Can Pay Overdue EMI Using Internet Banking/Debit Card/UPI Facility Offered By Your Bank To Pay Outstanding Payments On Loans From

Annapurna Finance </p>

<ul class="nav nav-tabs" role="tablist">
  <li class="nav-item">
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
  	<div class="formbox">
						<?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'form-horizontal']]); ?>
						<div class="form-group">
							
							<div class="col-sm-12">
								<?php if(isset($recordDetail->LoanAccountNo)){?>
									<?= $form->field($model, 'loanaccno')->textInput(['value'=>isset($recordDetail->LoanAccountNo)?$recordDetail->LoanAccountNo:'','placeholder'=>'Loan a/c Number','readonly'=>'readonly'])->label(false) ?>
								<?php }else{?>
								<?= $form->field($model, 'loanaccno')->textInput(['value'=>isset($recordDetail->LoanAccountNo)?$recordDetail->LoanAccountNo:'','placeholder'=>'Loan a/c Number', 'autocomplete'=>'off', 'onblur'=>'fetchmob()'])->label(false) ?>
								<?php }?>
								<p id="display_info" style="color:red;text-align:left;"></p>
							</div>
						</div>
						<div class="form-group">
							
							<div class="col-sm-12">
								<?= $form->field($model, 'mobileno')->textInput(['id'=>'mobilenum','name'=>'mobilenum','value'=>isset($recordDetail->MobileNo)? str_repeat("X", strlen($recordDetail->MobileNo)-4) . substr($recordDetail->MobileNo, -4):'','type' => 'tel','placeholder'=>'Mobile No', 'readonly'=>isset($recordDetail->MobileNo)?'readonly':''])->label(false) ?>
								<?= $form->field($model, 'mobileno')->textInput(['value'=>isset($recordDetail->MobileNo)? $recordDetail->MobileNo:'','type' => 'hidden'])->label(false) ?>
								<p id="mob_info" style="color:red;text-align:left;"></p>
							</div>
						</div>
						<?php if(isset($recordDetail->MobileNo)){?>
							<div class="form-group" id="generatebttn">
								<div class="col-sm-offset-6 col-sm-6">
									<button type="button" class="btn btn-primary" onclick="generateotp();">Generate OTP</button>
								</div>
							</div>
						<?php }else{?>
							<div class="form-group" id="generatebttn" style="display:none;">
								<div class="col-sm-offset-6 col-sm-6">
									<button type="button" class="btn btn-primary" onclick="generateotp();">Generate OTP</button>
								</div>
							</div>
						<?php } ?>
						
						<div class="form-group otpsuccess" style="margin-top:15px !important;display: none;">
							<label class="control-label col-sm-4 col-xs-12" for="pwd">OTP:</label>
							<div class="col-sm-2 col-xs-3">
								<input type="text" maxlength="1" class="form-control inputs" id="pwd" placeholder="" name="otp[]" required="required">
							</div>
							<div class="col-sm-2 col-xs-3">
								<input type="text" maxlength="1" class="form-control inputs" id="pwd" placeholder="" name="otp[]" required="required">
							</div>
							<div class="col-sm-2 col-xs-3">
								<input type="text" maxlength="1" class="form-control inputs" id="pwd" placeholder="" name="otp[]" required="required">
							</div>
							<div class="col-sm-2 col-xs-3">
								<input type="text" maxlength="1" class="form-control inputs" id="pwd" placeholder="" name="otp[]" required="required">
							</div>
						</div>
						
						<div class="form-group otpsuccess" style="margin-bottom:30px;display: none;">
							<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
								'template' => '<div class="row"><div class="col-sm-4">{image}<a href="#" id="fox" title="refresh capcha"><i class="fa fa-refresh" aria-hidden="true"></i></a></div><div class="col-sm-8">{input}</div></div>',
							]) ?>
						</div>
						<div class="form-group otpsuccess" style="display: none;">
							<div class="col-sm-offset-5 col-sm-7">
								<button type="submit" name="authenticate" class="btn btn-success">Authenticate/Submit</button>
							</div>
						</div>
						<?php ActiveForm::end(); ?>
					</div>

   </div>
   <!-- for Loan Account login end -->
   <!-- for mobile login start -->
  <div role="tabpanel" class="tab-pane fade" id="buzz">Mobile</div>
  <!-- for mobile login end -->

</div>




					
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
  })
JS;
$this->registerJs($js);
?>
<script type="text/javascript">
	function generateotp() {
		var mobileno = $('#loanpaymentform-mobileno').val();
		$.ajax({
			url: "<?= Url::toRoute(['site/generateotp']); ?>?mobileno=" + mobileno,
			success: function(results) {
				if (results == 0) {
					alert("Wrong mobile no");
				} else {
					$('.otpsuccess').show();
				}
			}
		});
	}

	function fetchmob(){
		$('#mob_info').html('');
		var loannum = $("#loanpaymentform-loanaccno").val();
		/*console.log(loannum);
		console.log(loannum.length);*/
		if (loannum) {
			if (loannum.length > 5) {
				$.ajax({
					url: "<?= Url::toRoute(['site/fetchmob']); ?>?loannum=" + loannum,
					success: function(results) {
						if (results) {
							var res=JSON.parse(results);
							console.log(res);
			               
			                    $('#mobilenum').val(res.maskMobileNo);
													$('#loanpaymentform-loanaccno').val(res.LoanAccountNo);
								$('#loanpaymentform-mobileno').val(res.MobileNo);
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
				$('#mob_info').html('');
			}
		 }else{
			$('#mobilenum').val('');
			$('#loanpaymentform-mobileno').val('');
			$('#generatebttn').hide();
			$('#display_info').html('');
			$('#mob_info').html('');
		 }
	}
	
</script>
