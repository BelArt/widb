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
 * @property float $width_cm
 * @property float $height_cm
 */
class Images extends ActiveRecord
{
    private $_thumbnailBig;
    private $_thumbnailMedium;
    private $_thumbnailSmall;

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
		return array(
            // сначала обязательные
            array(
                'photo_type_id, width, height, dpi, original, source, request, code, date_photo, width_cm, height_cm',
                'validators.MyRequiredValidator',
                'except' => 'delete'
            ),
            // потом проверки на формат
            array('photo_type_id', 'validators.IntegerValidator', 'skipOnError' => true, 'except' => 'delete'),
            array('has_preview, deepzoom', 'boolean', 'strict' => true, 'skipOnError' => true, 'except' => 'delete'),
            array('width, height, dpi', 'validators.IntegerValidator', 'skipOnError' => true, 'except' => 'delete'),
            array('sort', 'validators.IntegerValidator', 'skipOnError' => true, 'except' => 'delete'),
            array('code', 'validators.CodeValidator', 'skipOnError' => true, 'except' => 'delete'),
            array('date_photo', 'date', 'skipOnError' => true, 'allowEmpty' => false, 'format' => 'dd.MM.yyyy', 'except' => 'delete'),
            array('width_cm, height_cm', 'validators.MyFloatValidator', 'maxIntegerSize' => 3, 'maxFractionalSize' => 2,  'except' => 'delete', 'skipOnError' => true),
            // потом отдельно на длину
            array('photo_type_id, sort', 'length', 'max'=>10, 'except' => 'delete', 'skipOnError' => true),
            array('width, height, dpi', 'length', 'max'=>8, 'except' => 'delete', 'skipOnError' => true),
            array('width_cm, height_cm', 'length', 'max'=>6, 'except' => 'delete', 'skipOnError' => true),
            array('original, source, request, code', 'length', 'max'=>150, 'except' => 'delete', 'skipOnError' => true),
            // и безопасные
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
            'preview' => Yii::t('common', 'Превью'),
            'width_cm' => Yii::t('images', 'Ширина, в см'),
            'height_cm' => Yii::t('images', 'Высота, в см'),
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
    }

    public function beforeSave()
    {
        if (parent::beforeSave()) {
            $this->formatDatePhotoFieldForSavingIntoDB();
            $this->formatFloatFieldForSavingIntoDB('width_cm');
            $this->formatFloatFieldForSavingIntoDB('height_cm');
            return true;
        } else {
            return false;
        }
    }

    private function formatDatePhotoFieldForSavingIntoDB()
    {
        if (!empty($this->date_photo)) {
            $this->date_photo = Yii::app()->dateFormatter->format('yyyy-MM-dd', strtotime($this->date_photo));
        }
    }

    private function formatFloatFieldForSavingIntoDB($fieldName)
    {
        if (!empty($this->$fieldName)) {
            $this->$fieldName = str_replace(',', '.', $this->$fieldName);
        }
    }

    public function afterSave()
    {
        // @@WIDB-79 start
        // проверяем на сценарий для исключения рекурсивного вызова этой функции в afterSave() после сохранения данных о превью
        if ($this->scenario != PreviewHelper::SCENARIO_SAVE_PREVIEWS) {
            PreviewHelper::savePreviews($this);
        }
        // @@WIDB-79 end

        PreviewHelper::savePreviews($this);

        parent::afterSave();
    }

    /**
     * Формирует набор превью
     */
    private function setThumbnails()
    {
        $this->_thumbnailBig = PreviewHelper::getBigThumbnailForImage($this);
        $this->_thumbnailMedium = PreviewHelper::getMediumThumbnailForImage($this);
        $this->_thumbnailSmall = PreviewHelper::getSmallThumbnailForImage($this);
    }

    public function getThumbnailBig()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        return $this->_thumbnailBig;
    }

    public function getThumbnailMedium()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        return $this->_thumbnailMedium;
    }

    public function getThumbnailSmall()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        return $this->_thumbnailSmall;
    }

    public function getName()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $name = $this->width.' x '.$this->height.' '.Yii::t('common', 'px');
        if (!empty($this->dpi)) {
            $name .= ' ['.$this->dpi.' '.Yii::t('common', 'dpi').']';
        }

        return $name;
    }

    public function getArrayOfPhotoTypes()
    {
        $Criteria = new CDbCriteria;
        $Criteria->select = 'id, name';
        $Criteria->order = 'sort ASC';

        $photoTypes = PhotoTypes::model()->findAll($Criteria);

        $result = array(
            0 => Yii::t('images', 'Тип съемки неизвестен')
        );

        foreach ($photoTypes as $PhotoType) {
            $result[$PhotoType->id] = $PhotoType->name;
        }

        return $result;
    }

    /**
     * Удаляет изображение
     * @throws Exception
     */
    public function deleteImage()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Transaction = Yii::app()->db->beginTransaction();

        try {
            $this->deleteRecord();
            PreviewHelper::deletePreview($this);
            $Transaction->commit();
        } catch (Exception $Exception) {
            $Transaction->rollback();
            throw $Exception;
        }
    }

    /**
     * Возвращает разрешение изображения
     * @return string разрешение изображения
     * @throws CException
     */
    public function getResolution()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $resolution = '';

        if (!empty($this->dpi)) {
            $resolution .= $this->dpi.' '.Yii::t('common', 'dpi');
        }

        return $resolution;
    }

    /**
     * Возвращает дату съемки изображения в виде "Съемка 12.03.2014"
     * @return string отформатированная дата съемки
     * @throws CException
     */
    public function getPhotoDateWithIntroWord()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $result = '';

        if (!empty($this->date_photo)) {
            $result .= Yii::t('images', 'Cъемка').' '.OutputHelper::formatDate($this->date_photo);
        } else {
            $result .= Yii::t('images', 'Дата съемки неизвестна');
        }

        return $result;
    }

}
