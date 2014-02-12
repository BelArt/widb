<?php

class UniversalValidator extends MValidator
{
    const TYPE = 'universal';

    protected function validateAttribute($object, $attribute)
    {
        parent::validateAttribute($object, $attribute);
    }
} 