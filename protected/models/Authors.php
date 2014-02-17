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
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('deleted', 'numerical', 'integerOnly'=>true),
			array('surname, name, middlename, initials', 'length', 'max'=>150),
			array('sort', 'length', 'max'=>10),
			array('date_create, date_modify, date_delete', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, surname, name, middlename, initials, date_create, date_modify, date_delete, sort, deleted', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
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
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('middlename',$this->middlename,true);
		$criteria->compare('initials',$this->initials,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_modify',$this->date_modify,true);
		$criteria->compare('date_delete',$this->date_delete,true);
		$criteria->compare('sort',$this->sort,true);
		$criteria->compare('deleted',$this->deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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
