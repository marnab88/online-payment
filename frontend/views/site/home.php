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

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
<div class="site-index">
	<div class="container-fluid">
 
			<div class="row align-items-center">
				<div class="col-md-6 page" style=" padding-top:50px;margin-top: 0px;margin-bottom: 0px;     background-size: contain;" >
					<p><img src="<?= Yii::getAlias('@frontendUrl'); ?>/images/logo.png" height="70" /></p>
					<h3>Details Of Loan Account</h3>
					<div class="box">
						<div class="row">
							<div class="table-responsive">
							<table class="table table-bordered">
								<tr>
									<td><b>Name :</b></td>
									<td><?= $recorddetail->ClientName; ?></td>
									<td><b>Due Date :</b></td>
									<td><?= date('d-M-Y', strtotime($recorddetail->DemandDate)); ?></td>
								</tr>
								<tr>
									<td><b>Branch :</b></td>
									<td><?= $recorddetail->BranchName; ?></td>
									<td><b>EMI Amount :</b></td>
									<td><?= number_format($recorddetail->CurrentMonthDue, 2); ?></td>
								</tr>
								<tr>
									<td><b>Loan a/c No. :</b></td>
									<td><?= $recorddetail->LoanAccountNo; ?></td>
									<td><b>Penalty Amount :</b></td>
									<td><?= number_format($recorddetail->LatePenalty, 2); ?></td>
								</tr>
                 <tr>
									<td><b>Mobile No. :</b></td>
									<td><?= $recorddetail->MobileNo; ?></td>
									<td><b>Last month Due :</b></td>
									<td><?=$recorddetail->LastMonthDue ?></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><b>Emi Balance :</b></td>
									<td><?=$recorddetail->CurrentMonthDue +  $recorddetail->LatePenalty+$recorddetail->LastMonthDue-$amount ?></td>
								</tr>
               
                
							</table>
							</div>
							<!-- <div class="col-md-6">
								<div class="smallbox">
									<span>Name :</span> <?= $recorddetail->ClientName; ?><br />
									<span>Branch:</span> <?= $recorddetail->BranchName; ?><br />
									<span>Loan Acc No.:</span> <?= $recorddetail->LoanAccountNo; ?>
								</div>
							</div>

							<div class="col-md-6">
								<div class="smallbox">
									<span>Due Date:</span> <?= date('d-M-Y', strtotime($recorddetail->DemandDate)); ?><br />
									<span>EMI Amount :</span> <?= number_format($recorddetail->CurrentMonthDue, 2); ?><br />
									<span>Penalty Amount:</span> <?= number_format($recorddetail->LatePenalty, 2); ?><br/>
								
									<span>Emi Balance:Rs.<?=$recorddetail->CurrentMonthDue +  $recorddetail->LatePenalty-$amount ?></span>
								</div>
							</div> -->
						</div>
						<div class="row text-center " style="margin-top:20px;">
							<!-- <a class="btn btn-success"  data-toggle="modal" data-target="#myModal1" style="width:150px;">Manual Payment</a> -->
							<a href="<?php echo Url::toRoute(['site/changemobile']);?>"><button type="submit" class="btn btn-success"  >Change Number</button></a>
							<a href="<?php echo Url::toRoute(['site/previouspay','lid' => $recorddetail->LoanAccountNo]);?>"data-method="post"><button type="submit" class="btn btn-primary" >Previous Payment</button></a>
							<button type="submit" class="btn btn-success" data-toggle="modal" data-target="#myModal">Pay Now</button>
							
							<a class="nav-link" href="<?= Url::to(['site/logout']) ?>" style="display:none;"data-method="post" data-confirm="you are succesfully log out">
							<button type="submit" class="btn btn-danger"   >Log Out</button></a>
         					
						</div>



					</div>
				</div>
			</div>
		 
	</div>
	<!-- Mobile change Modal start -->
		<div class="modal fade" id="mobModal" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="
					modal-title">Change Your Mobile Number</h4>
				</div>
				<div class="modal-body">
					<?php $form = ActiveForm::begin(['options'=>['class' => 'form-horizontal']]); ?>

						<div class="form-group">
							<label class="col-sm-5" for="email">
								New Mobile Number:</label>
							<div class="col-sm-7">
								<input type="number" name="mob" id="mob" class="form-control">
							</div>
						</div>

						<div class="form-group otpsuccess" style="margin-top:15px !important;display: none;">
							<label class="control-label col-sm-4 col-xs-12" for="pwd">OTP:</label>
							<div class="col-sm-7">
								<input type="text" name="otp" id="otp" class="form-control">
							</div>
							<div class="form-group">
							<div class="col-sm-offset-4 col-sm-6">
								
								<button type="button" class="btn btn-primary" id="submit" name="submit" style="width:150px;">Submit</button>
							</div>
						</div>
						</div>
						<div class="form-group">
						<div class="col-sm-offset-4 col-sm-6">
						<button type="button" class="btn btn-primary" id="newotp" style="width:150px;" onclick="gotp();">Generate OTP</button>
						</div>
					</div>
					
					<?php ActiveForm::end(); ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Mobile change Modal end -->
	<!-- Modal -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="
					modal-title">Payment Information</h4>
				</div>
				<div class="modal-body">
					<?php $form = ActiveForm::begin(['options'=>['class' => 'form-horizontal']]); ?>

						<div class="form-group">
							<label class="col-sm-5" for="email">
								EMI Amount:</label>
							<div class="col-sm-7">
							
								Rs.<?= number_format($recorddetail->CurrentMonthDue +  $recorddetail->LatePenalty+$recorddetail->LastMonthDue ,2); ?>
								<input type="hidden" value="<?= number_format($recorddetail->CurrentMonthDue +  $recorddetail->LatePenalty+$recorddetail->LastMonthDue-$amount, 2); ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5" for="email">EMI Balance :</label>
							<div class="col-sm-7">
								<?php if(($recorddetail->CurrentMonthDue +  $recorddetail->LatePenalty+$recorddetail->LastMonthDue-$amount) != 0 ){?>
									Rs.<?=$recorddetail->CurrentMonthDue +  $recorddetail->LatePenalty+$recorddetail->LastMonthDue-$amount ?>
								<?php }else{?>
									<?= $recorddetail->CurrentMonthDue +  $recorddetail->LatePenalty+$recorddetail->LastMonthDue ?>
								<?php } ?>
								<input type="hidden" value="<?php if(($recorddetail->CurrentMonthDue +  $recorddetail->LatePenalty+$recorddetail->LastMonthDue-$amount) != 0 ){?><?= $recorddetail->CurrentMonthDue+$recorddetail->LastMonthDue +  $recorddetail->LatePenalty-$amount ?><?php }else{?><?= $recorddetail->CurrentMonthDue+$recorddetail->LastMonthDue +  $recorddetail->LatePenalty ?><?php } ?>" id="emi" name="emi">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-5">
								<input type="radio" id="stmtblnc" name="payment" value="<?php if(($recorddetail->CurrentMonthDue+$recorddetail->LastMonthDue +  $recorddetail->LatePenalty-$amount) != 0 ){?><?= $recorddetail->CurrentMonthDue +$recorddetail->LastMonthDue+  $recorddetail->LatePenalty-$amount ?><?php }else{?><?= $recorddetail->CurrentMonthDue+$recorddetail->LastMonthDue +  $recorddetail->LatePenalty ?><?php } ?>" checked>
								<label for="stmtblnc">Last Statement Balance :</label>
							</div>
							<div class="col-sm-7">
								<?php if(($recorddetail->CurrentMonthDue+$recorddetail->LastMonthDue +  $recorddetail->LatePenalty-$amount) != 0 ){?>
									Rs.<?=number_format($recorddetail->CurrentMonthDue+$recorddetail->LastMonthDue +  $recorddetail->LatePenalty-$amount,2); ?>
								<?php }else{?>
									Rs.<?= number_format($recorddetail->CurrentMonthDue +$recorddetail->LastMonthDue+  $recorddetail->LatePenalty,2); ?>
								<?php } ?>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-5">
							<input type="radio" id="payment" name="payment" value="other">
							<label for="payment">Other Amount :</label>
							</div>
							<div class="col-sm-7">
								<input type="number" required class="form-control" id="amount" pattern="[0-9]" autocomplete="off" value="<?= $recorddetail->CurrentMonthDue+$recorddetail->LastMonthDue +  $recorddetail->LatePenalty-$amount ?>" readonly placeholder="Enter Amount" name="payment_amount">
								<br>
								<div class="text-danger">
									<p id="errormessage" style="text-align: left;color: #ff0000;font-size: 14px;"></p>

									<?php //Yii::$app->session->getFlash('message'); ?>
								</div>
							</div>
						</div>
						

						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-6">
                <?php if(($recorddetail->CurrentMonthDue+$recorddetail->LastMonthDue +  $recorddetail->LatePenalty-$amount) != 0 )
                {
                  ?>
                  <button type="submit" name="payment" id="payment" class="btn btn-primary" style="width:150px;">Submit</button>
                  <?php
                }
                ?>
								
							</div>
						</div>
					<?php ActiveForm::end(); ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>




	<!-- manual payment -->
	<div class="modal fade" id="myModal1" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="
					modal-title">Payment Information</h4>
				</div>
				<div class="modal-body">
					<?php $form = ActiveForm::begin(['action'=>['site/manuallypay'],'options'=>['class' => 'form-horizontal']]); ?>
						<div class="form-group">
							<label class="control-label col-sm-4" for="email">EMI Amount:</label>
							<div class="col-sm-8">
							
								Rs.<?= number_format($recorddetail->CurrentMonthDue +  $recorddetail->LatePenalty+$recorddetail->LastMonthDue,2); ?>
								<input type="hidden" value="<?= number_format($recorddetail->CurrentMonthDue +  $recorddetail->LatePenalty+$recorddetail->LastMonthDue-$amount, 2); ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="email">EMI Balance:</label>
							<div class="col-sm-8">
							
								Rs.<?=$recorddetail->CurrentMonthDue +  $recorddetail->LatePenalty+$recorddetail->LastMonthDue-$amount ?>
								<input type="hidden" value="<?= $recorddetail->CurrentMonthDue +  $recorddetail->LatePenalty+$recorddetail->LastMonthDue-$amount ?>" name="emi">
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="control-label col-sm-4" for="pwd">Payment:</label>
							<div class="col-sm-8">

								<input type="text" class="form-control" placeholder="Enter Amount" name="manualpayment_amount">
								<br>
								<div class="text-danger">
									<?= Yii::$app->session->getFlash('message'); ?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<?php if ($recorddetail->Type =='MSME') {?>
								<input type="hidden" value="<?= isset($recorddetail->Mid)?$recorddetail->Mid:''; ?>" name="userid">
							<?php }else{ ?>
								<input type="hidden" value="<?= isset($recorddetail->Eid)?$recorddetail->Eid:''; ?>" name="userid">
							<?php }?>
							<br>
							<input type="hidden" value="<?= $recorddetail->LoanAccountNo; ?>" name="loanid">
							<br>
							<input type="hidden" value="<?= $recorddetail->Type; ?>" name="type">
							<br>
							<input type="hidden" value="<?= date('d-m-Y', strtotime($recorddetail->DemandDate)); ?>" name="month">
						</div>
						

						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-6">
								<button type="submit" name="manuallypayment" class="btn btn-primary" style="width:150px;">Submit</button>
							</div>
						</div>
					<?php ActiveForm::end(); ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- <script type="text/javascript">
