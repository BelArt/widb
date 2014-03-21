<?php

abstract class MyValidator extends CValidator
{
    public $allowEmpty = true;

    protected function isEmpty($value, $trim = true)
    {
        return $value === '0.00' || $value === '0' || parent::isEmpty($value, $trim);
    }
} 