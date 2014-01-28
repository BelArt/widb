<?php
/**
 * Базовый класс кастомных исключений проекта
 */

class CustomException extends CException
{
    public function __construct(Exception $previous = null, $message = "", $code = 0)
    {
        if (empty($message)) {
            $message = Yii::t('common', 'Произошла ошибка!');
        }

        parent::__construct($message, $code, $previous);
    }
}