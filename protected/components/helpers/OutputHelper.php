<?php
/**
 * Хелпер вывода
 */

class OutputHelper extends CApplicationComponent
{
    /**
     * Форматирует размер
     * @param string $size размер в формате БД (с точками-разделителями десятичной части)
     * @return string отформатированный размер с запятыми-разделителями десятичной части
     */
    public static function formatSize($size)
    {
        return Yii::app()->format->number($size);
    }

    /**
     * Приводит строку к нижнему регистру
     * @param string $string исходная строка
     * @return string исходная строка в нижнем регистре
     */
    public static function stringToLower($string)
    {
        return mb_strtolower($string, 'UTF-8');
    }
} 