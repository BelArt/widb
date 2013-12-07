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
        if ($Collection->has_preview) {
            // @todo реализовать
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        } else {
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        }

        return $previewUrl;
    }

    /**
     * Возвращает путь к средней превью для коллекции
     * @todo реализовать, когда будут известны пути хранения
     * @param Collections $Collection модель коллекции
     * @return string путь к средней превью
     */
    public static function getMediumThumbnailForCollection(Collections $Collection)
    {
        if ($Collection->has_preview) {
            // @todo реализовать
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        } else {
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        }

        return $previewUrl;
    }

    /**
     * Возвращает путь к маленькой превью для коллекции
     * @todo реализовать, когда будут известны пути хранения
     * @param Collections $Collection модель коллекции
     * @return string путь к маленькой превью
     */
    public static function getSmallThumbnailForCollection(Collections $Collection)
    {
        if ($Collection->has_preview) {
            // @todo реализовать
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        } else {
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        }

        return $previewUrl;
    }

    /**
     * Возвращает путь к большой превью для объекта
     * @todo реализовать, когда будут известны пути хранения
     * @param Objects $Object модель объекта
     * @return string путь к большой превью
     */
    public static function getBigThumbnailForObject(Objects $Object)
    {
        if ($Object->has_preview) {
            // @todo реализовать
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        } else {
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        }

        return $previewUrl;
    }

    /**
     * Возвращает путь к средней превью для объекта
     * @todo реализовать, когда будут известны пути хранения
     * @param Objects $Object модель объекта
     * @return string путь к средней превью
     */
    public static function getMediumThumbnailForObject(Objects $Object)
    {
        if ($Object->has_preview) {
            // @todo реализовать
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        } else {
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        }

        return $previewUrl;
    }

    /**
     * Возвращает путь к маленькой превью для объекта
     * @todo реализовать, когда будут известны пути хранения
     * @param Objects $Object модель объекта
     * @return string путь к маленькой превью
     */
    public static function getSmallThumbnailForObject(Objects $Object)
    {
        if ($Object->has_preview) {
            // @todo реализовать
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        } else {
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        }

        return $previewUrl;
    }
}