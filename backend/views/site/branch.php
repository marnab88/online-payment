<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\UploadRecords;
use yii\helpers\Url;
//use yii\widgets\ActiveForm;
$this->title = 'Home';

?>
<div class="site-login">


	<?php if(Yii::$app->user->identity->Role == 1 ){ ?>
	<div class="row">
		
		
		<div class="col-lg-6 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Upload Branch Master</h4>
					
					<?php $form = ActiveForm::begin(['options' => ['class' => 'forms-sample','enctype' => 'multipart/form-data']]) ?>
					
					
						<div class="form-group">
							<?= $form->field($model, 'File')->fileInput() ?>
						</div>

						
						
							
							
						
						<button type="submit" class="btn btn-success mr-2" >Submit</button>
						
						<?php ActiveForm::end() ?>
						</div>
					</div>
				</div>
			</div>
			
			
		</div>


		<div class="row">


			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title col-sm-6">View List Of Branch Master</h4>
						 <div class="col-sm-6 text-right">
						 	<?php
                        if(!empty($details))
                        {
                        ?>
                            <a href="<?=Url::toRoute(['site1/exportbranch']);?>"><button class="btn btn-warning" type="button"><i class="mdi mdi-file-excel"></i> Export</button></a>
                          
                       
                        <?php
                        }
                        ?>
				                <!-- <input type="button" value="Export Branch Data" class="btn btn-success exportToExcel" style="float:right;"> -->
				            </div>
						<table class="table table-striped table-bordered" id="table_emp">
							<thead>

								<tr>
									<th>No.</th>
									<th>Branch Id</th>
									<th>Branch Name</th>
									<th>Cluster</th>
									<th>State</th>
									<th>Entity</th>
									
								</tr>
							</thead>
							<tbody>
								<?php
								$a=1;
								foreach ($details as $key => $value) {
								
							
								 ?>
								<tr>
									<td><?= $a ?></td>
									<td><?= $value->BranchId ?></td>
									<td><?= $value->BranchName ?></td>
									<td><?= $value->Cluster ?></td>
									<td><?= $value->State ?></td>
									<td><?= $value->Entity ?></td>
									
								</tr>
								
									<?php
									$a++;
								}
								?>
							</tbody>
						</table>

					</div>
					 <div class="row">
            <div class="col-sm-6 text-left"></div>
            <div class="col-sm-6 text-right"><?= yii\widgets\LinkPager::widget(['pagination' => $pages]);?></div>
          </div>
				</div>
			</div>


		</div>
		<?php }?>


<?php
    $this->registerJs('
    $(".exportToExcel").click(function(e){
          var table = $("#table_emp");
          if(table && table.length){
            var preserveColors = (table.hasClass("table2excel_with_colors") ? true : false);
            $("#table_emp").table2excel({
              exclude: ".noExl",
              name: "BranchData",
              filename: "BranchData" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
              fileext: ".xls",
              exclude_img: true,
              exclude_links: true,
              exclude_inputs: true,
              preserveColors: preserveColors
            });
          }
        });



 ');
  ?>