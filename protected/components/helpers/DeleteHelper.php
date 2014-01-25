<?php
/**
 * Хелпер для удаления сущностей в проекте
 */

class DeleteHelper extends CApplicationComponent
{

    /**
     * Удаляет объекты из обычной коллекции
     * @param array $params массив с параметрами
     * @throws CException
     * @todo отрефакторить - оставить только логику удаления
     */
    public static function deleteObjects($params)
    {
        if (!empty($params['ids'])) {

            if (!is_array($params['ids'])) {
                throw new CException(Yii::t('common', 'Произошла ошибка!'));
            }

            foreach ($params['ids'] as $objectId) {

                $Object = Objects::model()->findByPk($objectId);

                if (empty($Object)) {
                    continue;
                }

                Yii::app()->user->setFlash(
                    'success',
                    Yii::t('objects', 'Все выбранные объекты удалены!')
                );

                if ($Object->isReadyToBeDeleted()) {
                    if (!$Object->deleteObject()) {
                        Yii::app()->user->setFlash('success', null);
                        Yii::app()->user->setFlash(
                            'error',
                            Yii::t('objects', 'Некоторые объекты удалить не получилось. У объекта не должно быть относящихся к нему изображений, чтобы его можно было удалить.')
                        );
                    }
                } else {
                    Yii::app()->user->setFlash('success', null);
                    Yii::app()->user->setFlash(
                        'error',
                        Yii::t('objects', 'Некоторые объекты удалить не получилось. У объекта не должно быть относящихся к нему изображений, чтобы его можно было удалить.')
                    );
                }
            }

        }

    }

    /**
     * Удаляет объекты из временной коллекции
     * @param array $params массив с параметрами
     * @throws CException
     * @todo отрефакторить - оставить только логику удаления
     */
    public static function deleteObjectsFromTempCollection($params)
    {
        if (!empty($params['ids']) && !empty($params['collectionId'])) {

            if (!is_array($params['ids'])) {
                throw new CException(Yii::t('common', 'Произошла ошибка!'));
            }

            $Criteria = new CDbCriteria;
            $Criteria->select = 'id';
            $Criteria->addCondition('collection_id = :collection_id');
            $Criteria->params = array(
                ':collection_id' => $params['collectionId']
            );
            $Criteria->addInCondition('object_id', $params['ids']);


            $records = TempCollectionObject::model()->findAll($Criteria);

            if (!empty($records)) {

                Yii::app()->user->setFlash(
                    'success',
                    Yii::t('objects', 'Все выбранные объекты из временной коллекции удалены!')
                );

                foreach ($records as $Record) {
                    if (!$Record->deleteRecord()) {
                        Yii::app()->user->setFlash('success', null);
                        Yii::app()->user->setFlash(
                            'error',
                            Yii::t('objects', 'Некоторые объекты удалить из временной коллекции не получилось.')
                        );
                    }
                }
            }

        }
    }

    /**
     * Удаляет обычную коллекцию
     * @param int $id айди обычной коллекции
     * @return bool true - удалена, false - не удалены
     */
    public static function deleteNormalCollection($id)
    {
        $Collection = Collections::model()->findByPk($id);

        if (empty($Collection) || $Collection->temporary) {
            return false;
        }

        if ($Collection->isReadyToBeDeleted()) {
            return $Collection->deleteNormalCollection();
        } else {
            return false;
        }
    }

    /**
     * Удаляет временную коллекцию
     * @param int $id айди временную коллекции
     * @return bool true - удалена, false - не удалены
     */
    public static function deleteTempCollection($id)
    {
        $Collection = Collections::model()->findByPk($id);

        if (empty($Collection) || !$Collection->temporary) {
            return false;
        }

        return $Collection->deleteTempCollection();
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