<?php
/**
 * DateRangePicker.php
 * @author: silentlun
 * @date  2021年3月24日下午2:09:24
 * @copyright  Copyright igkcms
 */
namespace silentlun\daterange;

use Yii;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

class DateRangePicker extends InputWidget
{
    use BootstrapTrait;
    public $pluginOptions;
    /**
     * @var string the javascript callback to be passed to the plugin constructor. Note: a default value is set for
     * this property when you set [[hideInput]] to false, OR you set [[useWithAddon]] to `true` or [[autoUpdateOnInit]]
     * to `false`. If you set a value here it will override any auto-generated callbacks.
     */
    public $callback = null;
    
    /**
     * @var boolean whether to auto update the input on initialization. If set to `false`, this will auto set the
     * `pluginOptions['autoUpdateInput']` to `false`. A default [[callback]] will be auto-generated when this is set to
     * `false`.
     */
    public $autoUpdateOnInit = false;
    
    /**
     * @var boolean whether to hide the input (e.g. when you want to show the date range picker as a dropdown). If set
     * to `true`, the input will be hidden. The plugin will be initialized on a container element (default 'div'),
     * using the container template. A default `callback` will be setup in this case to display the selected range
     * value within the container.
     */
    public $hideInput = false;
    
    /**
     * @var boolean whether you are using the picker with a input group addon. You can set it to `true`, when
     * `hideInput` is false, and you wish to show the picker position more correctly at the input-group addon indicator.
     * A default `callback` will be generated in this case to generate the selected range value for the input.
     */
    public $useWithAddon = false;
    
    /**
     * @var boolean initialize all the list values set in `pluginOptions['ranges']` and convert all values to
     * `yii\web\JsExpression`
     */
    public $initRangeExpr = true;
    
    /**
     * @var boolean show a preset dropdown. If set to true, this will automatically generate a preset list of ranges
     * for selection. Setting this to true will also automatically set `initRangeExpr` to true.
     */
    public $presetDropdown = false;
    
    /**
     * @var boolean whether to add additional preset filter options for days (applicable only when
     * [[presetDropdown]] is `true`). If this is set to `true` following additional preset filter option(s) will
     * be available
     * - **Last {n} Days**
     * where n will be picked up from the list of filter days set via [presetFilterDays]
     */
    public $includeDaysFilter = true;
    
    /**
     * @var string the markup for the calendar picker icon. If not set this will default to:
     * - `<i class="glyphicon glyphicon-calendar"></i>` if [[bsVersion]] is set to `3.x`
     * - `<i class="fas fa-calendar-alt"></i>` if [[bsVersion]] is set to `4.x`
     */
    public $pickerIcon;
    
    /**
     * @var array the HTML attributes for the container, if hideInput is set to true. The following special options
     * are recognized:
     * - `tag`: string, the HTML tag for rendering the container. Defaults to `div`.
     */
    public $containerOptions = [];
    
    /**
     * @var string the attribute name which you can set optionally to track changes to the range start value. One of
     * the following actions will be taken when this is set:
     *  - If using with model, an active hidden input will be automatically generated using this as an attribute name
     *     for the start value of the range.
     *  - If using without model, a normal hidden input will be automatically generated using this as an input name
     *     for the start value of the range.
     */
    public $startAttribute;
    
    /**
     * @var string the attribute name which you can set optionally to track changes to the range end value. One of
     * the following actions will be taken when this is set:
     *  - If using with model, an active hidden input will be automatically generated using this as an attribute name
     *     for the end value of the range.
     *  - If using without model, a normal hidden input will be automatically generated using this as an input name
     *     for the end value of the range.
     */
    public $endAttribute;
    
    /**
     * @var array the HTML attributes for the start input (applicable only if `startAttribute` is set). If using
     * without a model, you can set a start value here within the `value` property.
     */
    public $startInputOptions = [];
    
    /**
     * @var array the HTML attributes for the end input (applicable only if `endAttribute` is set).  If using
     * without a model, you can set an end value here within the `value` property.
     */
    public $endInputOptions = [];
    
    /**
     * @var array (DEPRECATED) the HTML attributes for the `span` element that displays the default value for a preset
     * dropdown. This property is not used any more and the [[containerTemplate]] setting directly can be used to
     * render the preset value input.
     */
    public $defaultPresetValueOptions = [];
    
    
    /**
     * @var array the HTML attributes for the form input
     */
    public $options = ['class' => 'form-control'];
    
    /**
     * @inheritdoc
     */
    public $pluginName = 'daterangepicker';
    
    /**
     * @var string locale language to be used for the plugin
     */
    protected $_localeLang = '';
    
