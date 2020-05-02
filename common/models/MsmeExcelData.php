<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "MsmeExcelData".
 *
 * @property int $Mid
 * @property int $RecordId
 * @property string $BranchName
 * @property string $Cluster
 * @property string $State
 * @property string $ClientId
 * @property string $LoanAccountNo
 * @property string $ClientName
 * @property string $MobileNo
 * @property string $EmiSrNo
 * @property string $DemandDate
 * @property string $LastMonthDue
 * @property string $CurrentMonthDue
 * @property string $LatePenalty
 * @property string $NextInstallmentDate
 * @property string $UploadMonth
 * @property string $ProductVertical
 * @property string $OnDate
 * @property string $UpdateDtae
 * @property int $IsDelete
 */
class MsmeExcelData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $totalcustomer;
    public $customerpaid;
    public $amountcollected;
    public static function tableName()
    {
        return 'MsmeExcelData';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'RecordId'], 'required'],
            [['Mid', 'RecordId', 'IsDelete'], 'integer'],
            [['OnDate', 'UpdateDtae','Type','SmsAlert', 'BranchName'], 'safe'],
            [['BranchName', 'Cluster', 'State', 'ClientId', 'LoanAccountNo', 'ClientName', 'MobileNo', 'EmiSrNo', 'LastMonthDue', 'CurrentMonthDue',   'UploadMonth', 'ProductVertical','LatePenalty'], 'string', 'max' => 100],
            
                

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Mid' => 'Mid',
            'RecordId' => 'Record ID',
            'BranchName' => 'Branch Name',
            'Cluster' => 'Cluster',
            'State' => 'State',
            'ClientId' => 'Client ID',
            'LoanAccountNo' => 'Loan Account No',
            'ClientName' => 'Client Name',
            'MobileNo' => 'Mobile No',
            'EmiSrNo' => 'Emi Sr No',
            'DemandDate' => 'Demand Date',
            'LastMonthDue' => 'Last Month Due',
            'CurrentMonthDue' => 'Current Month Due',
            'LatePenalty' => 'Late Penalty',
            'NextInstallmentDate' => 'Next Installment Date',
            'UploadMonth' => 'Upload Month',
            'ProductVertical' => 'Product Vertical',
            'OnDate' => 'On Date',
            'UpdateDtae' => 'Update Dtae',
            'IsDelete' => 'Is Delete',
        ];
    }

    public function getMsmeRecord()
    {
        return $this->hasOne(UploadRecord::className(), ['RecordId' => 'RecordId']);
    }

    public function getPaymentstatus()
    {
        return $this->hasOne(TXNDETAILS::className(), ['USER_ID' => 'Mid']);
    }
   
    /*public function getPaymentdetails()
    {
        return $this->hasOne(TXNDETAILS::className(), ['USER_ID' => 'Mid' ])->select('sum(TXN_AMT) as TXNAMT')->andWhere(TXN_STATUS=>1);
    }*/
    public function branchpayment($RecordId,$BranchName,$fromdate,$todate)
    {
       $cnt=$this->find()->joinWith('paymentstatus')
            ->andWhere(['MsmeExcelData.RecordId'=>$RecordId,'BranchName'=>$BranchName])
            ->select('sum(TXN_DETAILS.TXN_AMT) as amountcollected, count(distinct(TXN_DETAILS.USER_ID)) as customerpaid')
            ->andWhere(['between','TXN_DATE',$fromdate,$todate])
            ->andWhere(['TXN_STATUS'=>1])
            ->one();
       
       return $cnt;
    }
    
}
