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
class BbapiController extends Controller {

    /**
     * {@inheritdoc}
     */
    // public function behaviors() {
    //     return [ 'access'=>[ 'class'=>AccessControl::className(),
    //         'only'=> ['logout', 'signup'],
    //         'rules'=> [ 
    //         [ 'actions'=> ['signup'],
    //         'allow'=> true,
    //         'roles'=> ['?'],
    //         ],
    //         [ 'actions'=> ['logout'],
    //         'allow'=> true,
    //         'roles'=> ['@'],
    //         ],
    //         ],
    //         ],
    //         'verbs'=> [ 'class'=> VerbFilter::className(),
    //         'actions'=> [ 'logout'=> ['post'],
    //         ],
    //         ],
    //         ];
    // }

    public function beforeAction($action)
    {            

        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionFetch(){

        $content = file_get_contents('php://input');
        $data = json_decode($content);
        $result=array();
        foreach ($data as $key => $value) {
            $checkvalue=$value;
        }
        if (strlen($checkvalue)>10) {
            $loanno=$checkvalue;
        }
        if (strlen($checkvalue)==10) {
            $mobile=$checkvalue;
        }
        $date=date('Y-m');
        if ($loanno) {
            $errorloanaccno=MsmeExcelData::find()
                ->where(['like', 'date_format(`DemandDate`, "%Y-%m")', $date . '%', false])
                ->andFilterWhere(['LoanAccountNo'=>$loanno])
                ->count();

            $errorloanaccno1=ExcelData::find()
                ->where(['like', 'date_format(`DemandDate`, "%Y-%m")', $date . '%', false])
                ->andFilterWhere(['LoanAccountNo'=>$loanno])
                ->count();
        }

        if ($errorloanaccno>1) {
            $errormsg.='LoanAccountNo number is invalid.';
        }

        if ($errorloanaccno1>1) {
            $errormsg.='LoanAccountNo number is invalid.';
        }

        $where=where(['like', 'date_format(`DemandDate`, "%Y-%m")', $date . '%', false])->andWhere(['LoanAccountNo'=> $loanno,'IsDelete'=>0,'IsApproved'=>1])->one();
        if ($errorloanaccno =='') {
            if ($loanno) {
                $getalldata=MsmeExcelData::find()->$where;
                if(!$getalldata){
                    $getalldata=ExcelData::find()->$where;
                }
            }
        }

        $mobwhere=where(['like', 'date_format(`DemandDate`, "%Y-%m")', $date . '%', false])->andWhere(['MobileNo'=> $mobile,'IsDelete'=>0,'IsApproved'=>1])->one();
        if ($mobile) {
            $getalldata=MsmeExcelData::find()->$mobwhere;
            if (!$getalldata) {
             $getalldata=ExcelData::find()->$mobwhere; 
         }
     }

     $result['statusinfo']=['status'  => 'success','statusCode' => 000];
     $userid=($getalldata->Mid)?$getalldata->Mid:$getalldata->Eid;
     $name=$getalldata->ClientName;
     $totaldue=$getalldata->LastMonthDue+$getalldata->CurrentMonthDue+$getalldata->LatePenalty;
     $paidamnt=TXNDETAILS::find()
         ->select('sum(TXN_AMT) as amountcollected')
         ->andWhere(['USER_ID'=>$userid,'TXN_STATUS'=>1])
         ->andWhere(['like', 'date_format(`DemandDate`, "%Y-%m")', $date . '%', false])
         ->one();
     $amountdue=$totaldue-$paidamnt->amountcollected;
     $loanacno=$getalldata->LoanAccountNo;
     $referenceid=($getalldata->Mid)?$getalldata->paymentstatus->TXN_ID:$getalldata->payment->TXN_ID;
     $result['Details']=['name_of_customer'=>$name,'amount_due'=>$amountdue,'loan_account_no'=>$loanacno,'fetch_reference_id'=>$referenceid]

     echo json_encode($result);
 }



 public function actionHome() {
    $session=Yii::$app->session;
    $session->open();

    $getrefresh= (Yii::$app->request->get('refresh')) ? Yii::$app->request->get('refresh') : '';
    $recordid=$session->get('datarecordid');
    $RecordId=$session->get('RecordId');
    $loan=$session->get('loan');
    $type=$session->get('type');
    $month=$session->get('month');
    $userid=$session->get('datarecordid');
    $mobile=$session->get('mobile');

    if ($type == 'MSME') {
        $getmmob=MsmeExcelData::find()->where(['LoanAccountNo'=>$loan,'IsApproved'=>1])->orderBy(['Mid' => SORT_DESC])->one();
    }else {
       $getmmob=ExcelData::find()->where(['LoanAccountNo'=>$loan,'IsApproved'=>1])->orderBy(['Eid' => SORT_DESC])->one();

   }
   $currentob=$getmmob->MobileNo;



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
   $model->MOB_NO=$currentob;

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


}