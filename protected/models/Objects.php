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
 * @property string $code
 * @property string $width
 * @property string $height
 * @property string $depth
 * @property string $period
 * @property integer $has_preview
 * @property string $department
 * @property string $keeper
 * @property string $date_create
 * @property string $date_modify
 * @property string $date_delete
 * @property string $sort
 * @property integer $deleted
 * @property string $user_create
 * @property string $user_modify
 * @property string $user_delete
 */
class Objects extends ActiveRecord
{
    private $thumbnailBig;
    private $thumbnailMedium;
    private $thumbnailSmall;

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
		return array(
            // сначала обязательные
            array('name, type_id, inventory_number, code', 'validators.MyRequiredValidator', 'on' => 'insert, update'),
            // проверки на формат
            array('author_id, collection_id', 'validators.IntegerValidator', 'on' => 'insert, update'),
            array('type_id', 'validators.IntegerValidator', 'on' => 'insert, update'),
            array('code', 'validators.CodeValidator', 'on' => 'insert'),
            array('width, height, depth', 'validators.MyFloatValidator', 'maxIntegerSize' => 3, 'maxFractionalSize' => 2,  'on' => 'insert, update'),
            array('has_preview', 'boolean', 'on' => 'insert, update'),
            array('sort', 'validators.IntegerValidator', 'on' => 'insert, update'),
            // на длину
            array('name, code, department, keeper, period, author_text', 'length', 'max'=>150, 'on' => 'insert, update'),
            array('inventory_number', 'length', 'max'=>50, 'on' => 'insert, update'),
            array('width, height, depth', 'length', 'max'=>6, 'on' => 'insert, update'),
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
            'author' => array(self::BELONGS_TO, 'Authors', 'author_id'),
            'type' => array(self::BELONGS_TO, 'ObjectTypes', 'type_id'),
            'collection' => array(self::BELONGS_TO, 'Collections', 'collection_id'),

            'images' => array(self::HAS_MANY, 'Images', 'object_id'),

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
            'author_id' => Yii::t('objects', 'Автор'),
            'type_id' => Yii::t('objects', 'Тип объекта'),
            'collection_id' => Yii::t('collections', 'Коллекция'),
            'name' => Yii::t('common', 'Название'),
            'description' => Yii::t('common', 'Описание'),
            'inventory_number' => Yii::t('objects', 'Инвентарный номер'),
            'code' => Yii::t('common', 'Код'),
            'width' => Yii::t('objects', 'Ширина'),
            'height' => Yii::t('objects', 'Высота'),
            'depth' => Yii::t('objects', 'Глубина'),
            'department' => Yii::t('objects', 'Отдел'),
            'keeper' => Yii::t('objects', 'Хранитель'),
            'period' => Yii::t('objects', 'Период создания'),
            'has_preview' => Yii::t('common', 'Есть превью'),
            'sort' => Yii::t('common', 'Сортировка'),
            'preview' => Yii::t('common', 'Превью'),
            'size' => Yii::t('common', 'Размер'),
            'author_text' => Yii::t('objects', 'Автор (текстом)'),
        );
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

    protected function afterFind()
    {
        parent::afterFind();

        // формируем набор превью
        $this->setThumbnails();
    }

    /**
     * Формирует набор превью
     */
    private function setThumbnails()
    {
        $this->thumbnailBig = PreviewHelper::getBigThumbnailForObject($this);
        $this->thumbnailMedium = PreviewHelper::getMediumThumbnailForObject($this);
        $this->thumbnailSmall = PreviewHelper::getSmallThumbnailForObject($this);
    }

