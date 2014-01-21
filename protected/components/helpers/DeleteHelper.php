<?php
/**
 * Хелпер для удаления сущностей в проекте
 */

class DeleteHelper extends CApplicationComponent
{

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
} 