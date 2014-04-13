<?php

/**
 * Базовый класс ActiveRecord для данного проекта
 */
class MyActiveRecord extends CActiveRecord
{
    protected function beforeSave()
    {
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
            case MyPreviewHelper::SCENARIO_SAVE_PREVIEWS:
                break;
            case 'delete':
                $this->date_delete = $now;
                $this->date_modify = $now;
                $this->user_delete = $userId;
                $this->user_modify = $userId;
                break;
            default:
                throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        return parent::beforeSave();
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
     * Удаляет запись в таблицах проекта
     * Cтавит в ней флаг, что запись удалена, но не удаляет ее из таблицы
     * @throws CException
     */
    protected function deleteRecord()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $this->scenario = 'delete';
        $this->deleted = 1;

        if (!$this->save()) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }
    }

    /**
     * Проверяет, действительно ли есть картинка превью на сервере
     * @param string $size какое превью проверяем - 'small', 'medium', 'big' или 'original'
     * @throws CException
     * @return bool
     */
    public function reallyHasPreview($size = 'medium')
    {
        if (!in_array($size, array('small', 'medium', 'big', 'original'))) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        if (!in_array(get_class($this), array('Collections', 'Objects', 'Images'))) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $previewUrl = MyPreviewHelper::getPreviewUrl($this, $size);

        return !empty($previewUrl);
    }

    /**
     * Проверяет, есть ли папка с картинками превью на сервере
     * @return bool
     * @throws CException
     */
    public function reallyHasPreviews()
    {
        if (!in_array(get_class($this), array('Collections', 'Objects', 'Images'))) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $previewFolder = MyPreviewHelper::getPreviewFolderPath($this);

        return file_exists($previewFolder) && is_dir($previewFolder);
    }

    /**
     * Returns a value indicating whether the attribute is required.
     * This is determined by checking if the attribute is associated with a
     * {@link CRequiredValidator} validation rule in the current {@link scenario}.
     * @param string $attribute attribute name
     * @return boolean whether the attribute is required
     */
    public function isAttributeRequired($attribute)
    {
        foreach($this->getValidators($attribute) as $validator)
        {
            if($validator instanceof CRequiredValidator || $validator instanceof MyRequiredValidator)
                return true;
        }
        return false;
    }

    /**
     * Сохраняет подгруженные превью из временной папки в постоянное место
     * @throws CException
     */
    public function saveUploadedPreviews()
    {
        // если было что сохранить
        if (MyPreviewHelper::savePreviews($this)) {
            // ставим отметку, что превью есть
            $this->has_preview = 1;

            // обязательно для повторного сохранения, иначе при создании yii будет пытаться вставить эту запись еще раз,
            // что вызовет ошибку
            $this->isNewRecord = false;

            if (!$this->save(false)) {
                throw new CException(Yii::t('common', 'Произошла ошибка!'));
            }
        }
    }

    /**
     * Удаляет превью
     * @throws CException
     */
    public function deletePreviews()
    {
        // если было что удалять
        if (MyPreviewHelper::deletePreview($this)) {

            $this->has_preview = 0;

            if (!$this->save(false)) {
                throw new CException(Yii::t('common', 'Произошла ошибка!'));
            }
        }
    }
}