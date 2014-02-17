<?php

/**
 * Базовый класс ActiveRecord для данного проекта
 */
class ActiveRecord extends CActiveRecord
{
    public function beforeSave()
    {
        /*
         * Обрабатываем запись в поля date_... и user_...
         */

        try {
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
                    throw new ActiveRecordException();
            }
            return parent::beforeSave();
        } catch (ActiveRecordException $Exception) {
            throw $Exception;
        } catch (Exception $Exception) {
            throw new ActiveRecordException($Exception);
        }
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

    /**
     * Проверяет, действительно ли есть картинка превью на сервере
     * @param string $size какое превью проверяем - 'small', 'medium', 'big' или 'original'
     * @throws ActiveRecordException
     * @return bool
     */
    public function reallyHasPreview($size = 'medium')
    {
        if (!in_array($size, array('small', 'medium', 'big', 'original'))) {
            throw new ActiveRecordException();
        }

        if (!in_array(get_class($this), array('Collections', 'Objects', 'Images'))) {
            throw new ActiveRecordException();
        }

        if ($this->isNewRecord) {
            throw new ActiveRecordException();
        }

        $previewUrl = PreviewHelper::getPreviewUrl($this, $size);

        return !empty($previewUrl);
    }

    /**
     * Проверяет, есть ли папка с картинками превью на сервере
     * @return bool
     * @throws ActiveRecordException
     */
    public function reallyHasPreviews()
    {
        if (!in_array(get_class($this), array('Collections', 'Objects', 'Images'))) {
            throw new ActiveRecordException();
        }

        if ($this->isNewRecord) {
            throw new ActiveRecordException();
        }

        $previewFolder = PreviewHelper::getPreviewFolderPath($this);

        return file_exists($previewFolder) && is_dir($previewFolder);
    }

}