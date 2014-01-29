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

            array('name, description, type_id, inventory_number, code', 'required'),
            array('has_preview', 'boolean'),
            array('author_id, type_id, sort', 'application.components.validators.EmptyOrPositiveIntegerValidator'),
            array('period, code, department, keeper', 'length', 'max'=>150),
            array('inventory_number', 'length', 'max'=>50),
            array('width, height, depth', 'numerical', 'numberPattern' => '/^\d\d{0,2}(\.\d{1,2})?$/', 'message' => Yii::t('objects', 'значение должно быть в формате xxx.xx'), 'allowEmpty' => true),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			//array('id, author_id, type_id, collection_id, name, description, inventory_number, inventory_number_en, code, width, height, depth, has_preview, department, keeper, date_create, date_modify, date_delete, sort, deleted', 'safe', 'on'=>'search'),
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
            // такого арибута в модели нет, это только для вывода лэйбла на форму
            'preview' => Yii::t('common', 'Превью'),
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
	/*public function search()
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
	}*/

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
     * @throws CException
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
            throw new CException(Yii::t('objects', 'Метод "{method}" не может вызываться для вновь создаваемого объекта', array('{method}' => __METHOD__)));
        }

        return $this->thumbnailBig;
    }

    public function getThumbnailMedium()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('objects', 'Метод "{method}" не может вызываться для вновь создаваемого объекта', array('{method}' => __METHOD__)));
        }

        return $this->thumbnailMedium;
    }

    public function getThumbnailSmall()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('objects', 'Метод "{method}" не может вызываться для вновь создаваемого объекта', array('{method}' => __METHOD__)));
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

    public function afterSave()
    {
        // проверяем на сценарий для исключения рекурсивного вызова этой функции в afterSave() после сохранения данных о превью
        if ($this->scenario != PreviewHelper::SCENARIO_SAVE_PREVIEWS) {
            PreviewHelper::savePreviews($this);
        }

        parent::afterSave();
    }

    /**
     * Проверяет, можно ли удалить объект
     * @throws ObjectsException
     * @return bool
     */
    public function isReadyToBeDeleted()
    {
        if ($this->isNewRecord) {
            throw new ObjectsException();
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
     * @throws ObjectsException
     */
    public function deleteObject()
    {
        if ($this->isNewRecord) {
            throw new ObjectsException();
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
     * @throws ObjectsException
     * @return bool
     */
    public function isAllowedToUser($userId)
    {
        if ($this->isNewRecord) {
            throw new ObjectsException();
        }

        return self::getObjectIsAllowedToUser($this->id, $userId);
    }

    /**
     * Проверяет, доступна ли объект пользователю.
     * @param integer $objectId айди объекта
     * @param integer $userId айди пользователя
     * @return bool
     * @throws ObjectsException
     */
    public static function getObjectIsAllowedToUser($objectId, $userId)
    {
        $Object = Objects::model()->findByPk($objectId);

        if (empty($Object)) {
            throw new ObjectsException();
        }

        return in_array($Object->collection_id, Collections::getIdsOfCollectionsAllowedToUser($userId));
    }
}
