<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
		'css/style.css',
		'css/materialdesignicons.min.css',
		//'css/perfect-scrollbar.min.css',
		'css/font-awesome.min.css',
        'css/jquery-ui.css'
        
    ];
    public $js = [
		//'js/jquery.min.js',
        'js/jquery-ui.js',
		//'js/bootstrap.min.js',
		//'js/perfect-scrollbar.jquery.min.js',
         'js/popper.min.js',
		 'js/off-canvas.js',
		 'js/misc.js',
          'js/jquery.table2excel.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
