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

            }

        }

    }
} 