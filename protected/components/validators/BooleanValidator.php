<?php

class BooleanValidator extends MValidator
{
    const TYPE = 'boolean';

    protected function validateAttribute($object, $attribute)
    {
        parent::validateAttribute($object, $attribute);

        $value = $object->$attribute;

        if ($value != '1' && $value != '' && $value != '0') {
            $this->addError($object, $attribute, Yii::t('common', 'У поля {attribute} задано неверное значение!'));
        }
    }
} 