function check(){
	
	var emi= $("#emi").val() ;
	var amount= $("#amount").val() ;
	alert(emi);
	if (amount>emi && amount < 1) {
		
			alert("amount doesn't macth");
	}
}
</script> -->

<script type="text/javascript">
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
					$('#submit').show();
				}
			}
		});
	}

	 <?php
    if($getrefresh !='')
    {
    ?>
	   refreshPage();
    <?php
    }
    ?>
    function refreshPage() {
	   setTimeout(function(){window.location.href ='<?= Url::to(['site/home']) ?>';},2000);
	}
</script>
 

  <?php
$js = <<<JS
$('input[name="payment"]').change(function(){
        if($(this).val()=='other')
        {
          $('#amount').removeAttr('readonly');
           $('#amount').val('');
        }
        else
        {
          $('#amount').attr('readonly','readonly');
          $('#amount').val($(this).val());
          $("#errormessage").html("");
        } 
    });

$("#amount").keyup(function() {
  var payment=parseFloat($("#amount").val());
	var emipayment=parseFloat($("#emi").val());
	console.log(payment);
	if(payment == '' || payment == 0){
		$("#errormessage").html("Please enter Amount");
		$("#amount").val('');
	}
	else if(isNaN(payment)){
		$("#errormessage").html("Please enter valid number");
		$("#amount").val('');
	}
	else if (payment > emipayment){
		$("#errormessage").html("Amount is Higher than Emi");
		$("#amount").val('');
	}else if(payment < 1){
		$("#errormessage").html("Your Amount Should not be less than 1");
	}
	else if(payment < emipayment){
		$("#errormessage").html("You have entered an amount which is less than your current due amount. Paying less than your due amount will affect your credit bureau score. If you want to proceed anyway then click Submit.");
	}
	else{
		$("#errormessage").html("");
	}
});



JS;
$this->registerJs($js);
?>
