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

class BbpsController extends Controller
{



    public function beforeAction($action)
    {

        $this->enableCsrfValidation = false;
        $header_list = new DumpHTTPRequestToFile();
        $headers_list = $header_list->getHeaderList();
        $ip = $this->get_client_ip();
        if (isset($headers_list['Afpl-Key']) && $headers_list['Afpl-Key'] == '0a6T8t19os90KvzRhgY10Jul5tknv1Bs') {
            return parent::beforeAction($action);
        } else {
            $statusInfo = [
                "status" => "fail",
                "statusCode" => "AFPL104",
                "statusText" => "Your IP is not whitelisted/invalid token. Please contact admin,"
            ];
            $resp = array("statusInfo" => $statusInfo, "Details" => array());
            echo json_encode($resp);
            die();
        }
    }

    public function actionFetch()
    {
        $targetFile = Yii::getAlias('@frontend') . '/web/assets/dump/dumprequest' . date('Y-m') . '.txt';
        $content = file_get_contents('php://input');
        $data = json_decode($content);
        $month = date('Y-m');
        $cnt = 0;
        if (isset($data->request->loan_account_no)) {
            $loanno = $data->request->loan_account_no;
            $loanno=str_replace('-','',$loanno);
            if(strlen($loanno)<6){
                
                $status = 'Fail';
            $msg = 'Invalid LoanAccount number. Required minimum last 6 char of loanAccountNumber';
            $statusCode = 'BFR002';
            }
            else{
            $data = MsmeExcelData::find()
                ->where(['like', 'DemandDate', $month . '%', false])
                ->andWhere(['IsApproved' => 1])
                ->andWhere("replace(LoanAccountNo, '-','')='$loanno' or replace(LoanAccountNo, '-','') like '%$loanno'")->one();
            $cnt = count($data);
            if ($cnt == 0) {
                $data = ExcelData::find()
                    ->where(['like', 'DemandDate', $month . '%', false])
                    ->andWhere(['IsApproved' => 1])
                    ->andWhere("replace(LoanAccountNo, '-','')='$loanno' or replace(LoanAccountNo, '-','') like '%$loanno'")->one();
                if ($data) {
                    $userid = $data->Eid;
                    $type = 'MFI';
                }
            } else {
                $userid = $data->Mid;
                $type = 'MSME';
            }
            $cnt = count($data);
            }
            
        } elseif (isset($data->request->mobile_no)) {
            if (strlen($data->request->mobile_no) != 10) {
                $status = 'Fail';
                $msg = 'Invalid Mobile NO';
                $statusCode = 'BFR002';
            } else {
                $mobileno = $data->request->mobile_no;
                $data = MsmeExcelData::find()
                    ->where(['like', 'DemandDate', $month . '%', false])
                    ->andWhere(['IsApproved' => 1])
                    ->andFilterWhere(['MobileNo' => $mobileno])->one();
                $cnt = count($data);

                if ($cnt == 0) {
                    $data = ExcelData::find()
                        ->where(['like', 'DemandDate', $month . '%', false])
                        ->andWhere(['IsApproved' => 1])
                        ->andFilterWhere(['MobileNo' => $mobileno])->one();
                    if ($data) {
                        $userid = $data->Eid;
                        $type = 'MFI';
                    }
                } else {
                    $userid = $data->Mid;
                    $type = 'MSME';
                }
                $cnt = count($data);
            }
        } else {
            $status = 'Fail';
            $msg = 'Invalid combination of customer parameters';
            $statusCode = 'BFR002';
        }
        $Details = array();
        if ($cnt == 1) {
            $paid = TXNDETAILS::find()->where(['TXN_STATUS' => 1, 'LOAN_ID' => $data->LoanAccountNo])->select('sum(TXN_AMT) as TXN_AMT')->one();
            if (!$paid) {
                $paid = 0;
            } else {
                $paid = $paid->TXN_AMT;
            }
            $payment_amount = $data->LastMonthDue + $data->CurrentMonthDue + $data->LatePenalty - $paid;
            if ($payment_amount == 0) {
                $statusInfo = [
                    "status" => "fail",
                    "statusCode" => "BFR004",
                    "statusText" => "Payment received for the billing period - no bill due"
                ];
                $resp = array("statusInfo" => $statusInfo, "Details" => array());
                echo json_encode($resp);
                die();
            }
            $count = TXNDETAILS::find()->count('LOAN_ID');
            $txn = ("AFPL" . $data->LoanAccountNo . date('Y-m') . sprintf('%02d', $count) . rand(1, 10));
            $txn = str_replace("-", "", $txn);
            $txninsert = new TXNDETAILS();
            $txninsert->LOAN_ID = $data->LoanAccountNo;
            $txninsert->USER_ID = $userid;
            $txninsert->MONTH = $month;
            $txninsert->TXN_DATE = date('Y-m-d H:i:s');
            $txninsert->TXN_AMT = $payment_amount;
            $txninsert->TXN_FOR = "EMI PAYMENT";
            $txninsert->TYPE = $type;
            $txninsert->MOB_NO = $data->MobileNo;
            $txninsert->TXN_ID = $txn;
            if ($txninsert->save()) {
                $status = 'SUCCESS';
                $statusCode = '000';
                $Details = array(
                    "name_of_customer" => $data->ClientName,
                    "amount_due" => $payment_amount,
                    "loan_account_no" => $data->LoanAccountNo,
                    "fetch_reference_id" => $txn
                );
            }
        } else {
            echo $cnt;
            $status = 'Incorrect / invalid customer account';
            $statusCode = 'BFR001';
        }
        $statusinfo = array("status" => $status, "statusCode" => $statusCode);
        $resp = array("statusInfo" => $statusinfo, "Details" => $Details);
        file_put_contents(
            $targetFile,
            json_encode($resp) . "\n",
            FILE_APPEND
        );
        echo json_encode($resp);
    }
    public function actionPayment()
    {
        $targetFile = Yii::getAlias('@frontend') . '/web/assets/dump/dumprequest' . date('Y-m') . '.txt';
        $content = file_get_contents('php://input');
        $requests = json_decode($content);
        $requests = $requests->request;
        $txnId = $requests->fetch_reference_id;
        $bankTxnNo = $requests->transaction_number;
        $amountPaid = $requests->amount_paid;
        $loanAccountNo = $requests->loan_account_no;
        $txnDetails = new TXNDETAILS();
        $txnDetails = $txnDetails->find()->where(['TXN_ID' => $txnId, 'LOAN_ID' => $loanAccountNo, 'TXN_AMT' => $amountPaid])->one();
        $txnRespDetails_ = TXNRESPDETAILS::find()->where(['PG_TXN_ID' => $bankTxnNo])->one();
        if ($txnDetails) {
            if ($txnDetails->TXN_STATUS == 1 || count($txnRespDetails_) > 0) {
                $jayParsedAry = [
                    "statusInfo" => [
                        "status" => "fail",
                        "statusCode" => "BPR007",
                        "statusText" => "failed"
                    ],
                    "receipt_key" => date('Ymdhis') . 'x' . '000',
                    "payment_notification" => "duplicate request"
                ];
            } else {
                if ($txnDetails) {
                    $txnDetails->PAYMENT_MODE = '2';
                    $txnDetails->PAYCORP_BANK_REF_NO = $bankTxnNo;
                    $txnDetails->TXN_STATUS = 1;
                    if ($txnDetails->save()) {
                        $txnRespDetails = new TXNRESPDETAILS();
                        $txnRespDetails->TXN_ID = $txnId;
                        $txnRespDetails->TXN_AMT = $amountPaid;
                        $txnRespDetails->TXN_FOR = 'EMI PAYMENT';
                        $txnRespDetails->PG_NAME = 'BBPS';
                        $txnRespDetails->PG_MODE = 'UPI';
                        $txnRespDetails->PG_TXN_ID = $bankTxnNo;
                        $txnRespDetails->WALLET_BANK_REF = $bankTxnNo;
                        $txnRespDetails->WALLET_TXN_STATUS = '100';
                        $txnRespDetails->WALLET_TXN_DATE = date('Y-m-d H:i:s');
                        $txnRespDetails->LOAN_ID = $loanAccountNo;
                        $txnRespDetails->MOB_NO = $txnDetails->MOB_NO;
                        $txnRespDetails->TYPE = $txnDetails->TYPE;
                        if ($txnRespDetails->save()) {
                            $jayParsedAry = [
                                "statusInfo" => [
                                    "status" => "SUCCESS",
                                    "statusCode" => "000",
                                    "statusText" => "Successful Response received"
                                ],
                                "receipt_key" => date('Ymd') . 'x' . dechex($txnRespDetails->id),
                                "payment_notification" => "Payment Successful"
                            ];
                        } else {
                            $jayParsedAry = [
                                "statusInfo" => [
                                    "status" => "fail",
                                    "statusCode" => "BPR005",
                                    "statusText" => "failed " . json_encode($txnRespDetails->getErrors())
                                ],
                                "receipt_key" => date('Ymdhis') . 'x' . '000',
                                "payment_notification" => "Payment cannot be accepted at this time"
                            ];
                        }
                    } else {
                        $jayParsedAry = [
                            "statusInfo" => [
                                "status" => "fail",
                                "statusCode" => "BPR005",
                                "statusText" => "failed- " . json_encode($txnDetails->getErrors())
                            ],
                            "receipt_key" => date('Ymdhis') . 'x' . '000',
                            "payment_notification" => "Payment cannot be accepted at this time"
                        ];
                    }
                }
            }
        } else {
            $jayParsedAry = [
                "statusInfo" => [
                    "status" => "fail",
                    "statusCode" => "BPR001",
                    "statusText" => "failed"
                ],
                "receipt_key" => date('Ymd his') . 'x' . '000',
                "payment_notification" => "Incorrect / invalid customer account"
            ];
        }
        file_put_contents(
            $targetFile,
            json_encode($jayParsedAry) . "\n",
            FILE_APPEND
        );
        echo json_encode($jayParsedAry);
    }

    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CF_CONNECTING_IP'))
            $ipaddress = getenv('HTTP_CF_CONNECTING_IP');
        else if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}