    /**
     * @var string the pluginOptions format for the date time
     */
    protected $_format;
    
    /**
     * @var string the pluginOptions separator
     */
    protected $_separator;
    
    /**
     * @var string the generated input for start attribute when `startAttribute` has been set
     */
    protected $_startInput = '';
    
    /**
     * @var string the generated input for end attribute when `endAttribute` has been set
     */
    protected $_endInput = '';
    
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initSettings();
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        /* $button = Html::button('<i class="fa fa-calendar"></i> <span>1 Jun 2020 - 7 Jun 2020</span> <b class="caret"></b>', $this->options);
        
        $input = $this->hasModel()
        ? Html::activeTextInput($this->model, $this->attribute, $this->options)
        : Html::textInput($this->name, $this->value, $this->options);
        
        if ($this->addon) {
            if ($this->isBs4()) {
                $addonText = Html::tag('span', $this->addon, ['class' => 'input-group-text']);
                $addon = Html::tag('div', $addonText, ['class' => $this->addonType == 'left' ? 'input-group-prepend' : 'input-group-append']);
            } else {
                $addon = Html::tag('span', $this->addon, ['class' => 'input-group-addon']);
            }
            $input = strtr($this->template, ['{input}' => $input, '{addon}' => $addon]);
            $input = Html::tag('div', $input, $this->containerOptions);
        } */
        //echo $input;
        
