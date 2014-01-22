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
} 