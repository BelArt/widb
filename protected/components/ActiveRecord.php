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
            case 'delete':
                $this->date_delete = $now;
                $this->date_modify = $now;
                $this->user_delete = $userId;
                $this->user_modify = $userId;
                break;
            default:
                throw new CException('Установлен неизвестный сценарий!');
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
} 