<?php

namespace common\models;

use yii\validators\Validator;

class AsDateValidator extends Validator
{
    public $format = 'yyyy-MM-dd';

    public function validateAttribute($model, $attribute)
    {
        switch ($this->format) {
            case 'U':
            case 'timestamp':
                $value = Yii::$app->formatter->asTimestamp($model->$attribute);
                break;
            default:
                $value = Yii::$app->formatter->asDate($model->$attribute, $this->format);
        }
        $model->$attribute = $value;
    }

}