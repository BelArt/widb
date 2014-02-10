<?php

class CodeValidator extends CValidator
{
    public $pattern = '/^[a-z0-9_]{3,}$/';
    /*public $allowEmpty = true;*/

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     * @throws ValidatorException
     */
    protected function validateAttribute($object, $attribute)
    {
        /*if ($this->allowEmpty && empty($object->$attribute)) {
            return;
        }*/

        if(!preg_match($this->pattern, $object->$attribute))
        {
            $this->addError($object, $attribute, Yii::t('common', 'Значение должно содержать только символы a-z, 0-9 и _'));
        }

        switch (get_class($object)) {
            case 'Collections':
                $Record = Collections::model()->findByAttributes(array('code' => $object->$attribute), 'id <> '.$object->id);
                break;
            case 'Objects':
                $Record = Objects::model()->findByAttributes(array('code' => $object->$attribute), 'id <> '.$object->id);
                break;
            case 'Images':
                $Record = Images::model()->findByAttributes(array('code' => $object->$attribute), 'id <> '.$object->id);
                break;
            default:
                throw new ValidatorException();
        }

        if (!empty($Record)) {
            $this->addError($object, $attribute, Yii::t('common', 'Такое значение уже есть в базе, придумайте другое'));
        }
    }
} 