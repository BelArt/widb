<?php

class IntegerValidator extends MValidator
{
    const TYPE = 'integer';

    public $onlyPositive = true;

    protected $positiveIntegerPattern = '/^[1-9]{1}\d*$/';
    protected $integerPattern = '/^([-]?[1-9]{1}\d*)|([0])$/';

    protected function validateAttribute($object, $attribute)
    {
        parent::validateAttribute($object, $attribute);

        $value = $object->$attribute;

        $pattern = $this->onlyPositive ? $this->positiveIntegerPattern : $this->integerPattern;

        if (!preg_match($pattern, $value)) {
            $this->addError($object, $attribute, Yii::t('common', 'У поля {attribute} задано неверное значение!'));
        }
    }
} 