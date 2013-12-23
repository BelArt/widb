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
     * @param Collections $Collection модель коллекции
     * @param boolean $addVersion добавить ли к пути метку версии, чтобы браузер не показывал закэшированную старую картинку
     * @return string путь к большой превью
     */
    public static function getBigThumbnailForCollection(Collections $Collection, $addVersion = true)
    {
        if ($Collection->has_preview) {
            $previewUrl = self::getPreviewUrl($Collection, 'big');
            if ($addVersion) {
                $previewUrl .= '?v='.self::getPreviewsVersion($Collection);
            }
        } else {
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        }

        return $previewUrl;
    }

    /**
     * Возвращает путь к средней превью для коллекции
     * @param Collections $Collection модель коллекции
     * @param boolean $addVersion добавить ли к пути метку версии, чтобы браузер не показывал закэшированную старую картинку
     * @return string путь к средней превью
     */
    public static function getMediumThumbnailForCollection(Collections $Collection, $addVersion = true)
    {
        if ($Collection->has_preview) {
            $previewUrl = self::getPreviewUrl($Collection, 'medium');
            if ($addVersion) {
                $previewUrl .= '?v='.self::getPreviewsVersion($Collection);
            }
        } else {
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        }

        return $previewUrl;
    }

    /**
     * Возвращает путь к маленькой превью для коллекции
     * @param Collections $Collection модель коллекции
     * @param boolean $addVersion добавить ли к пути метку версии, чтобы браузер не показывал закэшированную старую картинку
     * @return string путь к маленькой превью
     */
    public static function getSmallThumbnailForCollection(Collections $Collection, $addVersion = true)
    {
        if ($Collection->has_preview) {
            $previewUrl = self::getPreviewUrl($Collection, 'small');
            if ($addVersion) {
                $previewUrl .= '?v='.self::getPreviewsVersion($Collection);
            }
        } else {
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        }

        return $previewUrl;
    }

    /**
     * Возвращает путь к большой превью для объекта
     * @param Objects $Object модель объекта
     * @param boolean $addVersion добавить ли к пути метку версии, чтобы браузер не показывал закэшированную старую картинку
     * @return string путь к большой превью
     */
    public static function getBigThumbnailForObject(Objects $Object, $addVersion = true)
    {
        if ($Object->has_preview) {
            // @todo реализовать
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
            if ($addVersion) {
                $previewUrl .= '?v='.self::getPreviewsVersion($Object);
            }
        } else {
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        }

        return $previewUrl;
    }

    /**
     * Возвращает путь к средней превью для объекта
     * @param Objects $Object модель объекта
     * @param boolean $addVersion добавить ли к пути метку версии, чтобы браузер не показывал закэшированную старую картинку
     * @return string путь к средней превью
     */
    public static function getMediumThumbnailForObject(Objects $Object, $addVersion = true)
    {
        if ($Object->has_preview) {
            // @todo реализовать
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
            if ($addVersion) {
                $previewUrl .= '?v='.self::getPreviewsVersion($Object);
            }
        } else {
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
        }

        return $previewUrl;
    }

    /**
     * Возвращает путь к маленькой превью для объекта
     * @param Objects $Object модель объекта
     * @param boolean $addVersion добавить ли к пути метку версии, чтобы браузер не показывал закэшированную старую картинку
     * @return string путь к маленькой превью
     */
    public static function getSmallThumbnailForObject(Objects $Object, $addVersion = true)
    {
        if ($Object->has_preview) {
            // @todo реализовать
            $previewUrl = Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
            if ($addVersion) {
                $previewUrl .= '?v='.self::getPreviewsVersion($Object);
            }
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

    /**
     * Возвращает версию превью.
     * Нужно для того, чтобы эту версию допусить к урлу превью, чтобы браузер выдавал новую, незакэшированную картинку при обновлении превью.
     * @param mixed $param модель коллекции, объекта или изображения или название соответствующего класса
     * @return int версия
     * @throws CException
     */
    public static function getPreviewsVersion($param)
    {
        $file = Yii::getPathOfAlias('application').DIRECTORY_SEPARATOR.Yii::app()->params['previewsVersionsFile'];

        $versions = '';

        if (!file_exists($file)) {
            file_put_contents($file,'');
        } else {
            if (($versions = file_get_contents($file)) === false) {
                throw new CException(Yii::t('common', 'Произошла ошибка!'));
            }
        }

        $versions = unserialize($versions);

        $className = '';

        if (is_object($param)) {
            $className = get_class($param);
        } else {
            $className = $param;
        }

        $version = 0;

        switch ($className) {
            case 'Collections':
                $version = empty($versions['collections']) ? 0 : $versions['collections'];
                break;
            case 'Objects':
                $version = empty($versions['objects']) ? 0 : $versions['objects'];
                break;
            case 'Images':
                $version = empty($versions['images']) ? 0 : $versions['images'];
                break;
            default:
                throw new CException(Yii::t('common', 'Переданный параметр не поддерживается'));
        }

        return $version;
    }

    /**
     * Устанавливает версию превью.
     * @see getPreviewsVersion()
     * @param mixed $param модель коллекции, объекта или изображения или название соответствующего класса
     * @param int $version номер версии
     * @throws CException
     */
    public static function setPreviewsVersion($param, $version = 0)
    {
        $file = Yii::getPathOfAlias('application').DIRECTORY_SEPARATOR.Yii::app()->params['previewsVersionsFile'];

        $versions = '';

        if (file_exists($file)) {
            if (($versions = file_get_contents($file)) === false) {
                throw new CException(Yii::t('common', 'Произошла ошибка!'));
            }
        }

        $versions = unserialize($versions);

        $className = '';

        if (is_object($param)) {
            $className = get_class($param);
        } else {
            $className = $param;
        }

        switch ($className) {
            case 'Collections':
                $versions['collections'] = $version;
                break;
            case 'Objects':
                $versions['objects'] = $version;
                break;
            case 'Images':
                $versions['images'] = $version;
                break;
            default:
                throw new CException(Yii::t('common', 'Переданный параметр не поддерживается'));
        }

        if (false === file_put_contents($file, serialize($versions))) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }
    }

    /**
     * Увеличиваем версию превью
     * @param mixed $param модель коллекции, объекта или изображения или название соответствующего класса
     */
    public static function incrementPreviewVersion($param)
    {
        $className = '';

        if (is_object($param)) {
            $className = get_class($param);
        } else {
            $className = $param;
        }

        $version = self::getPreviewsVersion($className);
        self::setPreviewsVersion($className, ++$version);

    }
}