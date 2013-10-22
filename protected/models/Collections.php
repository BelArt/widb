<?php

/**
 * This is the model class for table "{{collections}}".
 *
 * The followings are the available columns in table '{{collections}}':
 * @property string $id
 * @property string $parent_id
 * @property string $name
 * @property string $description
 * @property string $code
 * @property string $image
 * @property integer $temporary
 * @property integer $has_preview
 * @property string $date_create
 * @property string $date_modify
 * @property string $date_delete
 * @property string $sort
 * @property integer $deleted
 */
class Collections extends CActiveRecord
{
    /**
     * @var array массив с моделями-детьми (у кого parent_id = id)
     * Сделан не через геттер\сеттер из-за того, что  иначе возникает
     * indirect modification of overloaded property has no effect
     */
    public $children;

    private $_thumbnailBig;
    private $_thumbnailMedium;
    private $_thumbnailSmall;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{collections}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('temporary, has_preview, deleted', 'numerical', 'integerOnly'=>true),
			array('parent_id, sort', 'length', 'max'=>10),
			array('name, code, image', 'length', 'max'=>150),
			array('description, date_create, date_modify, date_delete', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parent_id, name, description, code, image, temporary, has_preview, date_create, date_modify, date_delete, sort, deleted', 'safe', 'on'=>'search'),
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
			'parent_id' => 'Parent',
			'name' => 'Name',
			'description' => 'Description',
			'code' => 'Code',
			'image' => 'Image',
			'temporary' => 'Temporary',
			'has_preview' => 'Has Preview',
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
		$criteria->compare('parent_id',$this->parent_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('temporary',$this->temporary);
		$criteria->compare('has_preview',$this->has_preview);
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
	 * @return Collections the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    protected function afterFind()
    {
        parent::afterFind();

        // формируем набор превью
        $this->setThumbnails();
    }

    /**
     * Формирует набор превью
     */
    protected function setThumbnails()
    {
        $this->thumbnailBig = ImageHelper::getBigThumbnailForCollection($this);
        $this->thumbnailMedium = ImageHelper::getMediumThumbnailForCollection($this);
        $this->thumbnailSmall = ImageHelper::getSmallThumbnailForCollection($this);
    }

    /**
     * Возвращает дерево моделей. Дочерние модели у каждой модели хранятся в
     * поле {@link children}. Дочерние модели определяются по parent_id
     * @return array массив с моделями
     */
    public static function getTree()
    {
        $Criteria = new CDbCriteria;
        $Criteria->addCondition('parent_id = 0');
        $Criteria->addCondition('deleted = 0');

        $tree = self::model()->findAll($Criteria);

        foreach ($tree as $Collection) {
            self::getChildrenCollections($Collection);
        }

        return $tree;
    }

    /**
     * Для переданной модели находит дочерние модели и кладет их в поле {@link children}
     * самой модели. Дочерние модели определяются по parent_id
     * @param Collections $Collection модель
     */
    private static function getChildrenCollections(Collections $Collection)
    {
        $Criteria = new CDbCriteria;
        $Criteria->addCondition('parent_id = '.$Collection->id);

        $Collection->children = self::model()->findAll($Criteria);

        foreach ($Collection->children as $ChildCollection) {
            self::getChildrenCollections($ChildCollection);
        }
    }

    /**
     * Возвращает структуру для передачи в виджет TreeView
     * @return array
     */
    public static function getStructureForTreeViewWidget()
    {
        $result = array();

        $CollectionsTree = self::getTree();
        foreach ($CollectionsTree as $Collection) {
            $result[] = array(
                'text' => '<a href="#">'.$Collection->name.'</a>',
                'children' => self::getChildrenStructure($Collection),
            );
        }

        return $result;
    }

    /**
     * Для переданной модели возвращает структуру ее детей для виджета TreeView
     * @param Collections $Collection модель
     * @return array
     */
    private static function getChildrenStructure(Collections $Collection)
    {
        $result = array();

        foreach ($Collection->children as $ChildCollection) {
            $result[] = array(
                'text' => '<a href="#">'.$ChildCollection->name.'</a>',
                'children' => self::getChildrenStructure($ChildCollection),
            );
        }

        return $result;
    }


    public function getThumbnailBig()
    {
        return $this->_thumbnailBig;
    }

    public function setThumbnailBig($value)
    {
        $this->_thumbnailBig = $value;
    }

    public function getThumbnailMedium()
    {
        return $this->_thumbnailMedium;
    }

    public function setThumbnailMedium($value)
    {
        $this->_thumbnailMedium = $value;
    }

    public function getThumbnailSmall()
    {
        return $this->_thumbnailSmall;
    }

    public function setThumbnailSmall($value)
    {
        $this->_thumbnailSmall = $value;
    }
}
