<?php
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Previous Payment History';
?>
<div class="site-index">
		
		<div class="container-fluid">
	 	<div class="container">
            <div class="row align-items-center">
                <div class="col-md-5 page" style=" padding-top:50px;margin-top: 0px;margin-bottom: 0px;" >
					<p><img src="<?= Yii::getAlias('@backendUrl')?>/images/logo.png" height="70"/></p>
					<h3>Previous Payment</h3>
					<p>&nbsp;</p>
					<div class="box" >
						<div class="row">
							<div class="table-responsive">
							<div class="col-md-12 ">
								<table class="table table-striped table-bordered">
									  <thead>
										<tr>
										  <th scope="col">Month</th>
										  <th scope="col">Amount</th>
										  <th scope="col">Date</th>
										  <th scope="col">Status</th>
										 <th scope="col">Download</th>
										</tr>
									  </thead>
									  <tbody>
									  <?php
									  foreach ($pre as $key => $value) {
									  ?>
									  
										<tr>
										  <th scope="row"><?= date('m-Y',strtotime($value->MONTH )) ?></th>
										  <td><?= $value->TXN_AMT ?></td>
										  <td><?= date('d-m-Y H:i:s',strtotime($value->TXN_DATE )) ?></td>
										  <td>
											  <?php
											  if ($value->TXN_STATUS==1) {
												echo "SUCCESS";
											  }
											  else {
												  echo "FAILURE";
											  }
											  ?>
										  </td>
										<td class="text-center">
											<!-- <a href="<?php echo Url::toRoute(['site/samplepdf','date' => $value->TXN_DATE,'loanid' => $value->LOAN_ID,'userid' => $value->USER_ID]);?>" data-method ="get">gen</a>  -->
											<!-- <input type="hidden" id="loanid" value="<?=$value->LOAN_ID?>">
											<input type="hidden" id="userid" value="<?=$value->USER_ID?>"> -->
											<input type="hidden" id="txnid" value="<?=$value->TXN_ID?>">
											<input type="hidden" id="typ" value="<?=$value->TYPE?>">
											<?php 
											if ($value->TXN_STATUS==1)
												{
											?>
											<a class="btn btn-success pdfdownload" id="print" style="margin-bottom: 0px;padding: 1px 8px;" data="<?=$value->TXN_ID?>"><i class="fa fa-download"></i></a>
											<?php
												}
											else 
												{
												echo "N/A";
												}
											 ?>
										</td>
										 
										</tr>
									
									<?php
								}?>
								</tbody>
								</table>
							 </div>
							</div>
							
						</div>
						<div class="row text-center " style="margin-top:20px;">
							<a href="<?= Url::to(['site/home']);  ?>" data-method="post"><button type="submit" class="btn btn-primary"><i class="fa fa-home"></i> Home</button></a>
							
						</div>
						
						
						
					</div>
				</div>
			</div>
		</div>
	</div>
		
		
</div>	
<div id="userInfo" style="display: none;background-color:#fff;"></div>
<!-- <script type="text/javascript">
$(document).ready(function(){
 $("a[id^=print]").click(function(event) {
	console.log($("#receipt" + $(this).attr('id').substr(5)))
	var divToPrint=$("#receipt" + $(this).attr('id').substr(5));
	newWin= window.open("");
	newWin.document.write(divToPrint.outerHTML);
	newWin.print();
	newWin.close();
      });
   });

</script> -->
 <?php
 $printUrl = Url::toRoute(['site/print']);
$js = <<<JS
$("a[id^=print]").click(function(event) {
      var ops=$('#typ').val();
      var txnid=$(this).attr('data');
      window.location.href='https://afpl.pay2annapurnafinance.in/amplpg/pdfgen?opstype='+ops+'&txnid='+txnid;
 });
JS;
$this->registerJs($js);
?>
<!--  console.log(txnid);
      console.log(txnid);
      console.log('http://128.199.184.65:8080/amplpg/pdfgen?opstype='+ops+'&txnid='+txnid); -->

<!-- window.location.href='http://128.199.184.65:8080/amplpg/pdfgen?opstype='+ops+'&txnid='+txnid; -->
<!--  $(document).ready(function(){
$('.pdfdownload').click(function() {
        var txndate=$('#txndate').val();
        var loanid=$('#loanid').val();
        var userid=$('#userid').val();
								var txnid=$(this).attr('data');
        $('#userInfo').load("$printUrl", {date: txndate, loanid: loanid, userid: userid,id:txnid},function(){
        	var divToPrint=document.getElementById('userInfo');
		  var newWin=window.open('','Print-Window');
		  newWin.document.open();
		  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
		  newWin.document.close();
		  setTimeout(function(){newWin.close();},0.2);
		  
		  
		  
            
        });
    });
}); -->
