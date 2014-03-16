<?php
/**
 * Хелпер вывода
 */
class OutputHelper
{
    const MEASURE_UNIT_CM = 'см';
    const MEASURE_UNIT_PX = 'px';

    /**
     * Форматирует число - из формата в БД (с разделителем-точкой) в формат с разделителем-запятой
     * @param string $number число в формате БД (с точками-разделителями десятичной части)
     * @return string отформатированное число с запятыми-разделителями десятичной части
     */
    public static function formatNumber($number)
    {
        return Yii::app()->format->number($number);
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

    /**
     * Форматирует дату из формата в БД в формат dd.mm.yyyy
     * @param string $date дата в формате БД (yyyy-mm-dd)
     * @return string дата в формате dd.mm.yyyy
     */
    public static function formatDate($date)
    {
        return Yii::app()->format->date($date);
    }

    /**
     * Форматирует размер из формата в БД с разделителем-точкой в формат с разделителем-запятой и приписывает
     * переданную единицу измерения
     * @param float $size размер
     * @param string $measure какую единицу приписать
     * @return string отформатированный размер + единица измерения
     */
    public static function formatSize($size, $measure = 'см')
    {
        $measureUnit = '';
        switch ($measure) {
            case self::MEASURE_UNIT_CM:
                $measureUnit = Yii::t('common', 'см');
                break;
            case self::MEASURE_UNIT_PX:
                $measureUnit = Yii::t('common', 'px');
                break;
            default:
                $measureUnit = Yii::t('common', 'см');
        }

        return self::formatNumber($size).' '.$measureUnit;
    }
} 