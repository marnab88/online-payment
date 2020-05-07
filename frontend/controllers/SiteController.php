<?php namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\OtpVerification;
use common\models\ExcelData;
use common\models\MsmeExcelData;
use common\models\UploadRecords;
use frontend\models\LoanPaymentForm;
use common\models\TXNDETAILS;
 use kartik\mpdf\Pdf;
use yii\db\Expression;
use yii\web\Session;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [ 'access'=>[ 'class'=>AccessControl::className(),
            'only'=> ['logout', 'signup'],
            'rules'=> [ [ 'actions'=> ['signup'],
            'allow'=> true,
            'roles'=> ['?'],
            ],
            [ 'actions'=> ['logout'],
            'allow'=> true,
            'roles'=> ['@'],
            ],
            ],
            ],
            'verbs'=> [ 'class'=> VerbFilter::className(),
            'actions'=> [ 'logout'=> ['post'],
            ],
            ],
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [ 'error'=>[ 'class'=>'yii\web\ErrorAction',
        ],
        'captcha'=>[ 'class'=>'common\captcha\CaptchaAction',
        'fixedVerifyCode'=>YII_ENV_TEST ? 'testme': null,
            ],
        'captcha2'=>[ 'class'=>'common\captcha\CaptchaAction2',
        'fixedVerifyCode'=>YII_ENV_TEST ? 'testme': null,
            ],
            ];
    }
 
 public function actionSamplepdf($loanid,$userid) {

          $pre=TXNDETAILS::find()->where(['LOAN_ID'=>$loanid,'USER_ID'=>$userid])->one();

         // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('print');

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $filename = "sample". ".pdf";

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => $destination,
            'filename' => $filename,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => 'p, td,div { font-family: freeserif; }; body, p { font-family: irannastaliq; font-size: 15pt; }; .kv-heading-1{font-size:18px}table{width: 100%;line-height: inherit;text-align: left; border-collapse: collapse;}table, td, th {border: 1px solid black;}',
            'marginFooter' => 5,
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => ['SAMPLE PDF'],
                //'SetHeader' => ['SAMPLE'],
                'SetFooter' => ['Page {PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render('previous');
    }
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() {
       return $this->redirect(['login']);
    }

    public function actionGenerateotp() {
        $loanno=Yii::$app->request->get()['loanno'];
         $msme=MsmeExcelData::find()->where(['LoanAccountNo'=> $loanno,'IsDelete'=>0,'IsApproved'=>1])->orderBy(['Mid'=> SORT_DESC])->one();
         $mobileno= $msme->MobileNo;
        $otp=new OtpVerification();
        $result=$otp->generateOtp($mobileno);
        echo json_encode($result);
    }
     public function actionMobchangeotp() {
        $mobileno=Yii::$app->request->get()['mobileno'];
        $otp=new OtpVerification();
        $result=$otp->mobchangeOtp($mobileno);
        echo json_encode($result);
    }
    public function actionCheckmobile($mobileno) {
            $session=Yii::$app->session;
            $session->open();
            $loan=$session->get('loan');
            $type=$session->get('type');

            //echo $type;die();
            if ($type =='MFI') {
                $recordDetail=ExcelData::find()->where(['MobileNo'=>$mobileno])->andWhere(['>', 'OnDate', new Expression('DATE_SUB(CURRENT_DATE(), INTERVAL 2 MONTH)')])->count();
            }

            else {
                $recordDetail=MsmeExcelData::find()->where(['MobileNo'=>$mobileno])->andWhere(['>', 'OnDate', new Expression('DATE_SUB(CURRENT_DATE(), INTERVAL 2 MONTH)')])->count();
            }
             if($recordDetail > 0){
                     $result=0;
                    }else{
                        $result=1;
                    }
           
        echo json_encode($result);
    }

    public function actionChangemobile() {
            $session=Yii::$app->session;
            $session->open();
            $loan=$session->get('loan');
            $type=$session->get('type');
            
            if ($type =='MFI') {
                $recordDetail=ExcelData::find()->where(['LoanAccountNo'=> $loan,'IsApproved'=>1, 'IsDelete'=> 0])->orderBy(['Eid'=> SORT_DESC])->all();
            }

            else {
                $recordDetail=MsmeExcelData::find()->where(['LoanAccountNo'=> $loan,'IsApproved'=>1, 'IsDelete'=> 0])->orderBy(['Mid'=> SORT_DESC])->all();
            }
        
        if(isset(Yii::$app->request->post()['otp']))
        {
            // echo "<pre>";var_dump($recordDetail);echo "</pre>";die();
            $otp=Yii::$app->request->post('otp');
            $mob=Yii::$app->request->post('mob');
            // $checkotp=OtpVerification::find()->where(['MobileNo'=> $mob, 'OtpCode'=> $otp,'IsDelete'=> 0])->orderBy(['Id'=> SORT_DESC])->one();
             $checkotp=OtpVerification::find()->where(['MobileNo'=> $mob, 'OtpCode'=> $otp, 'IsVerified'=> 0, 'IsDelete'=> 0])->orderBy(['Id'=> SORT_DESC])->one();
             if (count($checkotp) > 0) {
               /* if($mob == $recordDetail->MobileNo)
                {
                    Yii::$app->session->setFlash('error', 'Mobile Number already exist!');
                    return $this->redirect(['home']);
                }*/
                $checkotp->IsVerified=1;
                if ($checkotp->save()) {
                foreach ($recordDetail as $key => $value) {
                    $value->MobileNo=$mob;
                     if ($value->save()){
                        $otp=new OtpVerification();
                        $otp->mobchangesuccessotp($mob,$loan);
                    // var_dump($recordDetail->getErrors());
                    Yii::$app->session->setFlash('success', 'Your Mobile Number Successfully Updated.');
                    return $this->redirect(['home','refresh'=>1]); 
                    }else{
                        // var_dump($recordDetail->getErrors());
                        Yii::$app->session->setFlash('error', 'Somthing Went wrong!.');
                    }
                    
                }
            }
             }
             else
             {
                $otp=new OtpVerification();
                $otp->mobchangefailedotp($mob,$loan);
                Yii::$app->session->setFlash('error', 'Please Enter valid Otp!.');
             }


        }
        return $this->render('newmobile');      
    }
    public function actionFetchmob() {
        $loannum=isset(Yii::$app->request->get()['loannum'])?Yii::$app->request->get()['loannum']:'';
        if(strlen($loannum)<6){
           return false;
        }

        $result=array();
        $fetchsom=MsmeExcelData::find()->where(['IsDelete'=>0,'IsApproved'=>1])->andWhere(['like', 'LoanAccountNo' , $loannum ])->orderBy(['Mid'=> SORT_DESC])->one();
       /* $fetchmob=Yii::$app->db->createCommand("(SELECT LoanAccountNo,MobileNo FROM ExcelData WHERE LoanAccountNo like '%$loannum' AND MobileNo !='' AND IsApproved=1  ORDER BY Eid DESC) UNION ALL (SELECT LoanAccountNo,MobileNo FROM MsmeExcelData WHERE LoanAccountNo like '%$loannum' AND MobileNo !='' AND IsApproved=1  ORDER BY Mid DESC)");
        $fetchsom =$fetchmob->queryOne();*/
        $result=false;
        if ($fetchsom) {
             $result['LoanAccountNo']=$fetchsom->LoanAccountNo;
            // $result['MobileNo']=$fetchsom['MobileNo'];
            $result['maskMobileNo']=str_repeat("X", strlen($fetchsom->MobileNo)-4) . substr($fetchsom->MobileNo, -4);

        
        }else{
            $fetchsom=ExcelData::find()->where(['IsDelete'=>0,'IsApproved'=>1])->andWhere(['like', 'LoanAccountNo' , $loannum ])->orderBy(['Eid'=> SORT_DESC])->one();
            if($fetchsom){
            $result['LoanAccountNo']=$fetchsom->LoanAccountNo;
            // $result['MobileNo']=$fetchsom['MobileNo'];
            $result['maskMobileNo']=str_repeat("X", strlen($fetchsom->MobileNo)-4) . substr($fetchsom->MobileNo, -4);
            }
            else
            {
                return false;
            }
        }
        
        echo json_encode($result);
    }
     public function actionFetchloan() {
        $mobile=isset(Yii::$app->request->get()['mobile'])?Yii::$app->request->get()['mobile']:'';
        $result=array();
       /* $fetchloan=Yii::$app->db->createCommand("(SELECT LoanAccountNo,MobileNo FROM ExcelData WHERE MobileNo like '%$mobile%' AND LoanAccountNo !='' AND IsApproved=1  ORDER BY Eid DESC) 
                                                    UNION ALL 
                                                (SELECT LoanAccountNo,MobileNo FROM MsmeExcelData WHERE MobileNo like '%$mobile%' AND LoanAccountNo !='' AND IsApproved=1  ORDER BY Mid DESC)");
        $fetchall =$fetchloan->queryOne();*/
        $fetchall=MsmeExcelData::find()->where(['MobileNo'=> $mobile,'IsDelete'=>0,'IsApproved'=>1])->orderBy(['Mid'=> SORT_DESC])->one();
        if ($fetchall) {
             $result['LoanAccountNo']=$fetchall->LoanAccountNo;
             $result['MobileNo']=$fetchall->MobileNo;
        }else{
            $fetchall=ExcelData::find()->where(['MobileNo'=> $mobile,'IsDelete'=>0,'IsApproved'=>1])->orderBy(['Eid'=> SORT_DESC])->one();
            if($fetchall){
                $result['LoanAccountNo']=$fetchall->LoanAccountNo;
             $result['MobileNo']=$fetchall->MobileNo;
            }
            else{
                return false;
            }
            
        }
        
        echo json_encode($result);
    }

    public function actionHome() {
        $session=Yii::$app->session;
        $session->open();

        $getrefresh= (Yii::$app->request->get('refresh')) ? Yii::$app->request->get('refresh') : '';
        // if($getrefresh){
        //return $this->redirect(['home']);
        //}
         
        //if(empty($session->get('type'))){
        //    return $this->redirect(['login']);
        //}

        $recordid=$session->get('datarecordid');
        $RecordId=$session->get('RecordId');
        $loan=$session->get('loan');
        $type=$session->get('type');
        $month=$session->get('month');
        $userid=$session->get('datarecordid');
        $mobile=$session->get('mobile');

        if(isset(Yii::$app->request->post()['payment'])) {
            $payment_amount=Yii::$app->request->post()['payment_amount'];
            $emi_amount=Yii::$app->request->post()['emi'];
            if( $payment_amount > $emi_amount ) {
                Yii::$app->session->setFlash('error', 'Amount Is Higher Than Emi');
                return $this->redirect(['home']);
            }else if($payment_amount < 1) {
                Yii::$app->session->setFlash('error', 'Your Amount Should not be less than 1');
                return $this->redirect(['home']);

            }else{
                 Yii::$app->session->setFlash('', '');
            }

            $count=TXNDETAILS::find()->count('LOAN_ID');
            $model=New TXNDETAILS;
            $model->LOAN_ID=$loan;
            $model->USER_ID=$userid;
            $model->MONTH=date('Y-m', strtotime($month));
            $model->TXN_DATE=date('Y-m-d H:i:s');
            $model->TXN_AMT=$payment_amount;
            $model->TXN_FOR="EMI PAYMENT";
            $model->TYPE=$type;
            $model->MOB_NO=$mobile;

            //  var_dump($model->UserId=$userid);die();
            if($model->save()) {
                $txn=("AFPL".$loan.date('Y-m', strtotime($month)).sprintf('%02d', $count).$model->id);
                $txni=str_replace("-", "", $txn);
                $tid=TXNDETAILS::find()->where(['id'=>$model->id])->one();
                $tid->TXN_ID=$txni;
                // var_dump($tid);die();
                $tid->save();

            }

            // echo"<pre>";var_dump($model);echo"</pre>";die();
            $session->set('payment_amount', $payment_amount);
            // return $this->redirect(['payment']);

            return $this->redirect("http://128.199.184.65:8080/amplpg/index.jsp?txnid=$txni&txntype=$type");

        }

        $uploadRecords=UploadRecords::find()->where(['RecordId'=> $RecordId, 'IsDelete'=>0])->one();

        if ($uploadRecords) {
            if ($uploadRecords->Type=='MFI') {
                $recordDetail=ExcelData::find()->where(['Eid'=> $recordid])->one();
            }

            else {
                $recordDetail=MsmeExcelData::find()->where(['Mid'=> $recordid])->one();
            }

            if ($recordDetail) {
                $connection=\Yii::$app->db;
                $amnt=$connection->createCommand("SELECT SUM(TXN_AMT) as amt FROM  TXN_DETAILS WHERE LOAN_ID = '$recordDetail->LoanAccountNo' and `TXN_STATUS`=1");
                $result=$amnt->queryAll();
                $amount=$result[0]['amt'];
                // var_dump($amount);die();

                return $this->render('home', ['recorddetail'=>$recordDetail, 'amount'=>$amount,'getrefresh'=>$getrefresh]);
            }
        }

        else {
            Yii::$app->session->setFlash('error', 'Record not found');
        }

        return $this->redirect(['login']);
    }

    public function actionPayment() {
        $session=Yii::$app->session;
        $session->open();
        $recordid=$session->get('datarecordid');
        $payment_amount=$session->get('payment_amount');


        return $this->render('payment', ['payment_amount'=>$payment_amount]);
    }

    public function actionManuallypay() {
        if(isset(Yii::$app->request->post()['manuallypayment'])) {
            $amount=Yii::$app->request->post()['manualpayment_amount'];
            $userid=Yii::$app->request->post()['userid'];
            $loanid=Yii::$app->request->post()['loanid'];
            $type=Yii::$app->request->post()['type'];
            $month=Yii::$app->request->post()['month'];
            $emi_amount=Yii::$app->request->post()['emi'];
        //    echo($payment_amount); echo ($emi_amount); echo($emi_amount-$payment_amount);die();
            if( $amount > $emi_amount ) {
                Yii::$app->session->setFlash('message', 'Amount Is Higher Than Emi ');
                // var_dump($payment_amount);die();
                return $this->redirect(['home']);

            }

            elseif($amount < 1) {
                Yii::$app->session->setFlash('message', 'Amount Is Lesser Than 1 ');
                return $this->redirect(['home']);

            }
            $model=new TXNDETAILS;
            $model->USER_ID=$userid;
            $model->LOAN_ID=$loanid;
            $model->TYPE=$type;
            $model->MONTH=date('Y-m', strtotime($month));
            $model->TXN_AMT=$amount;
            $model->PAYMENT_TYPE='Cash';
            $model->TXN_STATUS=1;
            $model->save(); 
          
       }
        return $this->redirect('home');
    }

    public function actionSuccess() {
        return $this->render('success');
    }

    public function actionPrevious() {
        return $this->render('previous');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        $session=Yii::$app->session;
        $session->open();
        if ( !Yii::$app->user->isGuest) {
            return $this->goHome();
        }   
        if($session->get('loan')){
        return $this->redirect(['home']);
        }
         $recordid= (Yii::$app->request->get('id')) ? Yii::$app->request->get('id') : '';
        $type=(Yii::$app->request->get('typ')) ? Yii::$app->request->get('typ'):'';
        $loanid=(Yii::$app->request->get('l')) ? Yii::$app->request->get('l'):'';
        $recordDetail=[];
        if ($type=='MSME') {
            $recordDetail=MsmeExcelData::find()->where(['LoanAccountNo'=>$loanid,'IsApproved'=>1])->orderBy(['Mid' => SORT_DESC])->one();
        }

        else if($type='MFI') {
            $recordDetail=ExcelData::find()->where(['LoanAccountNo'=>$loanid,'IsApproved'=>1])->orderBy(['Eid' => SORT_DESC])->one();
        }
       if($loanid==''){
        $recordDetail=[];
       }

      

        $model=new LoanPaymentForm();

        if ($model->load(Yii::$app->request->post())) {
         //echo "inn";
            $otpcode=implode('', Yii::$app->request->post()['otp']);
            $model->otp=$otpcode;

            if($model->validate()) {
             
                $loanaccountno=$model->loanaccno;
                $findMSMECustomer=MsmeExcelData::find()->where(['LoanAccountNo'=>$loanaccountno,'IsApproved'=>1])->orderBy(['Mid' => SORT_DESC])->one();
                $findMFICustomer=ExcelData::find()->where(['LoanAccountNo'=>$loanaccountno,'IsApproved'=>1])->orderBy(['Eid' => SORT_DESC])->one();
                if(isset($findMSMECustomer->MobileNo))
                     $mobileno=$findMSMECustomer->MobileNo;
                else
                   $mobileno=$findMFICustomer->MobileNo;
                
                $checkotp=OtpVerification::find()->where(['MobileNo'=> $mobileno, 'OtpCode'=> $otpcode, 'IsVerified'=> 0, 'IsDelete'=> 0])->orderBy(['Id'=> SORT_DESC])->one();
                if (count($checkotp) > 0) {
                    $record=MsmeExcelData::find()->where(['MobileNo'=> $mobileno, 'LoanAccountNo'=>$loanaccountno,'IsDelete'=>0,'IsApproved'=>1])->orderBy(['Mid'=> SORT_DESC])->one();
                    
                    /*$record=ExcelData::find()->where(['MobileNo'=> $mobile, 'LoanAccountNo'=>$loanaccountno,'IsDelete'=>0])->orderBy(['Eid'=> SORT_DESC])->one();
                    }*/
                  /*$recordtl=Yii::$app->db->createCommand("SELECT Eid as id,RecordId,LoanAccountNo,MobileNo,DemandDate,Type FROM `ExcelData` WHERE `MobileNo`='$mobileno' AND `LoanAccountNo`='$loanaccountno'GROUP BY LoanAccountNo
                                                         UNION ALL
                                                         SELECT  Mid as id,RecordId,LoanAccountNo,MobileNo,DemandDate,Type FROM `MsmeExcelData` WHERE `MobileNo`='$mobileno' AND `LoanAccountNo`='$loanaccountno'GROUP BY LoanAccountNo");
                       $record =$recordtl->queryAll(); */
                       // var_dump($record);die();
                    //$record = ExcelData::find()->where(['MobileNoo' => $mobileno, 'LoanAccountNo' => $loanaccountno])->one();
                    if ($record) {
                        $checkotp->IsVerified=1;

                        if ($checkotp->save()) {
                            $otp=new OtpVerification();
                            $otp->loginsuccessotp($mobileno,$loanaccountno);
                            $session->set('datarecordid', $record->Mid);
                            $session->set('RecordId', $record->RecordId);
                            $session->set('loan', $record->LoanAccountNo);
                            $session->set('type', $record->Type);
                            $session->set('month', $record->DemandDate);
                            $session->set('mobile',$record->MobileNo);
                            return $this->redirect(['home']);
                        }
                        
                    }else{
                        $record=ExcelData::find()->where(['MobileNo'=> $mobileno, 'LoanAccountNo'=>$loanaccountno,'IsDelete'=>0,'IsApproved'=>1])->orderBy(['Eid'=> SORT_DESC])->one();
                        $checkotp->IsVerified=1;

                        if ($checkotp->save()) {
                            $otp=new OtpVerification();
                            $otp->loginsuccessotp($mobileno,$loanaccountno);
                            $session->set('datarecordid', $record->Eid);
                            $session->set('RecordId', $record->RecordId);
                            $session->set('loan', $record->LoanAccountNo);
                            $session->set('type', $record->Type);
                            $session->set('month', $record->DemandDate);
                            $session->set('mobile',$record->MobileNo);
                            return $this->redirect(['home']);
                        }
                    }
                }

                else {
                    $otp=new OtpVerification();
                    $otp->loginfailedotp($mobileno,$loanaccountno);
                    Yii::$app->session->setFlash('error', 'Invalid Otp');


                }
            }

            else {

                Yii::$app->session->setFlash('error', 'Please fill otp');
                return $this->redirect(['login']);
            }

        }
  //var_dump($recordDetail);
        return $this->render('login', ['model'=>$model, 'recordDetail'=>$recordDetail]);
    }





    





    public function actionVerification() {}

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogouts() {
     
       $session = Yii::$app->session;
     $session->open();
     $session->remove('RecordId');
     unset($session['RecordId']);
     unset($session['loan']);
     unset($_SESSION['RecordId']);
     $session->close();
     $session->destroy();
     
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact() {
        $model=new ContactForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            }

            else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        else {
            return $this->render('contact', [ 'model'=> $model,
                ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout() {
        return $this->render('about');
    }

    public function actionPreviouspay($lid) {
        $session=Yii::$app->session;
            $session->open();
            $lid=$session->get('loan');
            
        $pre=TXNDETAILS::find()->where(['LOAN_ID'=>$lid])->all();
        $loan=TXNDETAILS::find()->where(['LOAN_ID'=>$lid])->one();
        return $this->render('previous', ['pre'=>$pre,'loan'=>$loan]);
    }

    public function actionPrint() {
        $date = Yii::$app->request->post('date');
        $userid = Yii::$app->request->post('userid');
        $loanid = Yii::$app->request->post('loanid');
        $txnid=Yii::$app->request->post('id');
        $pre=TXNDETAILS::find()->where(['id'=>$txnid])->one();
        //die();
        $pre_pre=TXNDETAILS::find()->select('sum(TXN_AMT) as TXN_AMT')->where(['TXN_STATUS'=>1,'LOAN_ID'=>$loanid])->andWhere("DATE_FORMAT(TXN_DATE,'%Y-%m') =DATE_FORMAT(NOW(),'%Y-%m')")->andWhere(['<','id',$txnid])->one();
        if ($pre->TYPE == "MSME") {
            $details=MsmeExcelData::find()->where(['LoanAccountNo'=>$loanid,'Mid'=>$userid,'IsDelete'=>0])->one();
        }
        else
        {
            $details=ExcelData::find()->where(['LoanAccountNo'=>$loanid,'Eid'=>$userid,'IsDelete'=>0])->one();
        }
        
        return $this->render('print',['pre'=>$pre,'details'=>$details,'date'=>$date,'pre_pre'=>$pre_pre]);

    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        $model=new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [ 'model'=> $model,
            ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model=new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [ 'model'=> $model,
            ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model=new ResetPasswordForm($token);
        }

        catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [ 'model'=> $model,
            ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token) {
        try {
            $model=new VerifyEmailForm($token);
        }

        catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($user=$model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail() {
        $model=new ResendVerificationEmailForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [ 'model'=> $model]);
    }
    
}