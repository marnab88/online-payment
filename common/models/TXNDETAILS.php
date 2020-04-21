<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "TXN_DETAILS".
 *
 * @property int $id
 * @property string|null $TXN_ID
 * @property string|null $SESSION_ID
 * @property string|null $WALLET_USER_CODE
 * @property string $TXN_DATE
 * @property float|null $TXN_AMT
 * @property string|null $TXN_FOR
 * @property string|null $RETURN_URL
 * @property string|null $CANCEL_URL
 * @property string|null $PAYMENT_MODE
 * @property string|null $PAYCORP_TXN_ID
 * @property string $PAYCORP_TXN_DATE
 * @property string|null $PAYCORP_TXN_REMARKS
 * @property string|null $PAYCORP_BANK_REF_NO
 * @property string|null $CHALLAN_NO
 * @property string|null $PG_VENDOR_NAME
 * @property string|null $PG_TXN_ID
 * @property string|null $NACH_PAY_TYPE
 * @property string|null $NACH_PAY_SCHEDULE
 */
class TXNDETAILS extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $WALLET_BANK_REF;
    public $PG_MODE;
    public static function tableName()
    {
        return 'TXN_DETAILS';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TXN_DATE', 'PAYCORP_TXN_DATE'], 'safe'],
            [['TXN_AMT'], 'number'],
            [['TXN_ID', 'WALLET_USER_CODE', 'TXN_FOR', 'PAYCORP_TXN_ID','TYPE','PAYMENT_TYPE'], 'string', 'max' => 50],
            [['SESSION_ID', 'RETURN_URL', 'CANCEL_URL', 'PAYCORP_TXN_REMARKS', 'PAYCORP_BANK_REF_NO', 'CHALLAN_NO', 'PG_VENDOR_NAME', 'PG_TXN_ID', 'NACH_PAY_TYPE', 'NACH_PAY_SCHEDULE'], 'string', 'max' => 150],
            [['PAYMENT_MODE'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'TXN_ID' => 'Txn ID',
            'Type'=>'Type',
            'SESSION_ID' => 'Session ID',
            'WALLET_USER_CODE' => 'Wallet User Code',
            'TXN_DATE' => 'Txn Date',
            'TXN_AMT' => 'Txn Amt',
            'TXN_FOR' => 'Txn For',
            'RETURN_URL' => 'Return Url',
            'CANCEL_URL' => 'Cancel Url',
            'PAYMENT_MODE' => 'Payment Mode',
            'PAYCORP_TXN_ID' => 'Paycorp Txn ID',
            'PAYCORP_TXN_DATE' => 'Paycorp Txn Date',
            'PAYCORP_TXN_REMARKS' => 'Paycorp Txn Remarks',
            'PAYCORP_BANK_REF_NO' => 'Paycorp Bank Ref No',
            'CHALLAN_NO' => 'Challan No',
            'PG_VENDOR_NAME' => 'Pg Vendor Name',
            'PG_TXN_ID' => 'Pg Txn ID',
            'NACH_PAY_TYPE' => 'Nach Pay Type',
            'NACH_PAY_SCHEDULE' => 'Nach Pay Schedule',
        ];
    }
     public function getTransactionres()
    {
        return $this->hasOne(TXNRESPDETAILS::className(), ['TXN_ID' => 'TXN_ID']);
    }
     public function getUsermser()
    {
        return $this->hasOne(MsmeExcelData::className(), ['Mid' => 'USER_ID']);
    }
     public function getUsermfi()
    {
        return $this->hasOne(ExcelData::className(), ['Eid' => 'USER_ID']);
    }
}
