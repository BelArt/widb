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
        switch ($attribute) {
            case 'parent_id':
                if (!empty($object->temporary)) {
                    if (!empty($object->$attribute)) {
                        $this->addError($object, $attribute, Yii::t('collections', 'У временной коллекции не может быть родительской коллекции!'));
                    }
                }
                break;
            case 'temporary_public':
                if (empty($object->temporary)) {
                    if (!empty($object->$attribute)) {
                        $this->addError($object, $attribute, Yii::t('collections', 'Обычная коллекция не может быть открытой!'));
                    }
                }
                break;
        }
    }
} 