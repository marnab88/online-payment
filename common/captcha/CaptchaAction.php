<?php

namespace common\captcha;

use yii\captcha\CaptchaAction as DefaultCaptchaAction;

class CaptchaAction extends DefaultCaptchaAction
{
    protected function generateVerifyCode()
    {
        if ($this->minLength > $this->maxLength) {
            $this->maxLength = $this->minLength;
        }
        if ($this->minLength < 3) {
            $this->minLength = 4;
        }
        if ($this->maxLength > 4) {
            $this->maxLength = 4;
        }
        $length = mt_rand(3, 5);
        $digits = '0123456789';
        $code = '';
        for ($i = 0; $i < 4; ++$i) {
            $code .= $digits[mt_rand(0, 9)];
        }
        return $code;
    }

}
