<?php

abstract class MValidator extends CValidator
{
    public $required = false;
    public $length;

    protected $skipOnError = true;
    protected $emptyValues = array(
        'boolean' => array(
            null,
            array(),
            false,
        ),
        'integer' => array(
            null,
            array(),
            false,
            '',
        ),
        'universal' => array(
            null,
            array(),
            false,
            '',
        ),
    );

    protected function isEmpty($value, $type)
    {
        if (!in_array($type, $this->emptyValues)) {
            throw new ValidatorException();
        }

        //$value = trim($value);

        $emptyValues = $this->emptyValues[$type];

        return in_array($value, $emptyValues); // все остальное
    }

    protected function validateAttribute($object, $attribute)
    {
        if ($this->isEmpty($object->$attribute, self::TYPE)) {
            if ($this->required) {
                $this->addError($object, $attribute, Yii::t('common', 'Поле {attribute} не заполнено!'));
            } else {
                return;
            }
        } else {
            if ($this->length && strlen(utf8_decode($object->$attribute)) > $this->length) {
                $this->addError($object, $attribute, Yii::t('common', 'В поле {attribute} должно быть максимум {n} символов!', array('n' => $this->length)));
            }
        }
    }
} 