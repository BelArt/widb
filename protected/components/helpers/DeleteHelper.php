<?php
/**
 * Хелпер для удаления сущностей в проекте
 */

class DeleteHelper extends CApplicationComponent
{
    /**
     * Удаляет изображение
     * @param $id айди изображения
     * @throws DeleteHelperException
     */
    public static function deleteImage($id)
    {
        try {
            $Image = Images::model()->findByPk($id);
            if (empty($Image)) {
                throw new DeleteHelperException();
            }
            $Image->deleteImage();
        } catch (DeleteHelperException $Exception) {
            throw $Exception;
        } catch (Exception $Exception) {
            throw new DeleteHelperException($Exception);
        }
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
     * @param $objectIds айди объектов для удаления
     * @param $collectionId айди временной коллекции
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
     * @throws DeleteHelperException
     */
    public static function deleteObjectFromNormalCollection($id)
    {
        $Object = Objects::model()->findByPk($id);

        if (empty($Object)) {
            throw new DeleteHelperException();
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
     * @throws DeleteHelperException
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
            throw new DeleteHelperException();
        }

        $Record->deleteRecord();
    }

    /**
     * Удаляет обычную коллекцию
     * @param int $id айди обычной коллекции
     * @return bool true - удалена, false - не удалена, из-за того, что не выполнены все необходимые для удаления условия
     * @throws DeleteHelperException
     */
    public static function deleteNormalCollection($id)
    {
        $Collection = Collections::model()->findByPk($id);

        if (empty($Collection) || $Collection->temporary) {
            throw new DeleteHelperException();
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
     * @throws DeleteHelperException
     */
    public static function deleteTempCollection($id)
    {
        $Collection = Collections::model()->findByPk($id);

        if (empty($Collection) || !$Collection->temporary) {
            throw new DeleteHelperException();
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
} 