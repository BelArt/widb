<?php

/**
 * Валидатор числовых значений с дробной частью.
 */
class MyFloatValidator extends MyValidator
{
    public $onlyPositive = true;
    public $maxIntegerSize = 3;
    public $maxFractionalSize = 0;

    protected function validateAttribute($object, $attribute)
    {
        if (!preg_match('/^[1-9]{1}\d*$/', $this->maxIntegerSize)) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        if (!preg_match('/^(0|[1-9]{1}\d*)$/', $this->maxFractionalSize)) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $value = $object->$attribute;

        if ($this->allowEmpty && $this->isEmpty($value))
        {
            return;
        }

        $pattern = $this->onlyPositive
            ? '/^(0|[1-9]{1}\d{0,'.($this->maxIntegerSize - 1).'})'.( $this->maxFractionalSize > 0 ? '((\.|\,)\d{1,'.$this->maxFractionalSize.'})?' : '').'$/'
            : '/^(0|-?[1-9]{1}\d{0,'.($this->maxIntegerSize - 1).'})'.( $this->maxFractionalSize > 0 ? '((\.|\,)\d{1,'.$this->maxFractionalSize.'})?' : '').'$/';

        if (!preg_match($pattern, $value)) {
            $this->addError($object, $attribute, Yii::t('common', 'У поля "{attribute}" задано неверное значение!'));
        }
    }

}