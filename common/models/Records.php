<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Records".
 *
 * @property int $SlNo
 * @property string $UserName
 * @property string $UserId
 * @property int $MobileNo
 * @property int $Amount
 * @property string $DueDate
 * @property string $PaymentLink
 * @property int $SmsStatus
 * @property int $WhatsappStatus
 * @property int $PaymentStatus
 * @property string $OnDate
 * @property string $UpdatedDate
 * @property int $IsDelete
 */
class Records extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Records';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['UserName', 'UserId', 'DueDate', 'PaymentLink', 'OnDate'], 'required'],
            [['MobileNo', 'Amount', 'SmsStatus', 'WhatsappStatus', 'PaymentStatus', 'IsDelete'], 'integer'],
            [['DueDate', 'OnDate', 'UpdatedDate'], 'safe'],
            [['UserName', 'UserId'], 'string', 'max' => 30],
            [['PaymentLink'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'SlNo' => 'Sl No',
            'UserName' => 'User Name',
            'UserId' => 'User ID',
            'MobileNo' => 'Mobile No',
            'Amount' => 'Amount',
            'DueDate' => 'Due Date',
            'PaymentLink' => 'Payment Link',
            'SmsStatus' => 'Sms Status',
            'WhatsappStatus' => 'Whatsapp Status',
            'PaymentStatus' => 'Payment Status',
            'OnDate' => 'On Date',
            'UpdatedDate' => 'Updated Date',
            'IsDelete' => 'Is Delete',
        ];
    }
}
