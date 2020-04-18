<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ExcelData".
 *
 * @property int $Eid
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
 * @property string|null $SpouseName
 * @property string|null $Village
 * @property string|null $Center
 * @property string|null $GroupName
 * @property string $UploadMonth
 * @property string $ProductVertical
 * @property string $OnDate
 * @property string $UpdateDate
 * @property int $IsDelete
 */
class ExcelData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ExcelData';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['RecordId'], 'required'],
            [['RecordId', 'IsDelete'], 'integer'],
            [['OnDate', 'UpdateDate','Type', 'BranchName'], 'safe'],
            [['BranchName', 'Cluster', 'State', 'ClientId', 'LoanAccountNo', 'ClientName', 'MobileNo', 'EmiSrNo', 'LastMonthDue', 'CurrentMonthDue', 'LatePenalty', 'SpouseName', 'VillageName', 'Center', 'GroupName'], 'string', 'max' => 100],
            // [['MobileNo'],'match', 'pattern' => '/((\+[0-9]{6})|0)[-]?[0-9]{7}/'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Eid' => 'Eid',
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
            'SpouseName' => 'Spouse Name',
            'Village' => 'Village',
            'Center' => 'Center',
            'GroupName' => 'Group Name',
            'UploadMonth' => 'Upload Month',
            'ProductVertical' => 'Product Vertical',
            'OnDate' => 'On Date',
            'UpdateDate' => 'Update Date',
            'IsDelete' => 'Is Delete',
        ];
    }

    public function getRecord()
    {
        return $this->hasOne(UploadRecord::className(), ['RecordId' => 'RecordId']);
    }
     public function getPayment()
    {
        return $this->hasOne(TXNDETAILS::className(), ['USER_ID' => 'Eid']);
    }
}
