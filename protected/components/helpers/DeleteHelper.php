<?php
/**
 * Хелпер для удаления сущностей в проекте
 */

class DeleteHelper extends CApplicationComponent
{

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
     * @return bool
     */
    public static function deleteObjectsFromTempCollection(array $objectIds, $collectionId)
    {
        $result = true;

        foreach ($objectIds as $id) {
            $tempResult = self::deleteObjectFromTempCollection($id, $collectionId);
            if (!$tempResult) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Удаляет объект из обычной коллекции
     * @param $id айди объекта
     * @return bool
     */
    public static function deleteObjectFromNormalCollection($id)
    {
        $Object = Objects::model()->findByPk($id);

        if (empty($Object)) {
            return false;
        }

        if ($Object->isReadyToBeDeleted()) {
            return $Object->deleteObject();
        } else {
            return false;
        }
    }

    /**
     * Удаляет объект из временной коллекции
     * @param $objectId айди объекта
     * @param $collectionId айди коллекции
     * @return bool
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
            return false;
        }

        return $Record->deleteRecord();
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
     * @return bool true - все переданные удалены, false - не все удалены
     */
    public static function deleteTempCollections(array $ids)
    {
        $result = true;

        foreach ($ids as $id) {
            $tempResult = self::deleteTempCollection($id);
            if (!$tempResult) {
                $result = false;
            }
        }

        return $result;

    }
} 