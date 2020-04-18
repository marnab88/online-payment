<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';

?>
<div class="site-login">

    <div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
      <div class="row">
        <div class="content-wrapper full-page-wrapper d-flex align-items-center auth-pages">
          <div class="card col-lg-4 mx-auto">
            <div class="card-body px-5 py-5">
              <h3 class="card-title text-left mb-3">Login</h3>
               <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <div class="form-group">
                  <label>Username or email *</label>
            		<?= $form->field($model, 'UserName')->textInput(['autofocus' => false,'placeholder'=>'User Name', 'options'=>['class' => 'form-control p_input']])->label(false) ?>
                 
                </div>
                <div class="form-group">
                  <label>Password *</label>
          <?= $form->field($model, 'Password')->passwordInput(['placeholder'=>'Password', 'options'=>['class'=>'form-control p_input']])->label(false) ?>
                 
                </div>
                
                <div class="text-center">
                  <button type="submit" class="btn btn-success btn-block enter-btn">Login</button>
                </div>
                
               
               <?php ActiveForm::end(); ?>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- row ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
</div>
