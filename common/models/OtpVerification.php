<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%OtpVerification}}".
 *
 * @property int $Id
 * @property string $OtpCode
 * @property int $MobileNo
 * @property string $OtpResponse
 * @property int $IsVerified 0->Not Verified,1->Verified
 * @property string $Ondate
 * @property string $UpdatedDate
 * @property int $IsDelete
 */
class OtpVerification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $User,$Username;
    public static function tableName()
    {
        return '{{%OtpVerification}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['OtpCode', 'MobileNo', 'OtpResponse','Ondate'], 'required'],
            [['MobileNo', 'IsVerified', 'IsDelete'], 'integer'],
            [['Ondate', 'UpdatedDate'], 'safe'],
            [['OtpCode'], 'string', 'max' => 10],
           // [['OtpResponse'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('app', 'ID'),
            'OtpCode' => Yii::t('app', 'Otp Code'),
            'MobileNo' => Yii::t('app', 'Mobile No'),
            'OtpResponse' => Yii::t('app', 'Otp Response'),
            'IsVerified' => Yii::t('app', 'Is Verified'),
            'Ondate' => Yii::t('app', 'Login Time'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
        ];
    }

    
	/*OTP Generate*/
	public function generateOtp($contactno)
    {
		$otpcode = mt_rand(1000, 9999);
        $request = "";
        $param['send_to'] = $contactno;
        $param['method'] = "sendMessage";
        $param['msg'] = "Dear Customer, OTP to update your number at the payment portal of Annapurna Finance Private Limited is :  $otpcode This OTP is valid for 05 minutes.";
        $param['userid'] = "2000183786";
        $param['password'] = "Afpl@786";
        $param['v'] = "1.1";
        $param['msg_type'] = "TEXT";

        foreach ($param as $key => $val) {
            $request .= $key . "=" . urlencode($val);
            $request .= "&";

            // var_dump($request) ;die();
        }
        $request = substr($request, 0, strlen($request) - 1);
        $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?" . $request;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $finalresult = explode("|",$result);
        $finalresult = trim($finalresult[0]);
        if($finalresult === 'success')
        {
            $otpnew = new OtpVerification();
			$otpnew->OtpCode = strval($otpcode);
			$otpnew->MobileNo = $contactno;
			$otpnew->OtpResponse = strval($result);
			$otpnew->Ondate = date('Y-m-d H:i:s');
			if($otpnew->save())
            {
				$response = 1;
			}
			else
            {
				$response = 0;
			}
        }
        else
        {
           $response = 0;
        }		
		return $response;
		
	}
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['MobileNo' => 'MobileNo'])->andOnCondition(['RoleID' => 2]);
    }
	
}
