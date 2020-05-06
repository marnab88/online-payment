<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@storage', dirname(dirname(__DIR__)) . '/storage');

Yii::setAlias('@frontendUrl', "http://128.199.184.65/onlineportal");
Yii::setAlias('@backendUrl',"http://128.199.184.65/onlineportal/backend");
Yii::setAlias('@storageUrl', "http://128.199.184.65/onlineportal/storage");
