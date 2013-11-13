<?php

/**
 * This is the model class for table "{{images}}".
 *
 * The followings are the available columns in table '{{images}}':
 * @property string $id
 * @property string $object_id
 * @property string $photo_type_id
 * @property string $description
 * @property integer $has_preview
 * @property string $width
 * @property string $height
 * @property string $dpi
 * @property string $original
 * @property string $source
 * @property integer $deepzoom
 * @property string $request
 * @property string $date_create
 * @property string $date_modify
 * @property string $date_delete
 * @property string $sort
 * @property integer $deleted
 */
class Images extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{images}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('has_preview, deepzoom, deleted', 'numerical', 'integerOnly'=>true),
			array('object_id, photo_type_id, sort', 'length', 'max'=>10),
			array('width, height, dpi', 'length', 'max'=>8),
			array('original, source, request', 'length', 'max'=>150),
			array('description, date_create, date_modify, date_delete', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, object_id, photo_type_id, description, has_preview, width, height, dpi, original, source, deepzoom, request, date_create, date_modify, date_delete, sort, deleted', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'object_id' => 'Object',
			'photo_type_id' => 'Photo Type',
			'description' => 'Description',
			'has_preview' => 'Has Preview',
			'width' => 'Width',
			'height' => 'Height',
			'dpi' => 'Dpi',
			'original' => 'Original',
			'source' => 'Source',
			'deepzoom' => 'Deepzoom',
			'request' => 'Request',
			'date_create' => 'Date Create',
			'date_modify' => 'Date Modify',
			'date_delete' => 'Date Delete',
			'sort' => 'Sort',
			'deleted' => 'Deleted',
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
		$criteria->compare('object_id',$this->object_id,true);
		$criteria->compare('photo_type_id',$this->photo_type_id,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('has_preview',$this->has_preview);
		$criteria->compare('width',$this->width,true);
		$criteria->compare('height',$this->height,true);
		$criteria->compare('dpi',$this->dpi,true);
		$criteria->compare('original',$this->original,true);
		$criteria->compare('source',$this->source,true);
		$criteria->compare('deepzoom',$this->deepzoom);
		$criteria->compare('request',$this->request,true);
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
	 * @return Images the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
