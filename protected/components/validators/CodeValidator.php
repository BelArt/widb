<?php

class CodeValidator extends CValidator
{
    public $pattern = '/^[a-z0-9_]{3,}$/';
    public $allowEmpty = true;

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute)
    {
        if ($this->allowEmpty && empty($object->$attribute)) {
            return;
        }

        if(!preg_match($this->pattern, $object->$attribute))
        {
            $this->addError($object, $attribute, Yii::t('common', 'Значение должно содержать только символы a-z, 0-9 и _'));
        }
    }
} 