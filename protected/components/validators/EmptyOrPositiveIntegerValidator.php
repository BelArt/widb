<?php

class EmptyOrPositiveIntegerValidator extends CValidator
{
    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute)
    {
        if (empty($object->$attribute)) {
            return;
        }

        $condition = is_numeric($object->$attribute) && (intval($object->$attribute) > 0);
        if(!$condition)
        {
            $this->addError($object, $attribute, Yii::t('common', 'Значение должно быть целым положительным числом!'));
        }
    }
} 