        //echo $this->renderInput();
        //echo $this->renderDropdown();
        echo $this->renderInput();
    }
    
    /**
     * Registers required script for the plugin to work as DatePicker
     */
    public function registerClientScript()
    {
        $js = [];
        $view = $this->getView();
        DateRangePickerAsset::register($view);
        
        $selector = "jQuery('#{$this->options['id']}')";
        

        $this->pluginOptions = ArrayHelper::merge([
            'startDate' => new JsExpression("moment().subtract(7, 'days')"),
            'endDate' => new JsExpression("moment()"),
            'showDropdowns' => true,
        ], $this->pluginOptions);
        $options = Json::encode($this->pluginOptions);
        
        /* if (empty($this->callback)) {
            $val = "start.format('{$this->_format}') + '{$this->_separator}' + end.format('{$this->_format}')";
            if (ArrayHelper::getValue($this->pluginOptions, 'singleDatePicker', false)) {
                $val = "start.format('{$this->_format}')";
            }
            $rangeJs = $this->getRangeJs('start') . $this->getRangeJs('end');
            $change = "{$selector}.val(val).trigger('change');{$rangeJs}";
            $script = "var val={$val};{$change}";
            $this->callback = "function(start,end,label){{$script}}";
        } */
        
        //$js[] = "{$selector}.daterangepicker({$options},{$this->callback});";
        
        $val = "start.format('{$this->_format}') + '{$this->_separator}' + end.format('{$this->_format}')";
        if (ArrayHelper::getValue($this->pluginOptions, 'singleDatePicker', false)) {
            $val = "start.format('{$this->_format}')";
        }
        $rangeJs = $this->getRangeJs('start') . $this->getRangeJs('end');
        $change = "{$selector}.val(val).trigger('change');{$rangeJs}";
        $script = "var val={$val};{$change}";
        
        $js[] = "{$selector}.daterangepicker({$options},function(start,end,label){{$script}{$this->callback}});";
        if ($this->presetDropdown) {
            $js[] .= <<< JS
        {$selector}.on('apply.daterangepicker', function(ev, picker) {
        {$selector}.find('span').html(picker.startDate.format('YYYY-MM-DD') + ' {$this->_separator} ' + picker.endDate.format('YYYY-MM-DD'));
});
JS;
        }
        /* $js[] .= <<< JS
        {$selector}.on('apply.daterangepicker', function(ev, picker) {
$('#{$id} span').html(picker.startDate.format('YYYY-MM-DD') + ' {$this->_separator} ' + picker.endDate.format('YYYY-MM-DD'));
console.log(picker.startDate.format('YYYY-MM-DD'));
console.log(picker.endDate.format('YYYY-MM-DD'));
console.log(moment().subtract(7, 'days'));
});
JS; */
        $view->registerJs(implode("\n", $js));
    }
    
    /**
     * Initializes widget settings
     *
     * @throws InvalidConfigException
     * @throws \ReflectionException
     */
    protected function initSettings()
    {
        $isBs4 = $this->isBs4();
        
        if (!isset($this->pickerIcon)) {
            //$iconCss = $isBs4 ? 'fa fa-calendar' : 'glyphicon glyphicon-calendar';
            //$this->pickerIcon = Html::tag('i', '', ['class' => $iconCss]);
            $this->pickerIcon = Html::tag('i', '', ['class' => 'fa fa-calendar']);
        }
        if (!isset($this->pluginOptions['cancelButtonClasses'])) {
            $this->pluginOptions['cancelButtonClasses'] = $isBs4 ? 'btn-secondary' : 'btn-default';
        }
        
        $this->initLocale();
        
        $locale = ArrayHelper::getValue($this->pluginOptions, 'locale', []);
        $this->_format = ArrayHelper::getValue($locale, 'format', 'YYYY-MM-DD');
        $this->_separator = ArrayHelper::getValue($locale, 'separator', ' - ');
        if (!empty($this->value)) {
            $dates = explode($this->_separator, $this->value);
            if (count($dates) > 1) {
                $this->pluginOptions['startDate'] = $dates[0];
                $this->pluginOptions['endDate'] = $dates[1];
                $this->initRangeValue('start', $dates[0]);
                $this->initRangeValue('end', $dates[1]);
            }
            if ($this->startAttribute && $this->endAttribute) {
                $start = $this->getRangeValue('start');
                $end = $this->getRangeValue('end');
                $this->value = $start . $this->_separator . $end;
                if ($this->hasModel()) {
                    $attr = Html::getAttributeName($this->attribute);
                    $this->model->$attr = $this->value;
                }
                $this->pluginOptions['startDate'] = $start;
                $this->pluginOptions['endDate'] = $end;
            }
        }
        
        // Set `autoUpdateInput` to false for certain settings
        if (!$this->autoUpdateOnInit) {
            $this->pluginOptions['autoUpdateInput'] = false;
        }
        $this->_startInput = $this->getRangeInput('start');
        $this->_endInput = $this->getRangeInput('end');
        
        $this->initRange();
        $this->registerClientScript();
    }
    
    /**
     * Initialize locale settings
     * @throws \ReflectionException
     */
    protected function initLocale()
    {
        
        $localeSettings = ArrayHelper::getValue($this->pluginOptions, 'locale', []);
        $localeSettings += [
            'applyLabel' => '确定',
            'cancelLabel' => '取消',
            'fromLabel' => '从',
            'toLabel' => '到',
            'weekLabel' => '周',
            'customRangeLabel' => '自选日期',
            'daysOfWeek' => new JsExpression('moment.weekdaysMin()'),
            'monthNames' => new JsExpression('moment.monthsShort()'),
            'firstDay' => new JsExpression('moment.localeData()._week.dow'),
        ];
        $this->pluginOptions['locale'] = $localeSettings;
    }
    
    /**
     * Initializes the pluginOptions range list
     * @throws InvalidConfigException
     */
    protected function initRange()
    {
        if ($this->presetDropdown) {
            $m = 'moment()';
            $this->initRangeExpr = $this->hideInput = true;
            $beg = "{$m}.startOf('day')";
            $end = "{$m}.endOf('day')";
            $last = "{$m}.subtract(1, 'month')";
            if (!isset($this->pluginOptions['ranges'])) {
                $this->pluginOptions['ranges'] = [
                    '今天' => [$beg, $end],
                    '昨天' => ["{$beg}.subtract(1,'days')", "{$end}.subtract(1,'days')"],
                    '本周' => ["{$m}.startOf('week')", "{$m}.endOf('week')"],
                    '本月' => ["{$m}.startOf('month')", "{$m}.endOf('month')"],
                    '上个月' => ["{$last}.startOf('month')", "{$last}.endOf('month')"],
                    ];
            }
            if (empty($this->value)) {
                $this->pluginOptions['startDate'] = new JsExpression("{$m}.startOf('day')");
                $this->pluginOptions['endDate'] = new JsExpression($m);
            }
        }
        $opts = $this->pluginOptions;
        if (!$this->initRangeExpr || empty($opts['ranges']) || !is_array($opts['ranges'])) {
            return;
        }
        $range = [];
        foreach ($opts['ranges'] as $key => $value) {
            if (!is_array($value) || empty($value[0]) || empty($value[1])) {
                throw new InvalidConfigException(
                    "Invalid settings for pluginOptions['ranges']. Each range value must be a two element array."
                    );
            }
            $range[$key] = [static::parseJsExpr($value[0]), static::parseJsExpr($value[1])];
        }
        $this->pluginOptions['ranges'] = $range;
    }
    
    /**
     * Renders the input
     *
     * @return string
     */
    protected function renderDropdown()
    {
        $isBs4 = $this->isBs4();
        $this->options['class'] = 'btn';
        Html::addCssClass($this->options, ['class' => $isBs4 ? 'btn-outline-secondary' : 'btn-default']);
        $button = Html::button($this->pickerIcon.' <span>'.$this->value.'</span> <b class="caret"></b>', $this->options);
        return $button;
    }
    
    /**
     * Renders the input
     *
     * @return string
     */
    protected function renderInput()
    {
        $isBs4 = $this->isBs4();
        $append = $this->_startInput . $this->_endInput;
        if ($this->presetDropdown) {
            $this->options['class'] = 'btn';
            Html::addCssClass($this->options, ['class' => $isBs4 ? 'btn-outline-secondary' : 'btn-default']);
            return Html::button($this->pickerIcon.' <span>'.$this->value.'</span> <b class="caret"></b>', $this->options) . $append;
        }
        $addon = Html::tag('span', $this->pickerIcon, ['class' => 'input-group-addon']);
        //$input = strtr($this->template, ['{input}' => $input, '{addon}' => $addon]);
        $input = $this->renderInputHtml('text');
        return Html::tag('div', $input . $addon . $append, ['class' => 'input-group']);
    }
    
    /**
     * Gets input options based on type
     *
     * @param string $type whether `start` or `end`
     *
     * @return array|mixed
     */
    protected function getInputOpts($type = '')
    {
        $opts = $type . 'InputOptions';
        return isset($this->$opts) && is_array($this->$opts) ? $this->$opts : [];
    }
    
    /**
     * Sets input options for a specific type
     *
     * @param string $type whether `start` or `end`
     * @param array $options the options to set
     */
    protected function setInputOpts($type = '', $options = [])
    {
        $opts = $type . 'InputOptions';
        if (property_exists($this, $opts)) {
            $this->$opts = $options;
        }
    }
    
    /**
     * Gets the range attribute value based on type
     *
     * @param string $type whether `start` or `end`
     *
     * @return mixed|string
     */
    protected function getRangeAttr($type = '')
    {
        $attr = $type . 'Attribute';
        return $type && isset($this->$attr) ? $this->$attr : '';
    }
    
    /**
     * Generates and returns the client script on date range change, when the start and end attributes are set
     *
     * @param string $type whether `start` or `end`
     *
     * @return string
     */
    protected function getRangeJs($type = '')
    {
        $rangeAttr = $this->getRangeAttr($type);
        if (empty($rangeAttr)) {
            return '';
        }
        $options = $this->getInputOpts($type);
        $input = "jQuery('#" . $this->options['id'] . "')";
        return "var v={$input}.val() ? {$type}.format('{$this->_format}') : '';jQuery('#" . $options['id'] .
        "').val(v).trigger('change');";
    }
    
    /**
     * Generates and returns the hidden input markup when one of start or end attributes are set.
     *
     * @param string $type whether `start` or `end`
     *
     * @return string
     */
    protected function getRangeInput($type = '')
    {
        $attr = $this->getRangeAttr($type);
        if (empty($attr)) {
            return '';
        }
        $options = $this->getInputOpts($type);
        if (empty($options['id'])) {
            $options['id'] = $this->options['id'] . '-' . $type;
        }
        if ($this->hasModel()) {
            $this->setInputOpts($type, $options);
            return Html::activeHiddenInput($this->model, $attr, $options);
        }
        $options['type'] = 'hidden';
        $options['name'] = $attr;
        $this->setInputOpts($type, $options);
        return Html::tag('input', '', $options);
    }
    
    /**
     * Initializes the range values when one of start or end attributes are set.
     *
     * @param string $type whether `start` or `end`
     * @param string $value the value to set
     */
    protected function initRangeValue($type = '', $value = '')
    {
        $attr = $this->getRangeAttr($type);
        if (empty($attr) || empty($value)) {
            return;
        }
        if ($this->hasModel()) {
            $this->model->$attr = $value;
        } else {
            $options = $this->getInputOpts($type);
            $options['value'] = $value;
            $this->setInputOpts($type, $options);
        }
    }
    
    /**
     * Generates and returns the hidden input markup when one of start or end attributes are set.
     *
     * @param string $type whether `start` or `end`
     *
     * @return string
     */
    protected function getRangeValue($type = '')
    {
        $attr = $this->getRangeAttr($type);
        if (empty($attr)) {
            return '';
        }
        $options = $this->getInputOpts($type);
        return $this->hasModel() ? Html::getAttributeValue($this->model, $attr) :
        ArrayHelper::getValue($options, 'value', '');
    }
    
    /**
     * Parses and returns a JsExpression
     *
     * @param string|JsExpression $value
     *
     * @return JsExpression
     */
    protected static function parseJsExpr($value)
    {
        return $value instanceof JsExpression ? $value : new JsExpression($value);
    }

}