<?php

/**
 * This is the model class for table "{{user_allowed_collection}}".
 *
 * The followings are the available columns in table '{{user_allowed_collection}}':
 * @property string $id
 * @property string $collection_id
 * @property string $user_id
 * @property string $date_create
 * @property string $date_modify
 * @property string $date_delete
 * @property string $sort
 * @property integer $deleted
 * @property string $user_create
 * @property string $user_modify
 * @property string $user_delete
 */
class UserAllowedCollection extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user_allowed_collection}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
        return array(
            // сначала обязательные
            array('collection_id, user_id', 'validators.MyRequiredValidator', 'on' => 'insert, update'),
            // потом общие проверки на формат
            array('sort, collection_id, user_id', 'validators.IntegerValidator', 'on' => 'insert, update'),
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
            'collection' => array(self::BELONGS_TO, 'Collections', 'collection_id'),
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),

            'userCreate' => array(self::BELONGS_TO, 'User', 'user_create'),
            'userModify' => array(self::BELONGS_TO, 'User', 'user_modify'),
            'userDelete' => array(self::BELONGS_TO, 'User', 'user_delete'),
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
			'user_id' => 'User',
			'date_create' => 'Date Create',
			'date_modify' => 'Date Modify',
			'date_delete' => 'Date Delete',
			'sort' => 'Sort',
			'deleted' => 'Deleted',
			'user_create' => 'User Create',
			'user_modify' => 'User Modify',
			'user_delete' => 'User Delete',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserAllowedCollection the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function deleteUserAllowedCollection()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        parent::deleteRecord();
    }
}
