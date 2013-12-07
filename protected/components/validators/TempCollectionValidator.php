<?php

class TempCollectionValidator extends CValidator
{
    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute)
    {
        if (empty($object->temporary)) {
            if (!empty($object->$attribute)) {
                $this->addError($object, $attribute, 'Обычная коллекция не может быть открытой!');
            }
        }
    }
} 