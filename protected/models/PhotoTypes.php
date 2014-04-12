<?php

/**
 * This is the model class for table "{{photo_types}}".
 *
 * The followings are the available columns in table '{{photo_types}}':
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $date_create
 * @property string $date_modify
 * @property string $date_delete
 * @property string $sort
 * @property integer $deleted
 */
class PhotoTypes extends MyActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{photo_types}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
        return array(
            // сначала обязательные
            array('name', 'validators.MyRequiredValidator', 'on' => 'insert, update'),
            // потом общие проверки на формат
            // если атрибут не указан в обязательных, то свойство allowEmpty должно быть true
            array('sort', 'validators.MyIntegerValidator', 'on' => 'insert, update'),
            // потом отдельно на максимальную длину
            array('name', 'length', 'max'=>150, 'on' => 'insert, update'),
            array('sort', 'length', 'max'=>10, 'on' => 'insert, update'),
            // и безопасные
            array('description', 'safe'),
        );
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
        return array(
            'images' => array(self::HAS_MANY, 'Images', 'photo_type_id'),

            'userCreate' => array(self::BELONGS_TO, 'Users', 'user_create'),
            'userModify' => array(self::BELONGS_TO, 'Users', 'user_modify'),
            'userDelete' => array(self::BELONGS_TO, 'Users', 'user_delete'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
        return array(
            'name' => Yii::t('common', 'Название'),
            'description' => Yii::t('common', 'Описание'),
            'sort' => Yii::t('common', 'Сортировка'),
        );
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PhotoTypes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Проверяет, можно ли удалять запись.
     * Нельзя, если запись где-то используется.
     * @return bool можно ли удалять запись
     * @throws CException
     */
    public function isReadyToBeDeleted()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Criteria = new CDbCriteria;
        $Criteria->select = 'id';
        $Criteria->addCondition('photo_type_id = :photo_type_id');
        $Criteria->params = array(':photo_type_id' => $this->id);

        $records = Images::model()->findAll($Criteria);

        if (!empty($records)) {
            return false;
        }

        return true;
    }

    /**
     * Удаляет запись
     * @throws CException
     */
    public function deleteDictionaryRecord()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $this->deleteRecord();
    }
}
