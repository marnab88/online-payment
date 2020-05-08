<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoanPaymentForm extends Model
{
    public $loanaccno;
    public $mobileno;
    public $otp;
    public $verifyCode;
    public $verifyCode2;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['loanaccno', 'otp'], 'required'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha','captchaAction' => 'site/captcha'],
            //['verifyCode2', 'captcha','captchaAction' => 'site/captcha2']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mobileno'=>'Mobile Number',
            'loanaccno'  => 'Loan Acc. Number',
            'verifyCode' => 'Verification Code'
        ];
    }
}