class DumpHTTPRequestToFile
{

    public function execute($targetFile)
    {
        header('Status: 200');
        $data = sprintf(
            "%s %s %s\n\nHTTP headers:\n",
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_SERVER['SERVER_PROTOCOL']
        );

        foreach ($this->getHeaderList() as $name => $value) {
            $data .= $name . ': ' . $value . "\n";
        }
        $data .= 'timestamp : ' . round(microtime(true) * 1000);

        $data .= "\nRequest body:\n";

        if (file_put_contents(
            $targetFile,
            $data . file_get_contents('php://input') . "\n",
            FILE_APPEND
        )) {
            //echo("Done!\n\n");
        } else {
            echo 'fail';
        }
    }
    public function getHeaderList()
    {

        $headerList = [];
        foreach ($_SERVER as $name => $value) {
            if (preg_match('/^HTTP_/', $name)) {
                // convert HTTP_HEADER_NAME to Header-Name
                $name = strtr(substr($name, 5), '_', ' ');
                $name = ucwords(strtolower($name));
                $name = strtr($name, ' ', '-');

                // add to list
                $headerList[$name] = $value;
            }
        }

        return $headerList;
    }
}
(new DumpHTTPRequestToFile)->execute(Yii::getAlias('@frontend') . '/web/assets/dump/dumprequest' . date('Y-m') . '.txt');
