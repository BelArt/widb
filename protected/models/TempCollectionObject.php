<?php

/**
 * This is the model class for table "{{temp_collection_object}}".
 *
 * The followings are the available columns in table '{{temp_collection_object}}':
 * @property string $id
 * @property string $collection_id
 * @property string $object_id
 * @property string $date_create
 * @property string $date_modify
 * @property string $date_delete
 * @property string $sort
 * @property integer $deleted
 */
class TempCollectionObject extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{temp_collection_object}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
        return array(
            // сначала обязательные
            array('collection_id, object_id', 'validators.MyRequiredValidator', 'on' => 'insert, update'),
            // потом общие проверки на формат
            array('sort, collection_id, object_id', 'validators.IntegerValidator', 'on' => 'insert, update'),
            // потом отдельно на максимальную длину
            // и безопасные
        );
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
        return array(
            'collections' => array(self::BELONGS_TO, 'Collections', 'collection_id'),
            'object' => array(self::BELONGS_TO, 'Objects', 'object_id'),

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
			'id' => 'ID',
			'collection_id' => 'Collection',
			'object_id' => 'Object',
			'date_create' => 'Date Create',
			'date_modify' => 'Date Modify',
			'date_delete' => 'Date Delete',
			'sort' => 'Sort',
			'deleted' => 'Deleted',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TempCollectionObject the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
