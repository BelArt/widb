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
 * @property string $code
 * @property string $date_photo
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

            // сначала формат
            array('object_id', 'application.components.validators.IntegerValidator', 'required' => true, 'length' => 10),
            array('photo_type_id', 'application.components.validators.IntegerValidator', 'required' => true, 'length' => 10),
            array('has_preview', 'application.components.validators.BooleanValidator'),
            array('width', 'application.components.validators.IntegerValidator', 'required' => true, 'length' => 8),
            array('height', 'application.components.validators.IntegerValidator', 'required' => true, 'length' => 8),
            array('dpi', 'application.components.validators.IntegerValidator', 'required' => true, 'length' => 8),
            array('original', 'application.components.validators.UniversalValidator', 'required' => true, 'length' => 150),
            array('source', 'application.components.validators.UniversalValidator', 'required' => true, 'length' => 150),
            array('deepzoom', 'application.components.validators.BooleanValidator'),
            array('request', 'application.components.validators.UniversalValidator', 'required' => true, 'length' => 150),
            array('sort', 'application.components.validators.IntegerValidator', 'length' => 10),
            array('code', 'application.components.validators.UniversalValidator', 'length' => 150),
            array('date_photo', 'application.components.validators.DateTimeValidator'),

            // затем логика
            array('code', 'application.components.validators.CodeValidator'),

            // безопасные
            array('description', 'safe'),
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
			'object_id' => Yii::t('images', 'Объект'),
			'photo_type_id' => Yii::t('images', 'Тип съемки'),
            'description' => Yii::t('common', 'Описание'),
            'has_preview' => Yii::t('common', 'Есть превью'),
			'width' => Yii::t('images', 'Ширина'),
			'height' => Yii::t('images', 'Высота'),
			'dpi' => Yii::t('images', 'dpi'),
			'original' => Yii::t('images', 'Путь хранения оригинала'),
			'source' => Yii::t('images', 'Путь хранения исходника'),
			'deepzoom' => Yii::t('images', 'Есть DeepZoom'),
			'request' => Yii::t('images', 'Заявка'),
            'code' => Yii::t('common', 'Код'),
            'date_photo' => Yii::t('images', 'Дата съемки'),
            'sort' => Yii::t('common', 'Сортировка'),
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
