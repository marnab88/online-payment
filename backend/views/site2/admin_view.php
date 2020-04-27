<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\UploadRecords;
use yii\helpers\Url;



$this->title = 'record_update';

?>
<div class="site-login">
	<div class="row">
		<div class="col-lg-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Individual Records</h4>
					<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>Download FIle</th>
							<th>File Type</th>
							<th>Date</th>
							<th>Uploaded By</th>
							<th>Month</th>
							<th>Number of Records </th>
							<th>Cleared</th>
							<th>Mismatch</th>
							<th>View Details</th>
						<!-- 	<?php if (Yii::$app->user->identity->Role != 2) {?>
							<th>Delete</th>
							<?php } ?> -->
								
						
							
						</tr>
						</thead>
						<tbody>
						<?php
						
						foreach ($details as $key => $value) {
							
							
							?>
							<tr>
								<td><a href="<?= Yii::getAlias('@storageUrl').'/uploads/'.$value->File?>" target="_blank" style="color:#d89d51;">Download</a></td>
								<td><?= $value->Type ?></td>
								<td><?= date('d-m-Y',strtotime($value->OnDate)) ?></td>
								<td><?= ($value->user)?$value->user->UserName:''; ?></td>
								<td><?= $value->MonthYear  ?></td>
								<td><?php
								if ($value->Type == 'MFI') {?>
									

									<span style="color:#FF3333;"><?=$value->Count?></span>
								 <?php	
								 } 
								 else{
								 	?>

									<span style="color:#FF3333;"><?=$value->Count?></span>
								 <?php	
								 }
								  ?></td>
								<td><span style="color:#00CC00"><?=$value->Count-($value->Mismatch+$value->MobileCount) ?></span></td>
								<td><?php
								if ($value->Type == 'MFI') {?>
								<span style="color:#FF3333;"><?=$value->Mismatch+$value->MobileCount?></span>

								<?php
									}
								else{
									?>
									<span style="color:#FF3333;"><?=$value->Mismatch+$value->MobileCount?></span>

								
								<?php
								}
								?>
									
								</td>

								<td><a href="<?php 
								 if(Yii::$app->user->identity->Role == 1){
								 echo Url::toRoute(['site2/both','id' => $value->RecordId,'type'=>34,'mon'=>$value->MonthYear,'pagination'=>'yes']);}
								 elseif(Yii::$app->user->identity->Type == "MFI"){
								 echo Url::toRoute(['site2/mfi','id' => $value->RecordId,'type'=>34,'mon'=>$value->MonthYear,'pagination'=>'yes']);}
								 elseif(Yii::$app->user->identity->Type == "MSME"){
								 echo Url::toRoute(['site2/msme','id' => $value->RecordId,'type'=>34,'mon'=>$value->MonthYear,'pagination'=>'yes']);}
								 else{
								 	echo Url::toRoute(['site2/both','id' => $value->RecordId,'type'=>34,'mon'=>$value->MonthYear,'pagination'=>'yes']);
								 }

								 ?>" data-method="post"  style="float:left;color:#ff3300; margin-top:5px;">View Details</a></td>
								
								
								</tr>
							</tr>
							<?php
						
						}
						?>
							
						</tbody>
					</table>
					</div>
				</div>
			</div>
		</div>
		
		
	</div>
	
</div>