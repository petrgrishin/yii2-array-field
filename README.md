yii2-array-field
================
[![Travis CI](https://travis-ci.org/petrgrishin/yii2-array-field.png "Travis CI")](https://travis-ci.org/petrgrishin/yii2-array-field)
[![Coverage Status](https://coveralls.io/repos/petrgrishin/yii2-array-field/badge.png?branch=master)](https://coveralls.io/r/petrgrishin/yii2-array-field?branch=master)

Yii2 array field behavior (usage https://github.com/petrgrishin/array-access)

Installation
------------
Add a dependency to your project's composer.json:
```json
{
    "require": {
        "petrgrishin/yii2-array-field": "~1.0"
    }
}
```

Usage examples
--------------
#### Attach behavior to you model
Model have text attribute `data` for storage array

```php
namespace app\models;

use yii\db\ActiveRecord;
use \PetrGrishin\ArrayField\ArrayAccessFieldBehavior;

class Model extends ActiveRecord{
    public function behaviors() {
        return [
            'arrayField' => [
                'class' => ArrayAccessFieldBehavior::className(),
                'fieldNameStorage' => 'data',
            ]
        ];
    }

}
```

#### Usage behavior
```php
$model = Model::find(1)->one();
$model->arrayField->setValue('a.b', true);
$value = $model->arrayField->getValue('a.b');
$array = $model->arrayField->getArray();
```

#### Save only array field
```php
$model = Model::find(1)->one();
$model->arrayField->setValue('a.b', true);
$model->arrayField->save();
```
