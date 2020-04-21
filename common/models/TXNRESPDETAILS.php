<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "TXN_RESP_DETAILS".
 *
 * @property int $id
 * @property string $TXN_ID
 * @property string $TXN_DATE
 * @property float|null $TXN_AMT
 * @property string|null $TXN_FOR
 * @property string|null $WALLET_USER_CODE
 * @property string|null $WALLET_PAYMENT_MODE
 * @property string|null $WALLET_TXN_STATUS
 * @property string|null $WALLET_TXN_ID
 * @property string|null $PG_TXN_ID
 * @property string|null $PG_NAME
 * @property string|null $PG_MODE
 * @property string|null $WALLET_TXN_DATE
 * @property string|null $WALLET_BANK_REF
 * @property string|null $REMARKS
 * @property string|null $PARAM1
 * @property string|null $PARAM2
 * @property string|null $PARAM3
 * @property string|null $PARAM4
 * @property string|null $PARAM5
 * @property string|null $PARAM6
 * @property string|null $PARAM7
 * @property string|null $PARAM8
 * @property string|null $PARAM9
 * @property string|null $PARAM10
 * @property string|null $LOAN_ID
 * @property string|null $MOB_NO
 * @property string|null $TYPE
 */
class TXNRESPDETAILS extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'TXN_RESP_DETAILS';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TXN_ID'], 'required'],
            [['TXN_DATE', 'WALLET_TXN_DATE'], 'safe'],
            [['TXN_AMT'], 'number'],
            [['TXN_ID'], 'string', 'max' => 100],
            [['TXN_FOR', 'WALLET_USER_CODE', 'MOB_NO'], 'string', 'max' => 50],
            [['WALLET_PAYMENT_MODE', 'WALLET_TXN_STATUS', 'WALLET_TXN_ID', 'PG_TXN_ID', 'PG_NAME', 'PG_MODE', 'WALLET_BANK_REF', 'REMARKS', 'PARAM1', 'PARAM2', 'PARAM3', 'PARAM4', 'PARAM5', 'PARAM6', 'PARAM7', 'PARAM8', 'PARAM9', 'PARAM10', 'LOAN_ID'], 'string', 'max' => 150],
            [['TYPE'], 'string', 'max' => 10],
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
            'TXN_DATE' => 'Txn Date',
            'TXN_AMT' => 'Txn Amt',
            'TXN_FOR' => 'Txn For',
            'WALLET_USER_CODE' => 'Wallet User Code',
            'WALLET_PAYMENT_MODE' => 'Wallet Payment Mode',
            'WALLET_TXN_STATUS' => 'Wallet Txn Status',
            'WALLET_TXN_ID' => 'Wallet Txn ID',
            'PG_TXN_ID' => 'Pg Txn ID',
            'PG_NAME' => 'Pg Name',
            'PG_MODE' => 'Pg Mode',
            'WALLET_TXN_DATE' => 'Wallet Txn Date',
            'WALLET_BANK_REF' => 'Wallet Bank Ref',
            'REMARKS' => 'Remarks',
            'PARAM1' => 'Param1',
            'PARAM2' => 'Param2',
            'PARAM3' => 'Param3',
            'PARAM4' => 'Param4',
            'PARAM5' => 'Param5',
            'PARAM6' => 'Param6',
            'PARAM7' => 'Param7',
            'PARAM8' => 'Param8',
            'PARAM9' => 'Param9',
            'PARAM10' => 'Param10',
            'LOAN_ID' => 'Loan ID',
            'MOB_NO' => 'Mob No',
            'TYPE' => 'Type',
        ];
    }
}
