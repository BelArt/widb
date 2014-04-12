<?php

abstract class MyValidator extends CValidator
{
    public $allowEmpty = true;

    protected function isEmpty($value, $trim = true)
    {
        return $value === '0.00' || $value === '0' || parent::isEmpty($value, $trim);
    }

    public static function valueIsEmpty($value, $trim = true)
    {
        return $value === '0.00'
            || $value === '0'
            || $value===null
            || $value===array()
            || $value===''
            || $trim && is_scalar($value) && trim($value)==='';
    }
} 