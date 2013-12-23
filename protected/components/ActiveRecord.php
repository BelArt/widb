<?php
/**
 * Базовый класс ActiveRecord для данного проекта
 */
class ActiveRecord extends CActiveRecord
{
    /**
     * @var string сценарий, который устанавливается при сохранении данных о превью, для
     * исключения рекурсивного вызова afterSave()
     */
    const SCENARIO_SAVE_PREVIEWS = 'savePreviews';

    public function beforeSave()
    {
        if (!parent::beforeSave()) {
            return false;
        }

        /*
         * Обрабатываем запись в поля date_... и user_...
         */

        $now = new CDbExpression('NOW()');
        $userId = Yii::app()->user->id;

        switch ($this->scenario) {
            case 'insert':
                $this->date_create = $now;
                $this->date_modify = $now;
                $this->user_create = $userId;
                $this->user_modify = $userId;
                break;
            case 'update':
                $this->date_modify = $now;
                $this->user_modify = $userId;
                break;
            case self::SCENARIO_SAVE_PREVIEWS:
                break;
            case 'delete':
                $this->date_delete = $now;
                $this->date_modify = $now;
                $this->user_delete = $userId;
                $this->user_modify = $userId;
                break;
            default:
                throw new CException(Yii::t('common', 'Установлен неизвестный сценарий!'));
        }

        return true;
    }

    /**
     * Выбираем только неудаленные записи и сортируем в соответствии с полем sort
     * @return array
     */
    public function defaultScope()
    {
        return array(
            'condition' => $this->getTableAlias(false, false).'.deleted = 0',
            'order' => $this->getTableAlias(false, false).'.sort ASC',
            //'order' => $this->getTableAlias(false, false).'.sort ASC, '.$this->getTableAlias(false, false).'.name ASC',
        );
    }

    /**
     * Удаляет запись в таблицах проекта.
     * Т.е. ставит в ней флаг, что запись удалена, но не удаляет ее из таблицы
     * @return bool
     */
    public function deleteRecord()
    {
        $this->scenario = 'delete';
        $this->deleted = 1;
        if ($this->save()) {
            return true;
        }
        return false;
    }


    /**
     * Сохраняем превью
     * @param object $Caller объект, который вызвал этото метод
     * @throws CException
     */
    public function savePreviews($Caller)
    {
        $callerClassName = get_class($Caller);

        if (!in_array($callerClassName, array('Collections', 'Objects', 'Images'))) {
            throw new CException(Yii::t('common', 'Объект не поддерживает вызванный метод'));
        }

        // предполагается, что в сессии хранится только одна картинка, которую как раз загрузили
        // в качестве превью при редактировании/создании коллекции/объекта/изображения
        if (Yii::app()->user->hasState(Yii::app()->params['xuploadStateName'])) {

            $userImages = Yii::app()->user->getState(Yii::app()->params['xuploadStateName']);

            $dir = ImageHelper::getPreviewFolderPath($Caller); //$this->resolvePreviewPath($Caller);

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

            Yii::app()->user->setState(Yii::app()->params['xuploadStateName'], null);
        }
    }

} 