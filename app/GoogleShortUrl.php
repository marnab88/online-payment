<?php

namespace common\components;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * This is just an example.
 */
class GoogleURLShortner extends Widget
{
    public $url;
    public $result;
    public $api_key = 'AIzaSyDfaG2JYU0M6Lwb6M5Dr94SxgCMkrmET1Y';
    
    public function init(){
         parent::init();
         if($this->$url===null) {
                $this->result= 'Please provide a URL!';
         }else{
                $request_data = array(
                    'longUrl' => $this->$url
                );
                
                $curl_obj = curl_init(sprintf('%s/url?key=%s', Yii::getAlias('@frontendUrl').'/site/login', $this->api_key));
                curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl_obj, CURLOPT_POST, true);
                curl_setopt($curl_obj, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
                curl_setopt($curl_obj, CURLOPT_POSTFIELDS, json_encode($request_data));
                curl_setopt($curl_obj, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl_obj, CURLOPT_SSL_VERIFYHOST, false);
                
                $response = curl_exec($curl_obj);
                $json = json_decode($response, true);
                curl_close($curl_obj);
                 
                $this->result= $json['id'];
         }
    }

    public function run()
    {
        return Html::encode($this->result);
    }
}