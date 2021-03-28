<?php
/**
 * DateRangePickerAsset.php
 * @author: silentlun
 * @date  2021年3月24日下午2:09:57
 * @copyright  Copyright igkcms
 */
namespace silentlun\daterange;

use Yii;
use yii\web\AssetBundle;


class DateRangePickerAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';
    
    public $css = [
        'css/daterangepicker.min.css',
    ];
    
    public $js = [
        'js/moment.min.js',
        'js/daterangepicker.min.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
    ];
}