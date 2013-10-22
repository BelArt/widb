<?php
/**
 * Хелпер для работы с изображениями.
 */
class ImageHelper extends CComponent
{
    /**
     * Возвращает путь к большой превью для коллекции
     * @todo реализовать, когда будут известны пути хранения
     * @param Collections $Collection модель коллекции
     * @return string путь к большой превью
     */
    public static function getBigThumbnailForCollection(Collections $Collection)
   {
       return Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
   }

    /**
     * Возвращает путь к средней превью для коллекции
     * @todo реализовать, когда будут известны пути хранения
     * @param Collections $Collection модель коллекции
     * @return string путь к средней превью
     */
    public static function getMediumThumbnailForCollection(Collections $Collection)
    {
        return Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
    }

    /**
     * Возвращает путь к маленькой превью для коллекции
     * @todo реализовать, когда будут известны пути хранения
     * @param Collections $Collection модель коллекции
     * @return string путь к маленькой превью
     */
    public static function getSmallThumbnailForCollection(Collections $Collection)
    {
        return Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
    }
}