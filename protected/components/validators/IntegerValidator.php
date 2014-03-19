<?php

/**
 * Валидатор целочисленных значений.
 *
 * Общий принцип валидации: сначала проверяется, пустое ли значение, и разрешены ли пустые значения.
 * Дальше проверяется по правилу, причем правило основано на предположении, что значение не пустое, т.к. пустые значения
 * отсекли на предыдущем шаге.
 */
class IntegerValidator extends CValidator
{
    public $allowEmpty = false;
    public $onlyPositive = true;

    protected $positiveIntegerPattern = '/^[1-9]{1}\d*$/';
    protected $integerPattern = '/^([-]?[1-9]{1}\d*)|([0])$/';

    protected function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;

        if ($this->allowEmpty && ($this->isEmpty($value, true) || $value == '0'))
        {
            return;
        }

        $pattern = $this->onlyPositive ? $this->positiveIntegerPattern : $this->integerPattern;

        if (!preg_match($pattern, $value)) {
            $this->addError($object, $attribute, Yii::t('common', 'У поля {attribute} задано неверное значение!'));
        }
    }

}