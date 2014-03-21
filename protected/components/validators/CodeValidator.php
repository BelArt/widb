<?php

/**
 * Валидатор поля Код.
 */
class CodeValidator extends CValidator
{
    public $pattern = '/^[a-z0-9_\-]{3,}$/';
    public $allowEmpty = true;

    protected function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;

        if ($this->allowEmpty && $this->isEmpty($value))
        {
            return;
        }

        if(!preg_match($this->pattern, $object->$attribute))
        {
            $this->addError($object, $attribute, Yii::t('common', 'Значение должно содержать только символы a-z, 0-9, - и _'));
        }

        $condition = '';
        if (!$object->isNewRecord) {
            $condition = 'id <> '.$object->id;
        }

        switch (get_class($object)) {
            case 'Collections':
                $Record = Collections::model()->findByAttributes(array('code' => $object->$attribute), $condition);
                break;
            case 'Objects':
                $Record = Objects::model()->findByAttributes(array('code' => $object->$attribute), $condition);
                break;
            case 'Images':
                $Record = Images::model()->findByAttributes(array('code' => $object->$attribute), $condition);
                break;
            default:
                throw new ValidatorException();
        }

        if (!empty($Record)) {
            $this->addError($object, $attribute, Yii::t('common', 'Такое значение уже есть в базе, придумайте другое'));
        }
    }
} 