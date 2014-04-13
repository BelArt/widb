<?php
/**
 * Хелпер для удаления сущностей в проекте
 */

class MyDeleteHelper
{
    /**
     * Удаляет изображение
     * @param $id айди изображения
     * @throws CException
     */
    public static function deleteImage($id)
    {
        $Image = Images::model()->findByPk($id);
        if (empty($Image)) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Image->deleteImage();
    }

    /**
     * Удаляет объекты из обычной коллекции
     * @param array $ids айди объектов для удаления
     * @return bool
     */
    public static function deleteObjectsFromNormalCollection(array $ids)
    {
        $result = true;

        foreach ($ids as $id) {
            $tempResult = self::deleteObjectFromNormalCollection($id);
            if (!$tempResult) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Удаляет объекты из временной коллекции
     * @param array $objectIds айди объектов для удаления
     * @param int $collectionId айди временной коллекции
     */
    public static function deleteObjectsFromTempCollection(array $objectIds, $collectionId)
    {
        foreach ($objectIds as $id) {
            self::deleteObjectFromTempCollection($id, $collectionId);

        }
    }

    /**
     * Удаляет объект из обычной коллекции
     * @param $id айди объекта
     * @return bool
     * @throws CException
     */
    public static function deleteObjectFromNormalCollection($id)
    {
        $Object = Objects::model()->findByPk($id);

        if (empty($Object)) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        if ($Object->isReadyToBeDeleted()) {
            $Object->deleteObject();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Удаляет объект из временной коллекции
     * @param $objectId айди объекта
     * @param $collectionId айди коллекции
     * @throws CException
     */
    public static function deleteObjectFromTempCollection($objectId, $collectionId)
    {
        $Criteria = new CDbCriteria;
        $Criteria->select = 'id';
        $Criteria->addCondition('collection_id = :collection_id');
        $Criteria->addCondition('object_id = :object_id');
        $Criteria->params = array(
            ':collection_id' => $collectionId,
            ':object_id' => $objectId
        );

        $Record = TempCollectionObject::model()->find($Criteria);

        if (empty($Record)) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Record->deleteRecord();
    }

    /**
     * Удаляет обычную коллекцию
     * @param int $id айди обычной коллекции
     * @return bool true - удалена, false - не удалена, из-за того, что не выполнены все необходимые для удаления условия
     * @throws CException
     */
    public static function deleteNormalCollection($id)
    {
        $Collection = Collections::model()->findByPk($id);

        if (empty($Collection) || $Collection->temporary) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        if ($Collection->isReadyToBeDeleted()) {
            $Collection->deleteNormalCollection();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Удаляет временную коллекцию
     * @param int $id айди временную коллекции
     * @throws CException
     */
    public static function deleteTempCollection($id)
    {
        $Collection = Collections::model()->findByPk($id);

        if (empty($Collection) || !$Collection->temporary) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Collection->deleteTempCollection();
    }

    /**
     * Удаляет обычные коллекции
     * @param array $ids массив с айдишниками обычных коллекций
     * @return bool true - все переданные удалены, false - не все удалены
     */
    public static function deleteNormalCollections(array $ids)
    {
        $result = true;

        foreach ($ids as $id) {
            $tempResult = self::deleteNormalCollection($id);
            if (!$tempResult) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Удаляет временные коллекции
     * @param array $ids массив с айдишниками временных коллекций
     */
    public static function deleteTempCollections(array $ids)
    {
        foreach ($ids as $id) {
            self::deleteTempCollection($id);
        }

    }

    /**
     * Удаляет запись из справочника
     * @param MyActiveRecord $Model модель записи, см. {@link DictionariesController::getDictionaryRecordModel()}
     * @return bool удалены ли запись или нет. Нет - в случае, если запись где-то используется
     * @throws CException
     */
    public static function deleteDictionaryRecord(MyActiveRecord $Model)
    {
        if ($Model->isReadyToBeDeleted()) {
            $Model->deleteDictionaryRecord();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Удаляет пользователя
     * @param Users $User модель пользователя
     * @throws CException
     * @throws Exception
     */
    public static function deleteUser(Users $User)
    {
        if ($User->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Transaction = Yii::app()->db->beginTransaction();

        try {
            $User->deleteUser();
            self::deleteUserAllowedCollections($User);
            self::updateUserFieldsInAllTables($User);
            $Transaction->commit();
        } catch (Exception $Exception) {
            $Transaction->rollback();
            throw $Exception;
        }
    }

    private static function deleteUserAllowedCollections(Users $User)
    {
        $records = UserAllowedCollection::model()->findAll('user_id = :userId', array(':userId' => $User->id));

        foreach ($records as $Record) {
            $Record->deleteUserAllowedCollection();
        }
    }

    private static function updateUserFieldsInAllTables(Users $User)
    {
        $modelNames = array(
            'Authors',
            'Collections',
            'Images',
            'ObjectTypes',
            'Objects',
            'PhotoTypes',
            'TempCollectionObject',
            'UserAllowedCollection',
            'Users'
        );

        foreach ($modelNames as $modelName) {
            self::updateUserFieldsInTable($modelName, $User);
        }
    }

    private static function updateUserFieldsInTable($modelName, Users $User)
    {
        $modelName::model()->updateAll(
            array('user_create' => 0),
            'user_create = :userId',
            array(':userId' => $User->id)
        );

        $modelName::model()->updateAll(
            array('user_modify' => 0),
            'user_modify = :userId',
            array(':userId' => $User->id)
        );

        $modelName::model()->updateAll(
            array('user_delete' => 0),
            'user_delete = :userId',
            array(':userId' => $User->id)
        );
    }

    /**
     * Реально удаляет все "удаленные" записи
     */
    public static function deleteDeletedRecords()
    {
        $modelNames = array(
            'Authors',
            'Collections',
            'Images',
            'ObjectTypes',
            'Objects',
            'PhotoTypes',
            'TempCollectionObject',
            'UserAllowedCollection',
            'Users'
        );

        $Transaction = Yii::app()->db->beginTransaction();

        try {
            foreach ($modelNames as $modelName) {
                self::deleteDeletedRecordsInTable($modelName);
            }
            $Transaction->commit();
        } catch (Exception $Exception) {
            $Transaction->rollback();
            throw $Exception;
        }
    }

    private static function deleteDeletedRecordsInTable($modelName)
    {
        $modelName::model()->deleteAll('deleted = 1');

    }

    /**
     * Удаляем несохраненные превью.
     */
    public static function deleteUnsavedPreviews()
    {
        $tmpFolderPath = Yii::getPathOfAlias('webroot')
            .DIRECTORY_SEPARATOR
            .Yii::app()->params['filesFolder']
            .DIRECTORY_SEPARATOR
            .Yii::app()->params['tempFilesFolder'];

        MyFileHelper::removeDirectory($tmpFolderPath);
        MyFileHelper::createDirectory($tmpFolderPath);
    }

    /**
     * Удаляет пустые поддиректории в папке превью
     */
    public static function deleteEmptyFoldersInPreviews()
    {
        $previewsFolderPath = Yii::getPathOfAlias('webroot')
            .DIRECTORY_SEPARATOR
            .Yii::app()->params['filesFolder']
            .DIRECTORY_SEPARATOR
            .Yii::app()->params['previewsFolder'];

        MyFileHelper::removeEmptySubdirectories($previewsFolderPath);
    }

    public static function uncheckHasPreviewCheckboxIfReallyHasNoPreview()
    {

    }

} 