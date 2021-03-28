<?php
/**
 * BootstrapTrait.php
 * @author: silentlun
 * @date  2021年3月25日上午10:57:14
 * @copyright  Copyright igkcms
 */
namespace silentlun\daterange;

use Yii;
use yii\helpers\ArrayHelper;

trait BootstrapTrait
{
    /**
     * @var int|string the bootstrap library version.
     *
     * To use with bootstrap 3 - you can set this to any string starting with 3 (e.g. `3` or `3.3.7` or `3.x`)
     * To use with bootstrap 4 - you can set this to any string starting with 4 (e.g. `4` or `4.1.1` or `4.x`)
     *
     * This property can be set up globally in Yii application params in your Yii2 application config file.
     *
     * For example:
     * `Yii::$app->params['bsVersion'] = '4.x'` to use with Bootstrap 4.x globally
     *
     * If this property is set, this setting will override the `Yii::$app->params['bsVersion']`. If this is not set, and
     * `Yii::$app->params['bsVersion']` is also not set, this will default to `3.x` (Bootstrap 3.x version).
     */
    public $bsVersion;
    
    /**
     * @var string default bootstrap button CSS
     */
    protected $_defaultBtnCss;
    
    /**
     * @var bool flag to detect whether bootstrap 4.x version is set
     */
    protected $_isBs4;
    
    
    /**
     * Configures the bootstrap version settings
     * @return string the bootstrap lib parsed version number
     */
    protected function configureBsVersion()
    {
        $v = empty($this->bsVersion) ? ArrayHelper::getValue(Yii::$app->params, 'bsVersion', '3') : $this->bsVersion;
        $ver = static::parseVer($v);
        $this->_isBs4 = $ver === '4';
        return $ver;
    }
    
    /**
     * Validate if Bootstrap 4.x version
     * @return bool
     *
     * @throws InvalidConfigException
     */
    public function isBs4()
    {
        if (!isset($this->_isBs4)) {
            $this->configureBsVersion();
        }
        return $this->_isBs4;
    }
    
    /**
     * Gets the default button CSS
     * @return string
     */
    public function getDefaultBtnCss()
    {
        return $this->_defaultBtnCss;
    }
    
    /**
     * Parses and returns the major BS version
     * @param string $ver
     * @return bool|string
     */
    protected static function parseVer($ver)
    {
        $ver = (string)$ver;
        return substr(trim($ver), 0, 1);
    }
    
    /**
     * Compares two versions and checks if they are of the same major BS version
     * @param int|string $ver1 first version
     * @param int|string $ver2 second version
     * @return bool whether major versions are equal
     */
    protected static function isSameVersion($ver1, $ver2)
    {
        if ($ver1 === $ver2 || (empty($ver1) && empty($ver2))) {
            return true;
        }
        return static::parseVer($ver1) === static::parseVer($ver2);
    }
}