<?php

/**
 * This is the model class for table "{{objects}}".
 *
 * The followings are the available columns in table '{{objects}}':
 * @property string $id
 * @property string $author_id
 * @property string $type_id
 * @property string $collection_id
 * @property string $name
 * @property string $description
 * @property string $inventory_number
 * @property string $inventory_number_en
 * @property string $code
 * @property string $width
 * @property string $height
 * @property string $depth
 * @property integer $has_preview
 * @property string $department
 * @property string $keeper
 * @property string $date_create
 * @property string $date_modify
 * @property string $date_delete
 * @property string $sort
 * @property integer $deleted
 */
class Objects extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{objects}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('has_preview, deleted', 'numerical', 'integerOnly'=>true),
			array('author_id, type_id, collection_id, sort', 'length', 'max'=>10),
			array('name, code, department, keeper', 'length', 'max'=>150),
			array('inventory_number, inventory_number_en', 'length', 'max'=>50),
			array('width, height, depth', 'length', 'max'=>5),
			array('description, date_create, date_modify, date_delete', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, author_id, type_id, collection_id, name, description, inventory_number, inventory_number_en, code, width, height, depth, has_preview, department, keeper, date_create, date_modify, date_delete, sort, deleted', 'safe', 'on'=>'search'),
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
			'author_id' => 'Author',
			'type_id' => 'Type',
			'collection_id' => 'Collection',
			'name' => 'Name',
			'description' => 'Description',
			'inventory_number' => 'Inventory Number',
			'inventory_number_en' => 'Inventory Number En',
			'code' => 'Code',
			'width' => 'Width',
			'height' => 'Height',
			'depth' => 'Depth',
			'has_preview' => 'Has Preview',
			'department' => 'Department',
			'keeper' => 'Keeper',
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
		$criteria->compare('author_id',$this->author_id,true);
		$criteria->compare('type_id',$this->type_id,true);
		$criteria->compare('collection_id',$this->collection_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('inventory_number',$this->inventory_number,true);
		$criteria->compare('inventory_number_en',$this->inventory_number_en,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('width',$this->width,true);
		$criteria->compare('height',$this->height,true);
		$criteria->compare('depth',$this->depth,true);
		$criteria->compare('has_preview',$this->has_preview);
		$criteria->compare('department',$this->department,true);
		$criteria->compare('keeper',$this->keeper,true);
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
	 * @return Objects the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
