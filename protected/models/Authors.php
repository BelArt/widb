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
class Authors extends ActiveRecord
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
            array('initials', 'required', 'except' => 'delete', 'skipOnError' => true),
            // потом общие проверки на формат
            // если атрибут не указан в обязательных, то свойство allowEmpty должно быть true
            array('sort', 'application.components.validators.IntegerValidator', 'skipOnError' => true, 'allowEmpty' => true, 'except' => 'delete'),
            // потом отдельно на максимальную длину
            array('surname, name, middlename, initials', 'length', 'max'=>150, 'except' => 'delete', 'skipOnError' => true),
            array('sort', 'length', 'max'=>10, 'except' => 'delete', 'skipOnError' => true),
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
}
