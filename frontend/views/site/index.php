<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title = 'Online Portal';
?>
<div class="site-index">

    <div class="container-fluid">
	 	<div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 page" style="text-align:center; padding-top:50px;">
					<p><img src="images/logo.png" height="70"/><br/><br/><br/>Dear Mr./Mrs/Ms (customer Name) Your Loan ID (xxxxx) is due on date(xx-xx-xxxx). <br/>Please go through the tinyurl/Annapurna Portal Link/ visitthe branch <br/> for payment to avoid the discontinue charge.
</p>
					<a href="<?= Url::to(['site/login']) ?>"><button class="btn btn-warning" style=" padding:10px; margin-top:20px; font-size:16px;" >Click on this portal link to pay ff
</button></a>
				</div>
			</div>
		</div>
	</div>
	
	
</div>
