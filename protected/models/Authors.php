<?php

/**
 * This is the model class for table "{{authors}}".
 *
 * The followings are the available columns in table '{{authors}}':
 * @property string $id
 * @property string $surname
 * @property string $name
 * @property string $middlename
 * @property string $initials
 * @property string $date_create
 * @property string $date_modify
 * @property string $date_delete
 * @property string $sort
 * @property integer $deleted
 */
class Authors extends MyActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{authors}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
            // сначала обязательные
            array('initials', 'validators.MyRequiredValidator', 'on' => 'insert, update'),
            // потом общие проверки на формат
            // если атрибут не указан в обязательных, то свойство allowEmpty должно быть true
            array('sort', 'validators.MyIntegerValidator', 'on' => 'insert, update'),
            // потом отдельно на максимальную длину
            array('surname, name, middlename, initials', 'length', 'max'=>150, 'on' => 'insert, update'),
            array('sort', 'length', 'max'=>10, 'on' => 'insert, update'),
            // и безопасные
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'objects' => array(self::HAS_MANY, 'Objects', 'author_id'),

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
			'surname' => Yii::t('admin', 'Фамилия'),
			'name' => Yii::t('admin', 'Имя'),
			'middlename' => Yii::t('admin', 'Отчество'),
			'initials' => Yii::t('admin', 'ФИО'),
            'sort' => Yii::t('common', 'Сортировка'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Authors the static model class
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
        $Criteria->addCondition('author_id = :author_id');
        $Criteria->params = array(':author_id' => $this->id);

        $records = Objects::model()->findAll($Criteria);

        if (!empty($records)) {
            return false;
        }

        return true;
    }

    /**
     * Удаляет запись
     * @throws DictionariesException
     */
    public function deleteDictionaryRecord()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $this->deleteRecord();
    }
}
