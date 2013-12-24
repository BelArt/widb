<?php
/**
 * Хелпер для работы с загруженными файлами
 */
class UploadsHelper extends CApplicationComponent
{
    /**
     * @var string сценарий, который устанавливается при сохранении данных о превью, для
     * исключения рекурсивного вызова afterSave()
     */
    const SCENARIO_SAVE_PREVIEWS = 'savePreviews';

    /**
     * Очищает подгруженные, но не сохраненные превью
     * @throws CException
     */
    public static function clearUserPreviewsUploads()
    {
        if ( Yii::app()->user->hasState(Yii::app()->params['xuploadStatePreviewsName'])) {

            $userImages = Yii::app()->user->getState(Yii::app()->params['xuploadStatePreviewsName']);

            if (!is_array($userImages)) {
                throw new CException(Yii::t('common', 'Произошла ошибка!'));
            }

            foreach ($userImages as $image) {
                if (is_file($image["path"])) {
                    if (!unlink($image["path"])) {
                        throw new CException(Yii::t('common', 'Произошла ошибка!'));
                    }
                }
            }

            Yii::app( )->user->setState(Yii::app()->params['xuploadStatePreviewsName'], null);

        }
    }

    /**
     * Удаляет превью
     * @param mixed $params или модель, или массив соответствующей структуры
     * @throws CException
     */
    public static function deletePreview($params)
    {
        if (empty($params)) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Model = null;

        if (is_array($params) && !empty($params['type']) && !empty($params['id'])) {
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
                    throw new CException(Yii::t('common', 'Произошла ошибка!'));
            }
        } elseif (is_object($params) && (get_class($params) == 'Collections' || get_class($params) == 'Objects' || get_class($params) == 'Images')) {
            $Model = $params;
        }

        if (empty($Model)) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $previewsFolder = ImageHelper::getPreviewFolderPath($Model);

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($previewsFolder, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
            if ($path->isFile() ) {
                if (!unlink($path->getPathname()) ) {
                    throw new CException(Yii::t('common', 'Произошла ошибка!'));
                }
            } else {
                if (!rmdir($path->getPathname()) ) {
                    throw new CException(Yii::t('common', 'Произошла ошибка!'));
                }
            }
        }
        if (!rmdir($previewsFolder)) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Model->has_preview = 0;
        if (!$Model->save()) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
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
            throw new CException(Yii::t('common', 'Объект не поддерживает вызванный метод'));
        }

        // предполагается, что в сессии хранится только одна картинка, которую как раз загрузили
        // в качестве превью при редактировании/создании коллекции/объекта/изображения
        if (Yii::app()->user->hasState(Yii::app()->params['xuploadStatePreviewsName'])) {

            $userImages = Yii::app()->user->getState(Yii::app()->params['xuploadStatePreviewsName']);

            $dir = ImageHelper::getPreviewFolderPath($Caller);

            if (!is_dir($dir)) {
                if (!mkdir($dir, 0777, true)) {
                    throw new CException(Yii::t('common', 'Произошла ошибка при сохранении превью'));
                }
            } else if (!is_writable($dir)) {
                if (!chmod($dir, 0777)) {
                    throw new CException(Yii::t('common', 'Произошла ошибка при сохранении превью'));
                }
            }

            foreach ($userImages as $image) {
                if (is_file($image["path"])) {

                    // генерим превьюхи

                    // маленькая
                    $Image = Yii::app()->image->load($image["path"]);
                    $imageExt = pathinfo($image["path"], PATHINFO_EXTENSION);
                    $Image->resize(ImageHelper::PREVIEW_SMALL_SIZE, ImageHelper::PREVIEW_SMALL_SIZE, Image::AUTO);
                    if (file_exists($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_SMALL_NAME.'.'.$imageExt)) {
                        if (!unlink($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_SMALL_NAME.'.'.$imageExt)) {
                            throw new CException(Yii::t('common', 'Произошла ошибка при сохранении превью'));
                        }
                    }
                    $Image->save($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_SMALL_NAME.'.'.$imageExt);
                    if (!chmod($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_SMALL_NAME.'.'.$imageExt, 0777)) {
                        throw new CException(Yii::t('common', 'Произошла ошибка при сохранении превью'));
                    }

                    // средняя
                    $Image = Yii::app()->image->load($image["path"]);
                    $imageExt = pathinfo($image["path"], PATHINFO_EXTENSION);
                    $Image->resize(ImageHelper::PREVIEW_MEDIUM_SIZE, ImageHelper::PREVIEW_MEDIUM_SIZE, Image::AUTO);
                    if (file_exists($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_MEDIUM_NAME.'.'.$imageExt)) {
                        if (!unlink($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_MEDIUM_NAME.'.'.$imageExt)) {
                            throw new CException(Yii::t('common', 'Произошла ошибка при сохранении превью'));
                        }
                    }
                    $Image->save($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_MEDIUM_NAME.'.'.$imageExt);
                    if (!chmod($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_MEDIUM_NAME.'.'.$imageExt, 0777)) {
                        throw new CException(Yii::t('common', 'Произошла ошибка при сохранении превью'));
                    }

                    // большая
                    $Image = Yii::app()->image->load($image["path"]);
                    $imageExt = pathinfo($image["path"], PATHINFO_EXTENSION);
                    $Image->resize(ImageHelper::PREVIEW_BIG_SIZE, ImageHelper::PREVIEW_BIG_SIZE, Image::AUTO);
                    if (file_exists($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_BIG_NAME.'.'.$imageExt)) {
                        if (!unlink($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_BIG_NAME.'.'.$imageExt)) {
                            throw new CException(Yii::t('common', 'Произошла ошибка при сохранении превью'));
                        }
                    }
                    $Image->save($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_BIG_NAME.'.'.$imageExt);
                    if (!chmod($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_BIG_NAME.'.'.$imageExt, 0777)) {
                        throw new CException(Yii::t('common', 'Произошла ошибка при сохранении превью'));
                    }

                    // перемещаем оригинал
                    if (file_exists($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_ORIGINAL_NAME.'.'.$imageExt)) {
                        if (!unlink($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_ORIGINAL_NAME.'.'.$imageExt)) {
                            throw new CException(Yii::t('common', 'Произошла ошибка при сохранении превью'));
                        }
                    }
                    if (!rename($image["path"], $dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_ORIGINAL_NAME.'.'.$imageExt)) {
                        throw new CException(Yii::t('common', 'Произошла ошибка при сохранении превью'));
                    }
                    if (!chmod($dir.DIRECTORY_SEPARATOR.ImageHelper::PREVIEW_ORIGINAL_NAME.'.'.$imageExt, 0777)) {
                        throw new CException(Yii::t('common', 'Произошла ошибка при сохранении превью'));
                    }

                    // ставим отметку, что превью есть
                    $Caller->has_preview = 1;

                    // обязательно для повторного сохранения, иначе при создании yii будет пытаться вставить эту запись еще раз,
                    // что вызовет ошибку
                    $Caller->isNewRecord = false;

                    // устанавливаем сценарий для исключения рекурсивного вызова этой функции в afterSave()
                    $Caller->scenario = self::SCENARIO_SAVE_PREVIEWS;

                    if (!$Caller->save()) {
                        throw new CException(Yii::t('common', 'Произошла ошибка при сохранении превью'));
                    }

                    ImageHelper::incrementPreviewVersion($Caller);
                }
            }

            Yii::app()->user->setState(Yii::app()->params['xuploadStatePreviewsName'], null);
        }
    }
} 