    public function getThumbnailBig()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        return $this->thumbnailBig;
    }

    public function getThumbnailMedium()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        return $this->thumbnailMedium;
    }

    public function getThumbnailSmall()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        return $this->thumbnailSmall;
    }

    /**
     * Получает массив вида id => Имя автора для
     * построения селекта Автор на форме
     * создания\редактирования объекта
     * @return array
     */
    public function getArrayOfPossibleAuthors()
    {
        $Criteria = new CDbCriteria;
        $Criteria->select = 'id, initials';
        $Criteria->order = 'sort ASC';

        $authors = Authors::model()->findAll($Criteria);

        $result = array(
            0 => Yii::t('objects', 'Автор неизвестен')
        );

        if (empty($authors)) {
            return $result;
        }

        foreach ($authors as $Author) {
            $result[$Author->id] = $Author->initials;
        }

        return $result;
    }

    /**
     * Получает массив вида id => Тип объекта для
     * построения селекта Тип объекта на форме
     * создания\редактирования объекта
     * @return array
     */
    public function getArrayOfPossibleObjectTypes()
    {
        $Criteria = new CDbCriteria;
        $Criteria->select = 'id, name';
        $Criteria->order = 'sort ASC';

        $types = ObjectTypes::model()->findAll($Criteria);

        $result = array();

        if (empty($types)) {
            return $result;
        }

        foreach ($types as $Type) {
            $result[$Type->id] = $Type->name;
        }

        return $result;
    }


    /**
     * Проверяет, можно ли удалить объект
     * @throws CException
     * @return bool
     */
    public function isReadyToBeDeleted()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Criteria = new CDbCriteria;
        $Criteria->select = 'id';
        $Criteria->addCondition('object_id = :object_id');
        $Criteria->params = array(':object_id' => $this->id);

        $images = Images::model()->findAll($Criteria);

        if (!empty($images)) {
            return false;
        }

        return true;
    }

    /**
     * Удаляет объект
     * @throws CException
     */
    public function deleteObject()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Transaction = Yii::app()->db->beginTransaction();

        try {

            // удаляем объект
            $this->deleteRecord();

            // удаляем объект из всех временных колекций
            $Criteria = new CDbCriteria;
            $Criteria->select = 'id';
            $Criteria->addCondition('object_id = :object_id');
            $Criteria->params = array(':object_id' => $this->id);

            $records = TempCollectionObject::model()->findAll($Criteria);

            foreach ($records as $Record) {
                $Record->deleteRecord();
            }

            // удаляем превью
            PreviewHelper::deletePreview($this);

            // раз все ок - завершаем транзакцию
            $Transaction->commit();

        } catch (Exception $Exception) {

            $Transaction->rollback();
            throw $Exception;
        }
    }

    /**
     * Проверяет, доступен ли текущий объект пользователю.
     * @param integer $userId айдишник пользователя
     * @throws CException
     * @return bool
     */
    public function isAllowedToUser($userId)
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        return self::getObjectIsAllowedToUser($this->id, $userId);
    }

    /**
     * Проверяет, доступна ли объект пользователю.
     * @param integer $objectId айди объекта
     * @param integer $userId айди пользователя
     * @return bool
     * @throws CException
     */
    public static function getObjectIsAllowedToUser($objectId, $userId)
    {
        $Object = Objects::model()->findByPk($objectId);

        if (empty($Object)) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        return in_array($Object->collection_id, Collections::getIdsOfCollectionsAllowedToUser($userId));
    }

    /**
     * Добавляет текущий объект во временную коллекцию
     * @param int $tempCollectionId айди временной коллекции
     * @throws CException
     */
    public function addToTempCollection($tempCollectionId)
    {
        $Collection = Collections::model()->findByPk($tempCollectionId);

        if (empty($Collection) || $Collection->temporary == 0) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $TempCollectionObject = TempCollectionObject::model()->findByAttributes(array(
            'collection_id' => $tempCollectionId,
            'object_id' => $this->id
        ));

        if (!empty($TempCollectionObject)) {
            return;
        }

        $TempCollectionObject = new TempCollectionObject();
        $TempCollectionObject->collection_id = $tempCollectionId;
        $TempCollectionObject->object_id = $this->id;

        if (!$TempCollectionObject->save()) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

    }

    public function moveToCollection($param)
    {
        $Collection = null;

        if (is_numeric($param)) {
            $Collection = Collections::model()->findByPk($param);
        } elseif (is_object($param) && get_class($param) == 'Collections') {
            $Collection = $param;
        }

        if (empty($Collection) || $Collection->temporary == 1) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Transaction = Yii::app()->db->beginTransaction();
        try {
            $this->collection_id = $Collection->id;
            if ($this->save()) {
                PreviewHelper::moveObjectToOtherCollection($this, $Collection);
                $Transaction->commit();
            } else {
                $Transaction->rollback();
            }
        } catch (Exception $Exception) {
            $Transaction->rollback();
            throw $Exception;
        }
    }

    /**
     * Возвращает размер объекта в формате Длина х Ширина х Высота
     * @return string размер объекта в формате Длина х Ширина х Высота
     * @throws CException
     */
    public function getSize()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $size = '';

        if ($this->width != '0.00') {
            $size .= OutputHelper::formatNumber($this->width);
            if ($this->height != '0.00') {
                $size .= ' x '.OutputHelper::formatNumber($this->height);
            }
            if ($this->depth != '0.00') {
                $size .= ' x '.OutputHelper::formatNumber($this->depth);
            }
            $size .= ' '.Yii::t('common', 'см');
        }

        return $size;
    }

    /**
     * Возвращает инициалы автора объекта
     * @return string
     * @throws CException
     */
    public function getAuthorInitials()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $authorInitials = '';

        if (!empty($this->author->initials)) {
            $authorInitials .= $this->author->initials;
        } elseif(!empty($this->author_text)) {
            $authorInitials .= $this->author_text;
        } else {
            $authorInitials .= Yii::t('objects', 'Автор неизвестен');
        }

        return $authorInitials;
    }

    public function beforeSave()
    {
        if (parent::beforeSave()) {
            $this->formatFloatFieldForSavingIntoDB('width');
            $this->formatFloatFieldForSavingIntoDB('height');
            $this->formatFloatFieldForSavingIntoDB('depth');
            return true;
        } else {
            return false;
        }
    }

    private function formatFloatFieldForSavingIntoDB($fieldName)
    {
        if (!empty($this->$fieldName)) {
            $this->$fieldName = str_replace(',', '.', $this->$fieldName);
        }
    }
}
