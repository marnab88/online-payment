<?php

/* @var $this yii\web\View */

$this->title = 'Successful Payment';
?>
<div class="site-index">
		<div class="container-fluid">
	 	<div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 page" style=" padding-top:50px;">
					<p><img src="images/logo.png" height="70"/></p>
					<h3><img src="images/success.png" height="40"/><br/>Payment Successfull</h3>
					<p>Your payment has been processed! Details of transactions are included below.</p>
					<div class="box1" >
						<div class="row">
							<div class="col-md-12 ">
								<div class="smallbox">
								  <span>Amount :</span> 8,930.00<br/>
								  <span>Transaction ID:</span> XXXXXXXXXXXXX<br/>
								  <span>Payment Type:</span> Net Banking<br/>
								  <span>Transaction Time:</span> 04-Apr-2020 - 10:11:12
								 </div>
							 </div>
							
							
						</div>
						<div class="row text-center " style="margin-top:20px;">
							<button type="submit" class="btn btn-warning" onclick="window.location.href='home.html'" style="width:150px;"><i class="fa fa-print"></i> Print Receipt</button>
							
						</div>
						
						
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	