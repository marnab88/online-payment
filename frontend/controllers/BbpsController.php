<?php
namespace frontend\controllers;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\ExcelData;
use common\models\MsmeExcelData;
use common\models\TXNDETAILS;
use common\models\TXNRESPDETAILS;


class BbpsController extends Controller {

   

    public function beforeAction($action)
        {
           
            $this->enableCsrfValidation = false;
            return parent::beforeAction($action);
        }

    public function actionFetch(){
        
        $content = file_get_contents('php://input');
        $data =json_decode($content);
        $month=date('Y-m');
        $cnt=0;
        if(isset($data->request->loan_account_no)) {
         $loanno=$data->request->loan_account_no; $data=MsmeExcelData::find()
            ->where(['like', 'DemandDate', $month . '%', false])
            ->andWhere(['IsApproved'=>1])
            ->andFilterWhere(['LoanAccountNo'=>$loanno])->one();
         $cnt=count($data);
         if($cnt==0){
          $data=ExcelData::find()
            ->where(['like', 'DemandDate', $month . '%', false])
            ->andWhere(['IsApproved'=>1])
            ->andFilterWhere(['LoanAccountNo'=>$loanno])->one();
          if($data){
            $userid=$data->Eid; $type='MFI';
            }
          
         }
         else{
          $userid=$data->Mid;
          $type='MSME';
         }
         $cnt=count($data);
        }
        elseif(isset($data->request->mobile_no)){
         if(strlen($data->request->mobile_no)!=10){
             $status='Fail';
            $msg='Invalid Mobile NO';
            $statusCode='212';
         }
        else{
                $mobileno=$data->request->mobile_no; $data=MsmeExcelData::find()
                   ->where(['like', 'DemandDate', $month . '%', false])
                   ->andWhere(['IsApproved'=>1])
                   ->andFilterWhere(['MobileNo'=>$mobileno])->one();
                $cnt=count($data);
                
                if($cnt==0){
                 $data=ExcelData::find()
                   ->where(['like', 'DemandDate', $month . '%', false])
                   ->andWhere(['IsApproved'=>1])
                   ->andFilterWhere(['MobileNo'=>$mobileno])->one();
                   if($data){
                   $userid=$data->Eid; $type='MFI'; }
                       } else{
                       $userid=$data->Mid; $type='MSME';
                   }
               $cnt=count($data);
            }
        }
        else{
            $status='Fail';
            $msg='Invalid request string';
            $statusCode='214';
            }
       $Details=array();
       if($cnt==1){
        $payment_amount=$data->LastMonthDue+$data->CurrentMonthDue+$data->LatePenalty;
        $count=TXNDETAILS::find()->count('LOAN_ID');
        $txn=("AFPL".$data->LoanAccountNo.date('Y-m').sprintf('%02d',$count).rand(1,10));
        $txn=str_replace("-", "", $txn);
        $txninsert=new TXNDETAILS();
        $txninsert->LOAN_ID= $data->LoanAccountNo;
        $txninsert->USER_ID=$userid;
        $txninsert->MONTH=$month;
        $txninsert->TXN_DATE=date('Y-m-d H:i:s');
        $txninsert->TXN_AMT=$payment_amount;
        $txninsert->TXN_FOR="EMI PAYMENT";
        $txninsert->TYPE=$type;
        $txninsert->MOB_NO=$data->MobileNo;
        $txninsert->TXN_ID=$txn;
        if($txninsert->save()){
         $status='SUCCESS'; $statusCode='000';
         $Details=array(
                     "name_of_customer"=>$data->ClientName,
                     "amount_due"=>$payment_amount,
                     "loan_account_no"=>$data->LoanAccountNo,
                     "fetch_reference_id"=>$txn
                        );
        }
       } else{
        $status='invalid account';
        $statusCode='213';
       }
       $statusinfo=array("status"=>$status,"statusCode"=>$statusCode);
       $resp=array("statusInfo"=>$statusinfo,"Details"=>$Details);
       echo json_encode($resp);
    }
    public function actionPayment(){
        $content = file_get_contents('php://input'); 
        $requests=json_decode($content);
        $requests=$requests->request;
        $txnId=$requests->fetch_reference_id;
        $bankTxnNo=$requests->transaction_number;
        $amountPaid=$requests->amount_paid;
        $loanAccountNo=$requests->loan_account_no;
        $txnDetails=new TXNDETAILS();
        $txnDetails=$txnDetails->find()->where(['TXN_ID'=>$txnId,'LOAN_ID'=>$loanAccountNo,'TXN_AMT'=>$amountPaid])->one();
        if($txnDetails)
            {
            if($txnDetails->TXN_STATUS==1){
                $jayParsedAry = [
                            "statusInfo" => [
                                  "status" => "fail", 
                                  "statusCode" => "201", 
                                  "statusText" => "failed" 
                               ], 
                            "receipt_key" => date('Ymd').'x'.'000', 
                            "payment_notification" => "duplicate request" 
                         ]; 
            }
            else{
                if($txnDetails){
                    $txnDetails->PAYMENT_MODE='2';
                    $txnDetails->PAYCORP_BANK_REF_NO=$bankTxnNo;
                    $txnDetails->TXN_STATUS=1;
                    if($txnDetails->save()){
                        $txnRespDetails=new TXNRESPDETAILS();
                        $txnRespDetails->TXN_ID=$txnId;
                        $txnRespDetails->TXN_AMT=$amountPaid;
                        $txnRespDetails->TXN_FOR='EMI PAYMENT';
                        $txnRespDetails->PG_NAME='BBPS';
                        $txnRespDetails->PG_MODE='UPI';
                        $txnRespDetails->WALLET_BANK_REF=$bankTxnNo;
                        $txnRespDetails->LOAN_ID=$loanAccountNo;
                        $txnRespDetails->MOB_NO=$txnDetails->MOB_NO;
                        $txnRespDetails->TYPE=$txnDetails->TYPE;
                        if($txnRespDetails->save())
                        {
                             $jayParsedAry = [
                                "statusInfo" => [
                                      "status" => "SUCCESS", 
                                      "statusCode" => "000", 
                                      "statusText" => "Successful Response received" 
                                   ], 
                                "receipt_key" => date('Ymd').'x'.dechex($txnRespDetails->id), 
                                "payment_notification" => "Payment Successful" 
                             ]; 
                        }
                        else{
                            $jayParsedAry=[
                                "statusInfo" => [
                                      "status" => "fail", 
                                      "statusCode" => "202", 
                                      "statusText" => "failed".json_encode($txnRespDetails->getErrors()) 
                                   ], 
                                "receipt_key" => date('Ymd').'x'.'000', 
                                "payment_notification" => "sorry couldnot recive" 
                             ]; 
                        }
                        
                    }
                    else{
                            $jayParsedAry=[
                                "statusInfo" => [
                                      "status" => "fail", 
                                      "statusCode" => "203", 
                                      "statusText" => "failed".json_encode($txnDetails->getErrors()) 
                                   ], 
                                "receipt_key" => date('Ymd').'x'.'000', 
                                "payment_notification" => "sorry couldnot recive" 
                             ]; 
                        }
                }
            }
        }
        else{
            $jayParsedAry=[
                                "statusInfo" => [
                                      "status" => "fail", 
                                      "statusCode" => "204", 
                                      "statusText" => "failed"
                                   ], 
                                "receipt_key" => date('Ymd').'x'.'000', 
                                "payment_notification" => "sorry couldnot collect correct data" 
                             ]; 
        }
        echo json_encode($jayParsedAry);
    }

 

    
}