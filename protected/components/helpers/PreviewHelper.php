<?php
/**
 * Хелпер для работы с превью.
 */
class PreviewHelper extends CApplicationComponent
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
     * @var string сценарий, который устанавливается при сохранении данных о превью, для
     * исключения рекурсивного вызова afterSave()
     */
    const SCENARIO_SAVE_PREVIEWS = 'savePreviews';

    /**
     * Возвращает урл картинки-заглушки "Нет изображения"
     * @return string
     */
    public static function getNoPhotoPreviewUrl()
    {
        return Yii::app()->assetManager->getPublishedUrl(Yii::getPathOfAlias('application.assets')).'/img/photo-not-available.jpg';
    }

    /**
     * Возвращает путь к большой превью для коллекции
     * @param Collections $Collection модель коллекции
     * @param boolean $addVersion добавить ли к пути метку версии, чтобы браузер не показывал закэшированную старую картинку
     * @return string путь к большой превью
     */
    public static function getBigThumbnailForCollection(Collections $Collection, $addVersion = true)
    {
        if ($Collection->has_preview) {
            if ($Collection->reallyHasPreview('big')) {
                $previewUrl = self::getPreviewUrl($Collection, 'big');
                if ($addVersion) {
                    $previewUrl .= '?v='.self::getPreviewsVersion($Collection);
                }
            } else {
                $previewUrl = self::getNoPhotoPreviewUrl();
            }
        } else {
            $previewUrl = self::getNoPhotoPreviewUrl();
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
            if ($Collection->reallyHasPreview('medium')) {
                $previewUrl = self::getPreviewUrl($Collection, 'medium');
                if ($addVersion) {
                    $previewUrl .= '?v='.self::getPreviewsVersion($Collection);
                }
            } else {
                $previewUrl = self::getNoPhotoPreviewUrl();
            }
        } else {
            $previewUrl = self::getNoPhotoPreviewUrl();
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
            if ($Collection->reallyHasPreview('small')) {
                $previewUrl = self::getPreviewUrl($Collection, 'small');
                if ($addVersion) {
                    $previewUrl .= '?v='.self::getPreviewsVersion($Collection);
                }
            } else {
                $previewUrl = self::getNoPhotoPreviewUrl();
            }
        } else {
            $previewUrl = self::getNoPhotoPreviewUrl();
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
            if ($Object->reallyHasPreview('big')) {
                $previewUrl = self::getPreviewUrl($Object, 'big');
                if ($addVersion) {
                    $previewUrl .= '?v='.self::getPreviewsVersion($Object);
                }
            } else {
                $previewUrl = self::getNoPhotoPreviewUrl();
            }
        } else {
            $previewUrl = self::getNoPhotoPreviewUrl();
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
            if ($Object->reallyHasPreview('medium')) {
                $previewUrl = self::getPreviewUrl($Object, 'medium');
                if ($addVersion) {
                    $previewUrl .= '?v='.self::getPreviewsVersion($Object);
                }
            } else {
                $previewUrl = self::getNoPhotoPreviewUrl();
            }
        } else {
            $previewUrl = self::getNoPhotoPreviewUrl();
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
            if ($Object->reallyHasPreview('small')) {
                $previewUrl = self::getPreviewUrl($Object, 'small');
                if ($addVersion) {
                    $previewUrl .= '?v='.self::getPreviewsVersion($Object);
                }
            } else {
                $previewUrl = self::getNoPhotoPreviewUrl();
            }
        } else {
            $previewUrl = self::getNoPhotoPreviewUrl();
        }

        return $previewUrl;
    }

    /**
     * Возвращает путь к большой превью для изображения
     * @param Images $Image модель изображения
     * @param boolean $addVersion добавить ли к пути метку версии, чтобы браузер не показывал закэшированную старую картинку
     * @return string путь к большой превью
     */
    public static function getBigThumbnailForImage(Images $Image, $addVersion = true)
    {
        if ($Image->has_preview) {
            if ($Image->reallyHasPreview('big')) {
                $previewUrl = self::getPreviewUrl($Image, 'big');
                if ($addVersion) {
                    $previewUrl .= '?v='.self::getPreviewsVersion($Image);
                }
            } else {
                $previewUrl = self::getNoPhotoPreviewUrl();
            }
        } else {
            $previewUrl = self::getNoPhotoPreviewUrl();
        }

        return $previewUrl;
    }

    /**
     * Возвращает путь к средней превью для изображения
     * @param Images $Image модель изображения
     * @param boolean $addVersion добавить ли к пути метку версии, чтобы браузер не показывал закэшированную старую картинку
     * @return string путь к средней превью
     */
    public static function getMediumThumbnailForImage(Images $Image, $addVersion = true)
    {
        if ($Image->has_preview) {
            if ($Image->reallyHasPreview('medium')) {
                $previewUrl = self::getPreviewUrl($Image, 'medium');
                if ($addVersion) {
                    $previewUrl .= '?v='.self::getPreviewsVersion($Image);
                }
            } else {
                $previewUrl = self::getNoPhotoPreviewUrl();
            }
        } else {
            $previewUrl = self::getNoPhotoPreviewUrl();
        }

        return $previewUrl;
    }

    /**
     * Возвращает путь к маленькой превью для объекта
     * @param Images $Image модель объекта
     * @param boolean $addVersion добавить ли к пути метку версии, чтобы браузер не показывал закэшированную старую картинку
     * @return string путь к маленькой превью
     */
    public static function getSmallThumbnailForImage(Images $Image, $addVersion = true)
    {
        if ($Image->has_preview) {
            if ($Image->reallyHasPreview('small')) {
                $previewUrl = self::getPreviewUrl($Image, 'small');
                if ($addVersion) {
                    $previewUrl .= '?v='.self::getPreviewsVersion($Image);
                }
            } else {
                $previewUrl = self::getNoPhotoPreviewUrl();
            }
        } else {
            $previewUrl = self::getNoPhotoPreviewUrl();
        }

        return $previewUrl;
    }

    /**
     * Возвращает урл превью
     * @param $Model модель коллекции, объекта или изображения
     * @param string $size какое превью вернуть - 'small', 'medium', 'big' или 'original'
     * @return string урл превью, если есть, и пустую строку, если превью почему-то не найдено
     * @throws CException
     */
    public static function getPreviewUrl($Model, $size = 'small')
    {
        $className = get_class($Model);

        if (!in_array($className, array('Collections', 'Objects', 'Images'))) {
            throw new PreviewHelperException();
        }

        $previewDir = self::getPreviewFolderPath($Model);
        $previewUrlPart = '';

        switch ($className) {
            case 'Collections':
                $previewUrlPart = Yii::app()->baseUrl
                    .DIRECTORY_SEPARATOR
                    .Yii::app()->params['filesFolder']
                    .DIRECTORY_SEPARATOR
                    .Yii::app()->params['previewsFolder']
                    .DIRECTORY_SEPARATOR
                    .$Model->code;
                break;
            case 'Objects':
                $previewUrlPart = Yii::app()->baseUrl
                    .DIRECTORY_SEPARATOR
                    .Yii::app()->params['filesFolder']
                    .DIRECTORY_SEPARATOR
                    .Yii::app()->params['previewsFolder']
                    .DIRECTORY_SEPARATOR
                    .$Model->collection->code
                    .DIRECTORY_SEPARATOR
                    .$Model->code;
                break;
            case 'Images':
                //throw new CException('Еще не готово');
                // @todo доделать
                break;
        }

        if (!is_dir($previewDir)) {
            return '';
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
                throw new PreviewHelperException();
        }

        foreach (scandir($previewDir) as $file) {
            if (strpos($file,$fileNamePart) !== false) {
                return $previewUrlPart.DIRECTORY_SEPARATOR.$file;
            }
        }

        return '';
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
            throw new PreviewHelperException();
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
                $path = Yii::getPathOfAlias('webroot')
                    .DIRECTORY_SEPARATOR
                    .Yii::app()->params['filesFolder']
                    .DIRECTORY_SEPARATOR
                    .Yii::app()->params['previewsFolder']
                    .DIRECTORY_SEPARATOR
                    .$Model->collection->code
                    .DIRECTORY_SEPARATOR
                    .$Model->code;
                break;
            case 'Images':
                //throw new CException('Еще не готово');
                // @todo доделать
                break;
            default:
                throw new PreviewHelperException();
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
                throw new PreviewHelperException();
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
                throw new PreviewHelperException();
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
                throw new PreviewHelperException();
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
                throw new PreviewHelperException();
        }

        if (false === file_put_contents($file, serialize($versions))) {
            throw new PreviewHelperException();
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

    /**
     * Удаляет превью
     * @param mixed $params или модель, или массив соответствующей структуры
     * @throws CException
     */
    public static function deletePreview($params)
    {
        $Model = null;

        if (!empty($params) && is_array($params) && !empty($params['type']) && !empty($params['id'])) {
            switch ($params['type']) {
                case 'collection':
                    $Model = Collections::model()->findByPk($params['id']);
                    break;
                case 'object':
                    $Model = Objects::model()->findByPk($params['id']);
                    break;
                case 'image':
                    $Model = Images::model()->findByPk($params['id']);
                    break;
                default:
                    throw new PreviewHelperException();
            }
        } elseif (!empty($params) && is_object($params) && (get_class($params) == 'Collections' || get_class($params) == 'Objects' || get_class($params) == 'Images')) {
            $Model = $params;
        }

        if (empty($Model)) {
            throw new PreviewHelperException();
        }

        $previewsFolder = self::getPreviewFolderPath($Model);

        if (file_exists($previewsFolder)) {

            $files = array_diff(scandir($previewsFolder), array('..', '.'));

            foreach ($files as $file) {
                if (
                    !is_dir($file)
                    && (
                        strpos($file, self::PREVIEW_SMALL_NAME) !== false
                        || strpos($file, self::PREVIEW_MEDIUM_NAME) !== false
                        || strpos($file, self::PREVIEW_BIG_NAME) !== false
                        || strpos($file, self::PREVIEW_ORIGINAL_NAME) !== false
                    )
                ) {
                    if (!unlink($previewsFolder.DIRECTORY_SEPARATOR.$file)) {
                        throw new PreviewHelperException();
                    }
                }
            }

            $files = array_diff(scandir($previewsFolder), array('..', '.'));

            if (empty($files)) {
                if (!rmdir($previewsFolder)) {
                    throw new PreviewHelperException();
                }
            }

            // удаляем пустую родительскую папку
            switch (get_class($Model)) {
                case 'Objects':
                    $Collection = Collections::model()->findByPk($Model->collection_id);
                    $previewsFolder = self::getPreviewFolderPath($Collection);
                    $files = array_diff(scandir($previewsFolder), array('..', '.'));
                    if (empty($files)) {
                        if (!rmdir($previewsFolder)) {
                            throw new PreviewHelperException();
                        }
                    }
                    break;
                case 'Images':
                    $Object = Objects::model()->findByPk($Model->object_id);
                    $previewsFolder = self::getPreviewFolderPath($Object);
                    $files = array_diff(scandir($previewsFolder), array('..', '.'));
                    if (empty($files)) {
                        if (!rmdir($previewsFolder)) {
                            throw new PreviewHelperException();
                        }
                    }
                    $Collection = Collections::model()->findByPk($Object->collection_id);
                    $previewsFolder = self::getPreviewFolderPath($Collection);
                    $files = array_diff(scandir($previewsFolder), array('..', '.'));
                    if (empty($files)) {
                        if (!rmdir($previewsFolder)) {
                            throw new PreviewHelperException();
                        }
                    }
                    break;
            }

        }

        /*
         * @@WIDB-79
         *
            $Model->has_preview = 0;
            if (!$Model->save()) {
                throw new PreviewHelperException();
            }
        */

    }

    /**
     * Очищает подгруженные, но не сохраненные превью
     * @throws CException
     */
    public static function clearUserPreviewsUploads()
    {
        if (Yii::app()->user->hasState(Yii::app()->params['xuploadStatePreviewsName'])) {

            $userImages = Yii::app()->user->getState(Yii::app()->params['xuploadStatePreviewsName']);

            if (!is_array($userImages)) {
                throw new PreviewHelperException();
            }

            foreach ($userImages as $image) {
                if (is_file($image["path"])) {
                    if (!unlink($image["path"])) {
                        throw new PreviewHelperException();
                    }
                }
            }

            Yii::app()->user->setState(Yii::app()->params['xuploadStatePreviewsName'], null);

        }
    }

    /**
     * Сохраняем превью
     * @param object $Caller объект, который вызвал этото метод
     * @throws CException
     */
    public static function savePreviews($Caller)
    {
        $callerClassName = get_class($Caller);

        if (!in_array($callerClassName, array('Collections', 'Objects', 'Images'))) {
            throw new PreviewHelperException();
        }

        // предполагается, что в сессии хранится только одна картинка, которую как раз загрузили
        // в качестве превью при редактировании/создании коллекции/объекта/изображения
        if (Yii::app()->user->hasState(Yii::app()->params['xuploadStatePreviewsName'])) {

            $userImages = Yii::app()->user->getState(Yii::app()->params['xuploadStatePreviewsName']);

            $dir = self::getPreviewFolderPath($Caller);

            // удаляем папку и превью, если они есть, т.е. будем перезаписывать вновь загружаемыми
            /*if (file_exists($dir)) {
                foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
                    if ($path->isFile()) {
                        if (!unlink($path->getPathname())) {
                            throw new CException(Yii::t('common', 'Произошла ошибка!'));
                        }
                    } else {
                        if (!rmdir($path->getPathname())) {
                            throw new CException(Yii::t('common', 'Произошла ошибка!'));
                        }
                    }
                }
                if (!rmdir($dir)) {
                    throw new CException(Yii::t('common', 'Произошла ошибка!'));
                }
            }

            if (!mkdir($dir, 0777, true)) {
                throw new CException(Yii::t('common', 'Произошла ошибка при сохранении превью'));
            }*/

            if (!file_exists($dir)) {
                if (!mkdir($dir, 0777, true)) {
                    throw new PreviewHelperException();
                }
            }

            if (!is_writable($dir)) {
                if (!chmod($dir, 0777)) {
                    throw new PreviewHelperException();
                }
            }

            foreach ($userImages as $image) {
                if (is_file($image["path"])) {

                    // генерим превьюхи

                    // маленькая
                    $Image = Yii::app()->image->load($image["path"]);
                    $imageExt = pathinfo($image["path"], PATHINFO_EXTENSION);
                    $Image->resize(self::PREVIEW_SMALL_SIZE, self::PREVIEW_SMALL_SIZE, Image::AUTO);
                    if (file_exists($dir . DIRECTORY_SEPARATOR . self::PREVIEW_SMALL_NAME . '.' . $imageExt)) {
                        if (!unlink($dir . DIRECTORY_SEPARATOR . self::PREVIEW_SMALL_NAME . '.' . $imageExt)) {
                            throw new PreviewHelperException();
                        }
                    }
                    $Image->save($dir . DIRECTORY_SEPARATOR . self::PREVIEW_SMALL_NAME . '.' . $imageExt);
                    if (!chmod($dir . DIRECTORY_SEPARATOR . self::PREVIEW_SMALL_NAME . '.' . $imageExt, 0777)) {
                        throw new PreviewHelperException();
                    }

                    // средняя
                    $Image = Yii::app()->image->load($image["path"]);
                    $imageExt = pathinfo($image["path"], PATHINFO_EXTENSION);
                    $Image->resize(self::PREVIEW_MEDIUM_SIZE, self::PREVIEW_MEDIUM_SIZE, Image::AUTO);
                    if (file_exists($dir . DIRECTORY_SEPARATOR . self::PREVIEW_MEDIUM_NAME . '.' . $imageExt)) {
                        if (!unlink($dir . DIRECTORY_SEPARATOR . self::PREVIEW_MEDIUM_NAME . '.' . $imageExt)) {
                            throw new PreviewHelperException();
                        }
                    }
                    $Image->save($dir . DIRECTORY_SEPARATOR . self::PREVIEW_MEDIUM_NAME . '.' . $imageExt);
                    if (!chmod($dir . DIRECTORY_SEPARATOR . self::PREVIEW_MEDIUM_NAME . '.' . $imageExt, 0777)) {
                        throw new PreviewHelperException();
                    }

                    // большая
                    $Image = Yii::app()->image->load($image["path"]);
                    $imageExt = pathinfo($image["path"], PATHINFO_EXTENSION);
                    $Image->resize(self::PREVIEW_BIG_SIZE, self::PREVIEW_BIG_SIZE, Image::AUTO);
                    if (file_exists($dir . DIRECTORY_SEPARATOR . self::PREVIEW_BIG_NAME . '.' . $imageExt)) {
                        if (!unlink($dir . DIRECTORY_SEPARATOR . self::PREVIEW_BIG_NAME . '.' . $imageExt)) {
                            throw new PreviewHelperException();
                        }
                    }
                    $Image->save($dir . DIRECTORY_SEPARATOR . self::PREVIEW_BIG_NAME . '.' . $imageExt);
                    if (!chmod($dir . DIRECTORY_SEPARATOR . self::PREVIEW_BIG_NAME . '.' . $imageExt, 0777)) {
                        throw new PreviewHelperException();
                    }

                    // перемещаем оригинал
                    if (file_exists($dir . DIRECTORY_SEPARATOR . self::PREVIEW_ORIGINAL_NAME . '.' . $imageExt)) {
                        if (!unlink($dir . DIRECTORY_SEPARATOR . self::PREVIEW_ORIGINAL_NAME . '.' . $imageExt)) {
                            throw new PreviewHelperException();
                        }
                    }
                    if (!rename($image["path"], $dir . DIRECTORY_SEPARATOR . self::PREVIEW_ORIGINAL_NAME . '.' . $imageExt)) {
                        throw new PreviewHelperException();
                    }
                    if (!chmod($dir . DIRECTORY_SEPARATOR . self::PREVIEW_ORIGINAL_NAME . '.' . $imageExt, 0777)) {
                        throw new PreviewHelperException();
                    }

                    /*
                     * @@WIDB-79 Теперь не ставим отметку, даже если загрузили превью - пользователь галочкой Есть превью
                     * должен управлять выводом превью.
                     * Чтобы включить функционал обратно, надо также изменить метод afterSave() соответствующих моделей
                     *
                            // ставим отметку, что превью есть
                            $Caller->has_preview = 1;

                            // обязательно для повторного сохранения, иначе при создании yii будет пытаться вставить эту запись еще раз,
                            // что вызовет ошибку
                            $Caller->isNewRecord = false;

                            // устанавливаем сценарий для исключения рекурсивного вызова этой функции в afterSave()
                            $Caller->scenario = self::SCENARIO_SAVE_PREVIEWS;

                            if (!$Caller->save()) {
                                throw new PreviewHelperException();
                            }
                     */

                    self::incrementPreviewVersion($Caller);
                }
            }

            Yii::app()->user->setState(Yii::app()->params['xuploadStatePreviewsName'], null);
        }
    }

    /**
     * Перемещает превью объекта при перемещении объекта в другую коллекцию
     * @param Objects $Object перемещаемый объект
     * @param Collections $Collection новая коллекцию объекта - куда его перемещаем
     * @throws PreviewHelperException
     */
    public static function moveObjectToOtherCollection(Objects $Object, Collections $Collection)
    {
        $objectFolder = self::getPreviewFolderPath($Object);

        if (!file_exists($objectFolder)) {
            return;
        }

        $collectionFolder = self::getPreviewFolderPath($Collection);
        $newObjectFolder = $collectionFolder.DIRECTORY_SEPARATOR.$Object->code;

        // по какой-то причине новая папка объекта уже есть в коллекции, куда хотим переместить объект - этого не должно быть!
        if (file_exists($newObjectFolder)) {
            throw new PreviewHelperException();
        }

        if (!file_exists($collectionFolder)) {
            if (!mkdir($collectionFolder, 0777, true)) {
                throw new PreviewHelperException();
            }
        }

        if (!is_writable($collectionFolder)) {
            if (!chmod($collectionFolder, 0777)) {
                throw new PreviewHelperException();
            }
        }

        if (!rename($objectFolder, $newObjectFolder)) {
            throw new PreviewHelperException();
        }

        if (!is_writable($newObjectFolder)) {
            if (!chmod($newObjectFolder, 0777)) {
                throw new PreviewHelperException();
            }
        }

        // удаляем пустую папку старой коллекции
        $oldCollectionFolder = self::getPreviewFolderPath($Object->collection);
        $files = array_diff(scandir($oldCollectionFolder), array('..', '.'));
        if (empty($files)) {
            if (!rmdir($oldCollectionFolder)) {
                throw new PreviewHelperException();
            }
        }
    }

    /**
     * Перемещает папку с превью (при изменении значения поля code)
     * @param ActiveRecord $Model модель сущности (коллекции, объекта, изображения) со СТАРЫМ значением поля code
     * @param string $newCode новое значение поля code
     * @throws PreviewHelperException
     */
    public static function changePreviewPath($Model, $newCode)
    {
        $oldFolder = self::getPreviewFolderPath($Model);

        // если превью нет - перемещать ничего не надо
        if (!file_exists($oldFolder)) {
            return;
        }

        $Model->code = $newCode;
        $newFolder = self::getPreviewFolderPath($Model);

        $parentFolder = '';
        switch (get_class($Model)) {
            case 'Collections':
                $parentFolder = Yii::getPathOfAlias('webroot')
                    .DIRECTORY_SEPARATOR
                    .Yii::app()->params['filesFolder']
                    .DIRECTORY_SEPARATOR
                    .Yii::app()->params['previewsFolder'];
                break;
            case 'Objects':
                $parentFolder = self::getPreviewFolderPath($Model->collection);
                break;
            case 'Images':
                $parentFolder = self::getPreviewFolderPath($Model->object);
                break;
            default:
                throw new PreviewHelperException();
        }

        if (!is_writable($parentFolder)) {
            if (!chmod($parentFolder, 0777)) {
                throw new PreviewHelperException();
            }
        }

        if (!rename($oldFolder, $newFolder)) {
            throw new PreviewHelperException();
        }

        if (!is_writable($newFolder)) {
            if (!chmod($newFolder, 0777)) {
                throw new PreviewHelperException();
            }
        }
    }
}