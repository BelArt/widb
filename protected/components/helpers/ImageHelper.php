<?php
/**
 * Хелпер для работы с изображениями.
 */
class ImageHelper extends CComponent
{
    /**
     * @var integer размер квадрата маленького превью, в px
     */
    const PREVIEW_SMALL_SIZE = 65;

    /**
     * @var integer размер квадрата среднего превью, в px
     */
    const PREVIEW_MEDIUM_SIZE = 150;

    /**
     * @var integer размер квадрата большого превью, в px
     */
    const PREVIEW_BIG_SIZE = 250;

    /**
     * @var string название маленькой превью
     */
    const PREVIEW_SMALL_NAME = 'preview_small';

    /**
     * @var string название средней превью
     */
    const PREVIEW_MEDIUM_NAME = 'preview_medium';

    /**
     * @var string название большой превью
     */
    const PREVIEW_BIG_NAME = 'preview_big';

    /**
     * @var string название большой превью
     */
    const PREVIEW_ORIGINAL_NAME = 'original';

    /**
     * Возвращает путь к большой превью для коллекции
     * @todo реализовать, когда будут известны пути хранения
     * @param Collections $Collection модель коллекции
     * @return string путь к большой превью
     */
    public static function getBigThumbnailForCollection(Collections $Collection)
    {
        if ($Collection->has_preview) {
            $previewUrl = self::getPreviewUrl($Collection, 'big');
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
            $previewUrl = self::getPreviewUrl($Collection, 'medium');
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
            $previewUrl = self::getPreviewUrl($Collection, 'small');
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

    /**
     * Возвращает урл превью
     * @param $Model модель коллекции, объекта или изображения
     * @param string $size какое превью вернуть - 'small', 'medium', 'big' или 'original'
     * @return string урл превью
     * @throws CException
     */
    public static function getPreviewUrl($Model, $size = 'small')
    {
        $className = get_class($Model);

        if (!in_array($className, array('Collections', 'Objects', 'Images'))) {
            throw new CException(Yii::t('common', 'Объект не поддерживает вызванный метод'));
        }

        $previewDir = '';
        $previewUrlPart = '';

        switch ($className) {
            case 'Collections':
                $previewDir = Yii::getPathOfAlias('webroot')
                    .DIRECTORY_SEPARATOR
                    .Yii::app()->params['filesFolder']
                    .DIRECTORY_SEPARATOR
                    .Yii::app()->params['previewsFolder']
                    .DIRECTORY_SEPARATOR
                    .$Model->code;
                $previewUrlPart = Yii::app()->baseUrl
                    .DIRECTORY_SEPARATOR
                    .Yii::app()->params['filesFolder']
                    .DIRECTORY_SEPARATOR
                    .Yii::app()->params['previewsFolder']
                    .DIRECTORY_SEPARATOR
                    .$Model->code;
                break;
            case 'Objects':
                throw new CException('Еще не готово');
                break;
            case 'Images':
                throw new CException('Еще не готово');
                break;
        }

        $fileNamePart = '';

        switch ($size) {
            case 'small':
                $fileNamePart = self::PREVIEW_SMALL_NAME;
                break;
            case 'medium':
                $fileNamePart = self::PREVIEW_MEDIUM_NAME;
                break;
            case 'big':
                $fileNamePart = self::PREVIEW_BIG_NAME;
                break;
            case 'original':
                $fileNamePart = self::PREVIEW_ORIGINAL_NAME;
                break;
            default:
                throw new CException(Yii::t('common', 'Переданный размер не поддерживается'));
        }

        foreach (scandir($previewDir) as $file) {
            if (strpos($file,$fileNamePart) !== false) {
                return $previewUrlPart.DIRECTORY_SEPARATOR.$file;
            }
        }

        throw new CException(Yii::t('common', 'Превью отсутствует!'));
    }

    /**
     * Возвращает путь к папке, в которой лежат превью
     * @param object $Model модель коллекции, объекта или изображения
     * @return string путь к папке, в которой лежат превью
     * @throws CException
     */
    public static function getPreviewFolderPath($Model)
    {
        $className = get_class($Model);

        if (!in_array($className, array('Collections', 'Objects', 'Images'))) {
            throw new CException(Yii::t('common', 'Объект не поддерживает вызванный метод'));
        }

        $path = '';

        switch ($className) {
            case 'Collections':
                $path = Yii::getPathOfAlias('webroot')
                    .DIRECTORY_SEPARATOR
                    .Yii::app()->params['filesFolder']
                    .DIRECTORY_SEPARATOR
                    .Yii::app()->params['previewsFolder']
                    .DIRECTORY_SEPARATOR
                    .$Model->code;
                break;
            case 'Objects':
                throw new CException('Еще не готово');
                break;
            case 'Images':
                throw new CException('Еще не готово');
                break;
        }

        return $path;
    }
}