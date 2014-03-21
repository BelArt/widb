<?php

class MyRequiredValidator extends MyValidator
{
    protected function validateAttribute($object, $attribute)
    {
        $value=$object->$attribute;

        if($this->isEmpty($value))
        {
            $this->addError($object,$attribute, Yii::t('common', 'У поля "{attribute}" должно быть непустое/ненулевое значение!'));
        }
    }

} 