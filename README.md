silentlun\yii2-daterangepicker
====================
DateRangePicker extension for YII2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer require --prefer-dist silentlun/yii2-daterangepicker "*"
```

or add

```
"silentlun/yii2-daterangepicker": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
use silentlun\daterange\DateRangePicker;
echo DateRangePicker::widget([
    'name' => 'daterange',
    'attribute' => 'datetime_range',
    'pluginOptions' => [
        'timePicker' => true,
        'locale' => [
            'format' => 'YYYY-MM-DD'
        ]
    ]
]);
```
or using on model

```php
use silentlun\daterange\DateRangePicker;
echo DateRangePicker::widget([
    'model' => $model,
    'attribute' => 'datetime_range',
    'pluginOptions' => [
        'timePicker' => true,
        'locale' => [
            'format'=>'YYYY-MM-DD'
        ]
    ]
]);
```
or using seperate start/end attributes on model

```php
use silentlun\daterange\DateRangePicker;
echo DateRangePicker::widget([
    'model'=>$model,
    'attribute'=>'datetime_range',
    'startAttribute'=>'datetime_start',
    'endAttribute'=>'datetime_end',
    'pluginOptions'=>[
        'timePicker'=>true,
        'timePickerIncrement'=>30,
        'locale'=>[
            'format'=>'YYYY-MM-DD'
        ]
    ]
]);
```

DateRangeBehavior : 

```php 
use silentlun\daterange\DateRangeBehavior;

class UserSearch extends User
{
    public $createTimeRange;
    public $createTimeStart;
    public $createTimeEnd;

    public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::className(),
                'attribute' => 'createTimeRange',
                'dateStartAttribute' => 'createTimeStart',
                'dateEndAttribute' => 'createTimeEnd',
            ]
        ];
    }

    public function rules()
    {
        return [
            // ...
            [['createTimeRange'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
        ];
    }

    public function search($params)
    {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['>=', 'createdAt', $this->createTimeStart])
              ->andFilterWhere(['<', 'createdAt', $this->createTimeEnd]);

        return $dataProvider;
    }
}
```