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
    private $thumbnailBig;
    private $thumbnailMedium;
    private $thumbnailSmall;
    private $name;

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
        return array(
            'object' => array(self::BELONGS_TO, 'Objects', 'object_id'),
            'photoType' => array(self::BELONGS_TO, 'PhotoTypes', 'photo_type_id'),

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
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Images the static model class
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

        // создаем фиктивный атрибут Имя
        $this->setName();
    }

    /**
     * Формирует набор превью
     */
    private function setThumbnails()
    {
        $this->thumbnailBig = PreviewHelper::getBigThumbnailForImage($this);
        $this->thumbnailMedium = PreviewHelper::getMediumThumbnailForImage($this);
        $this->thumbnailSmall = PreviewHelper::getSmallThumbnailForImage($this);
    }

    public function getThumbnailBig()
    {
        if ($this->isNewRecord) {
            throw new ImagesException();
        }

        return $this->thumbnailBig;
    }

    public function getThumbnailMedium()
    {
        if ($this->isNewRecord) {
            throw new ImagesException();
        }

        return $this->thumbnailMedium;
    }

    public function getThumbnailSmall()
    {
        if ($this->isNewRecord) {
            throw new ImagesException();
        }

        return $this->thumbnailSmall;
    }

    /**
     * создаем фиктивный атрибут Имя
     */
    private function setName()
    {
        $this->name = $this->width.' x '.$this->height.' px';
    }

    public function getName()
    {
        if ($this->isNewRecord) {
            throw new ImagesException();
        }

        return $this->name;
    }

}
