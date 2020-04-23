<?php

/* @var $this \yii\web\View */
/* @var $content string */
// use common\models\UploadRecords;
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php $this->registerCsrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>
</head>
<body>

  <?php $this->beginBody() ?>
  <div class="container-scroller">
  
   <!-- partial:partials/_navbar.html -->
   <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
      <a class="navbar-brand brand-logo" href="<?= Url::to(['site/index']) ?>"><img src="<?= Yii::getAlias('@backendUrl')?>/images/logo.png" alt="logo"/>Annapurna</a>
      

    </div>
	<div class="navbar-menu-wrapper d-flex align-items-stretch">
	<ul class="navbar-nav navbar-nav-right">
		<li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle nav-profile" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
             
              <span class=" d-lg-inline"><?=Yii::$app->user->identity->UserName; ?></span>
            </a>
            <div class="dropdown-menu navbar-dropdown w-100" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="#">
                <i class="mdi mdi-cached mr-2 text-success"></i>
                Activity Log
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="<?= Url::to(['site/logout']) ?>" data-method="post" data-confirm="you have succesfully signed out">
                <i class="mdi mdi-logout mr-2 text-primary"></i>
                Signout
              </a>
            </div>
          </li>
	</ul>
	<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <span class="mdi mdi-menu"></span>
    </button>
	</div>
    
   <!--  <div class="navbar-menu-wrapper d-flex align-items-stretch">



    </div> -->
  </nav>
  <!-- partial -->
  
  <div class="container-fluid page-body-wrapper">
    <div class="row row-offcanvas row-offcanvas-right">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <?php
          if (Yii::$app->user->identity->Role == 1) {
          ?>
          <li class="nav-item">
            <a class="nav-link"   href="<?= Url::to(['user/create']) ?>" >
              <span class="menu-title">Create Subadmin</span>


              <i class="mdi mdi-plus menu-icon"></i>
            </a>

          </li>
          <?php
          }
          ?>
          <?php
          if (Yii::$app->user->identity->Role == 1 || Yii::$app->user->identity->UploadRecords == 1) {
          ?>
          <li class="nav-item">
            <a class="nav-link"   href="<?= Url::to(['site/index']) ?>" >
              <span class="menu-title">Monthly Data Upload</span>


              <i class="mdi mdi-contacts menu-icon"></i>
            </a>

          </li>
          <?php
          }
          ?>
		  <?php
         if (Yii::$app->user->identity->Role == 1 || Yii::$app->user->identity->ViewReports == 1) {
          ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= Url::to(['site/report']) ?>" >
              <span class="menu-title">Loan wise Payment Report</span>
              <i class="mdi mdi-plus menu-icon"></i>
            </a>

          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= Url::to(['site/report2']) ?>" >
              <span class="menu-title">Transaction history Report</span>
              <i class="mdi mdi-plus menu-icon"></i>
            </a>

          </li>
          <?php
          }
          ?>
           <?php
          if (Yii::$app->user->identity->Role == 1 || Yii::$app->user->identity->ViewReports == 1) {
          ?>
          <li class="nav-item">
            <a class="nav-link"   href="<?= Url::to(['site/adminprev']) ?>" >
              <span class="menu-title"> Admin Uploaded Records</span>


              <i class="mdi mdi-plus menu-icon"></i>
            </a>

          </li>
          
          <?php
          }
          ?>
           <?php
          if (Yii::$app->user->identity->Role == 1 || Yii::$app->user->identity->ViewReports == 1) { ?>
             <li class="nav-item">
            <a class="nav-link"  href="<?= Url::to(['site/adminview']) ?>" >
              <span class="menu-title">Subadmin Uploaded Records</span>


              <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>

          </li>
          <?php
          }
          elseif(Yii::$app->user->identity->ViewReports == 1){
          ?>
          <li class="nav-item">
            <a class="nav-link"  href="<?= Url::to(['site/previous']) ?>" >
              <span class="menu-title">View Previous Payments</span>


              <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>

          </li>
          <?php
        }
        ?>
        

           <?php
          if (Yii::$app->user->identity->Role == 1 || Yii::$app->user->identity->ViewReports == 1) { ?>
             <li class="nav-item">
            <a class="nav-link"  href="<?= Url::to(['site/paymentdetails']) ?>" >
              <span class="menu-title">Payment Details</span>
              <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>

          </li>
          <?php
          }
          ?>
           
          <?php
         if (Yii::$app->user->identity->Role == 1) {
          ?>
		  <li class="nav-item">
            <a class="nav-link"   href="<?= Url::to(['site/branch']) ?>" >
              <span class="menu-title">Branch Master</span>


              <i class="mdi mdi-plus menu-icon"></i>
            </a>

          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= Url::to(['site/resetpassword']) ?>" >
              <span class="menu-title">Reset Password</span>
              <i class="mdi mdi-plus menu-icon"></i>
            </a>

          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= Url::to(['site/subaddresetpassword']) ?>" >
              <span class="menu-title">Sub-admin Reset Password </span>
              <i class="mdi mdi-plus menu-icon"></i>
            </a>

          </li>
          <?php
          }
          else{
          ?>
          <li class="nav-item">
            <a class="nav-link"  href="<?= Url::to(['site/resetpassword']) ?>" >
              <span class="menu-title">Reset Password</span>


              <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>

          </li>
          <?php
        }
        ?>


          

          <!-- <li class="nav-item">
            <a class="nav-link" href="<?= Url::to(['site/logout']) ?>" data-method="post" data-confirm="you have succesfully loged out">
              <span class="menu-title">Logout</span>
              <i class="mdi mdi-logout menu-icon"></i>
            </a>
          </li> -->

        </ul>
	
      </nav>
      <!-- partial -->



      <div class="content-wrapper">

        <?= Breadcrumbs::widget([
          'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
      </div> 




      <?php $this->endBody() ?>
    </div>

  </div>
  </div>
</body>
</html>
<?php $this->endPage() ?>
