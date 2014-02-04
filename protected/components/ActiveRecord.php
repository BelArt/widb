<?php

/**
 * Базовый класс ActiveRecord для данного проекта
 */
class ActiveRecord extends CActiveRecord
{
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
            case PreviewHelper::SCENARIO_SAVE_PREVIEWS:
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
     * @throws ActiveRecordException
     */
    public function deleteRecord()
    {
        $this->scenario = 'delete';
        $this->deleted = 1;

        if (!$this->save()) {
            throw new ActiveRecordException();
        }
    }
    /*public function deleteRecord()
    {
        $this->scenario = 'delete';
        $this->deleted = 1;
        if ($this->save()) {
            return true;
        }
        return false;
    }*/

} 