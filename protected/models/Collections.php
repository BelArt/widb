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
 * @property integer $temporary
 * @property integer $has_preview
 * @property string $date_create
 * @property string $date_modify
 * @property string $date_delete
 * @property string $sort
 * @property integer $deleted
 * @property string $user_create
 * @property string $user_modify
 * @property string $user_delete
 * @property integer $temporary_public
 *
 * @todo разобраться с поиском
 */
class Collections extends ActiveRecord
{
    /**
     * @var array массив с моделями-детьми (у кого parent_id = id)
     * Сделан не через геттер\сеттер из-за того, что  иначе возникает
     * indirect modification of overloaded property has no effect
     */
    public $children = array();

    protected $thumbnailBig;
    protected $thumbnailMedium;
    protected $thumbnailSmall;

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
		return array(
            array('temporary_public, parent_id', 'application.components.validators.TempCollectionValidator', 'skipOnError' => true, 'except' => 'delete'),
            array('name, code', 'required', 'except' => 'delete'),
			array('temporary, has_preview, temporary_public', 'boolean', 'except' => 'delete'),
            array('parent_id, sort', 'application.components.validators.EmptyOrPositiveIntegerValidator', 'skipOnError' => true, 'except' => 'delete'),
            array('name, code', 'length', 'max' => 150, 'except' => 'delete'),
            array('code', 'application.components.validators.CodeValidator', 'skipOnError' => true, 'except' => 'delete'),

            array('description', 'safe'),


			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			//array('id, parent_id, name, description, code, temporary, has_preview, date_create, date_modify, date_delete, sort, deleted', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
        return array(
            'parentCollection' => array(self::BELONGS_TO, 'Collections', 'parent_id'),
            'childCollections' => array(self::HAS_MANY, 'Collections', 'parent_id'),
            'userAllowedCollection' => array(self::HAS_MANY, 'UserAllowedCollection', 'collection_id'),

            'objects' => array(self::HAS_MANY, 'Objects', 'collection_id'),

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
			'parent_id' => Yii::t('collections', 'Родительская коллекция'),
			'name' => Yii::t('common', 'Название'),
			'description' => Yii::t('common', 'Описание'),
			'code' => Yii::t('common', 'Код'),
			'temporary' => Yii::t('collections', 'Временная коллекция'),
			'has_preview' => Yii::t('common', 'Есть превью'),
			'sort' => Yii::t('common', 'Сортировка'),
            'temporary_public' => Yii::t('collections', 'Открытая временная коллекция'),
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
		$criteria->compare('parent_id',$this->parent_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('code',$this->code,true);
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
	}*/

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
    private function setThumbnails()
    {
        $this->thumbnailBig = PreviewHelper::getBigThumbnailForCollection($this);
        $this->thumbnailMedium = PreviewHelper::getMediumThumbnailForCollection($this);
        $this->thumbnailSmall = PreviewHelper::getSmallThumbnailForCollection($this);
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
            $itemUrl = $ChildCollection->temporary ? Yii::app()->urlManager->createTempCollectionUrl($ChildCollection) : Yii::app()->urlManager->createNormalCollectionUrl($ChildCollection);
            $result[] = array(
                'text' => '<a '.($ChildCollection->temporary ? '' : 'class="_normalCollectionLink"').' href="'.$itemUrl.'">'.$ChildCollection->name.'</a>',
                'children' => self::getChildrenStructure($ChildCollection),
            );
        }

        return $result;
    }

    public function getThumbnailBig()
    {
        if ($this->isNewRecord) {
            throw new CollectionsException();
        }

        return $this->thumbnailBig;
    }

    public function getThumbnailMedium()
    {
        if ($this->isNewRecord) {
            throw new CollectionsException();
        }

        return $this->thumbnailMedium;
    }

    public function getThumbnailSmall()
    {
        if ($this->isNewRecord) {
            throw new CollectionsException();
        }

        return $this->thumbnailSmall;
    }

    /**
     * Проверяет, доступна ли текущая коллекция пользователю.
     * @param integer $userId айдишник пользователя
     * @throws CException
     * @return bool
     */
    public function isAllowedToUser($userId)
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('collections', 'Метод "{method}" не может вызываться для вновь создаваемой коллекции', array('{method}' => __METHOD__)));
        }

        return self::getCollectionIsAllowedToUser($this->id, $userId);
    }

    /**
     * Проверяет, доступна ли коллекция пользователю.
     * @param integer $collectionId айди коллекции
     * @param integer $userId айди пользователя
     * @return bool
     */
    public static function getCollectionIsAllowedToUser($collectionId, $userId)
    {
        return in_array($collectionId, self::getIdsOfCollectionsAllowedToUser($userId));
    }

    /**
     * Получает массив вида id => Название коллекции для
     * построения селекта Родительская коллекция на форме
     * создания\редактирования обычной коллекции.
     * @return array
     */
    public function getArrayOfPossibleNormalParentCollections()
    {
        $Criteria = new CDbCriteria;
        $Criteria->select = 'id, name';
        $Criteria->addCondition('temporary = 0');

        // если редактируем - то не показываем саму коллекцию и ее дочерние коллекции
        if (!$this->isNewRecord) {
            $Criteria->addNotInCondition('id', self::getDescendantCollectionsIds($this->id));
        }

        $collections = self::model()->findAll($Criteria);

        $result = array(
            0 => Yii::t('collections', 'Нет родительской коллекции')
        );

        if (empty($collections)) {
            return $result;
        }

        foreach ($collections as $Collection) {
            $result[$Collection->id] = $Collection->name;
        }

        return $result;
    }

    /**
     * Получает массив вида id => Название коллекции для
     * построения селекта Родительская коллекция на форме
     * создания\редактирования временной коллекции
     * @return array
     */
    /*public function getArrayOfPossibleTempParentCollections()
    {
        $Criteria = new CDbCriteria;
        $Criteria->select = 'id, name';
        $Criteria->addCondition('temporary = 1');

        // если редактируем - то не показываем саму коллекцию и ее дочерние коллекции
        if (!$this->isNewRecord) {
            $Criteria->addNotInCondition('id', self::getDescendantCollectionsIds($this->id));
        }

        $collections = self::model()->findAll($Criteria);

        $result = array(
            0 => Yii::t('collections', 'Нет родительской коллекции')
        );

        if (empty($collections)) {
            return $result;
        }

        foreach ($collections as $Collection) {
            $result[$Collection->id] = $Collection->name;
        }

        return $result;
    }*/

    /**
     * Возвращает массив с айди всех коллекций, доступных пользователю
     * @param integer $userId айди пользователя
     * @return array массив с айди коллекций, доступных пользователю
     */
    public static function getIdsOfCollectionsAllowedToUser($userId)
    {
        $result = array();

        $User = Users::model()->findByPk(
            $userId,
            array(
                'select' => 'role'
            )
        );

        $collections = self::model()->findAll(
            array(
                'select' => 'id',
            )
        );

        // если пользователь - админ или контент-менеджер, то ему доступны все коллекции
        if ($User->role == 'administrator' || $User->role == 'contentManager') {
            foreach ($collections as $Collection) {
                $result[] = $Collection->id;
            }
            return $result;
        }

        $records = UserAllowedCollection::model()->findAll(
            array(
                'select' => 'collection_id',
                'condition' => 'user_id = :user_id',
                'params' => array(':user_id' => $userId)
            )
        );

        // если есть перечень доступных пользователю Коллекций, то ему доступны только эти коллекции и их потомки
        // притом у временных коллекций нет потомков
        if (!empty($records)) {
            foreach ($records as $Record) {
                $result = array_unique(array_merge($result, self::getDescendantCollectionsIds($Record->collection_id)));
            }
            return $result;
        }

        // перечень доступных коллекций пуст - значит, доступны все обычные коллекции, публичные временные и те временные, которые он сам создал
        $collections = self::model()->findAll(
            array(
                'select' => 'id',
                'condition' => 'temporary = 0 OR (temporary = 1 AND temporary_public = 1) OR (temporary = 1 AND user_create = :user_id)',
                'params' => array(':user_id' => $userId)
            )
        );

        foreach ($collections as $Collection) {
            $result[] = $Collection->id;
        }

        return $result;
    }

    /**
     * Возвращает массив с айди обычных коллекций, доступных пользователю
     * @param integer $userId айди пользователя
     * @return array массив с айди коллекций, доступных пользователю
     */
    public static function getIdsOfNormalCollectionsAllowedToUser($userId)
    {
        $result = array();

        $User = Users::model()->findByPk(
            $userId,
            array(
                'select' => 'role'
            )
        );

        // все обычные коллекции
        $collections = self::model()->findAll(
            array(
                'select' => 'id',
                'condition' => 'temporary = 0'
            )
        );

        // перечень доступных пользователю обычных коллекций
        $records = UserAllowedCollection::model()->findAll(
            array(
                'select' => 'collection_id',
                'condition' => 'user_id = :user_id AND collection.temporary = 0',
                'params' => array(':user_id' => $userId),
                'with' => array('collection')
            )
        );

        // если пользователь - админ или контент-менеджер, или перечень доступных коллекций пуст - ему доступны все обычные коллекции
        if ($User->role == 'administrator' || $User->role == 'contentManager' || empty($records)) {

            foreach ($collections as $Collection) {
                $result[] = $Collection->id;
            }

        } else {
            // если есть перечень доступных пользователю Коллекций, то ему доступны только эти коллекции и их потомки
            foreach ($records as $Record) {
                $result = array_unique(array_merge($result, self::getDescendantCollectionsIds($Record->collection_id)));
            }
        }

        return $result;
    }

    /**
     * Возвращает массив с AR временных коллекций, доступных пользователю
     * @param integer $userId айди пользователя
     * @return array массив с AR временных коллекций, доступных пользователю или пустой, если таких нет
     */
    public static function getTempCollectionsAllowedToUser($userId)
    {
        $ids = self::getIdsOfTempCollectionsAllowedToUser($userId);

        if (empty($ids)) {
            return $ids;
        }

        $Criteria = new CDbCriteria();
        $Criteria->addInCondition('id', $ids);
        $Criteria->addCondition('temporary = 1');

        return self::model()->findAll($Criteria);
    }

    /**
     * Возвращает массив с айди временных коллекций, доступных пользователю
     * @param integer $userId айди пользователя
     * @return array массив с айди коллекций, доступных пользователю
     * @throws CollectionsException
     */
    public static function getIdsOfTempCollectionsAllowedToUser($userId)
    {
        $result = array();

        $User = Users::model()->findByPk(
            $userId,
            array(
                'select' => 'role'
            )
        );

        if (empty($User)) {
            throw new CollectionsException();
        }

        // все временные коллекции
        $collections = self::model()->findAll(
            array(
                'select' => 'id',
                'condition' => 'temporary = 1'
            )
        );

        // если пользователь - админ или контент-менеджер - ему доступны все временные коллекции
        if ($User->role == 'administrator' || $User->role == 'contentManager') {
            foreach ($collections as $Collection) {
                $result[] = $Collection->id;
            }
            return $result;
        }

        // перечень доступных пользователю временных коллекций
        $records = UserAllowedCollection::model()->findAll(
            array(
                'select' => 'collection_id',
                'condition' => 'user_id = :user_id AND collection.temporary = 1',
                'params' => array(':user_id' => $userId),
                'with' => array('collection')
            )
        );

        // если есть перечень доступных пользователю временных Коллекций, то ему доступны только эти временные коллекции и их потомки
        // хотя у временных коллекций нет потомков ...
        if (!empty($records)) {
            foreach ($records as $Record) {
                $result = array_unique(array_merge($result, self::getDescendantCollectionsIds($Record->collection_id)));
            }
            return $result;
        }

        // перечень доступных временных коллекций пуст - значит, доступны все публичные временные и те временные, которые он сам создал
        $collections = self::model()->findAll(
            array(
                'select' => 'id',
                'condition' => '(temporary = 1 AND temporary_public = 1) OR (temporary = 1 AND user_create = :user_id)',
                'params' => array(':user_id' => $userId)
            )
        );

        foreach ($collections as $Collection) {
            $result[] = $Collection->id;
        }

        return $result;
    }

    /**
     * Возвращает массив со структурой дерева коллекций, доступных пользователю, для передачи в виджет CTreeView
     * @return array
     */
    public static function getTree()
    {
        $idsOfCollectionsAllowedToUser = self::getIdsOfCollectionsAllowedToUser(Yii::app()->user->id);

        $Criteria = self::getAllowedCollectionsCriteria(Yii::app()->user->id);

        $allowedCollections = Collections::model()->findAll($Criteria);

        $rootCollections = array();

        foreach ($allowedCollections as $Collection) {
            foreach ($allowedCollections as $CollectionChild) {
                if ($CollectionChild->parent_id == $Collection->id) {
                    $Collection->children[] = $CollectionChild;
                }
            }
            if (empty($Collection->parent_id) || !in_array($Collection->parent_id, $idsOfCollectionsAllowedToUser)) {
                $rootCollections[] = $Collection;
            }
        }

        $result = array();

        foreach ($rootCollections as $Collection) {
            $itemUrl = $Collection->temporary ? Yii::app()->urlManager->createTempCollectionUrl($Collection) : Yii::app()->urlManager->createNormalCollectionUrl($Collection);
            $result[] = array(
                'text' => '<a '.($Collection->temporary ? '' : 'class="_normalCollectionLink"').' href="'.$itemUrl.'">'.$Collection->name.'</a>',
                'children' => self::getChildrenStructure($Collection),
            );
        }

        return $result;
    }

    /**
     * Возвращает массив со структурой дерева обычных коллекций, доступных пользователю, для передачи в виджет CTreeView
     * @return array
     */
    public static function getTreeNormal()
    {
        $idsOfCollectionsAllowedToUser = self::getIdsOfNormalCollectionsAllowedToUser(Yii::app()->user->id);

        $Criteria = self::getAllowedNormalCollectionsCriteria(Yii::app()->user->id);

        $allowedCollections = Collections::model()->findAll($Criteria);

        $rootCollections = array();

        foreach ($allowedCollections as $Collection) {
            foreach ($allowedCollections as $CollectionChild) {
                if ($CollectionChild->parent_id == $Collection->id) {
                    $Collection->children[] = $CollectionChild;
                }
            }
            if (empty($Collection->parent_id) || !in_array($Collection->parent_id, $idsOfCollectionsAllowedToUser)) {
                $rootCollections[] = $Collection;
            }
        }

        $result = array();

        foreach ($rootCollections as $Collection) {
            $itemUrl = Yii::app()->urlManager->createNormalCollectionUrl($Collection);
            $result[] = array(
                'text' => '<a class="_normalCollectionLink" href="'.$itemUrl.'">'.$Collection->name.'</a>',
                'children' => self::getChildrenStructure($Collection),
            );
        }

        return $result;
    }

    /**
     * Возвращает массив со структурой дерева временных коллекций, доступных пользователю, для передачи в виджет CTreeView
     * @return array
     */
    public static function getTreeTemp()
    {
        $idsOfCollectionsAllowedToUser = self::getIdsOfTempCollectionsAllowedToUser(Yii::app()->user->id);

        $Criteria = self::getAllowedTempCollectionsCriteria(Yii::app()->user->id);

        $allowedCollections = Collections::model()->findAll($Criteria);

        $rootCollections = array();

        foreach ($allowedCollections as $Collection) {
            foreach ($allowedCollections as $CollectionChild) {
                if ($CollectionChild->parent_id == $Collection->id) {
                    $Collection->children[] = $CollectionChild;
                }
            }
            if (empty($Collection->parent_id) || !in_array($Collection->parent_id, $idsOfCollectionsAllowedToUser)) {
                $rootCollections[] = $Collection;
            }
        }

        $result = array();

        foreach ($rootCollections as $Collection) {
            $itemUrl = Yii::app()->urlManager->createTempCollectionUrl($Collection);
            $result[] = array(
                'text' => '<a href="'.$itemUrl.'">'.$Collection->name.'</a>',
                'children' => self::getChildrenStructure($Collection),
            );
        }

        return $result;
    }

    /**
     * Возвращает CDbCriteria для получения всех доступных пользователю коллекций
     * @param integer $userId айди пользователя
     * @return CDbCriteria для подстановки в DataProvider
     */
    public static function getAllowedCollectionsCriteria($userId)
    {
        $Criteria = new CDbCriteria();
        $Criteria->addInCondition('id', self::getIdsOfCollectionsAllowedToUser($userId));

        return $Criteria;
    }

    /**
     * Возвращает CDbCriteria для получения всех доступных пользователю обычных коллекций
     * @param integer $userId айди пользователя
     * @return CDbCriteria для подстановки в DataProvider
     */
    public static function getAllowedNormalCollectionsCriteria($userId)
    {
        $Criteria = new CDbCriteria();
        $Criteria->addInCondition('id', self::getIdsOfNormalCollectionsAllowedToUser($userId));

        return $Criteria;
    }

    /**
     * Возвращает CDbCriteria для получения всех доступных пользователю временных коллекций
     * @param integer $userId айди пользователя
     * @return CDbCriteria для подстановки в DataProvider
     */
    public static function getAllowedTempCollectionsCriteria($userId)
    {
        $Criteria = new CDbCriteria();
        $Criteria->addInCondition('id', self::getIdsOfTempCollectionsAllowedToUser($userId));

        return $Criteria;
    }

    /**
     * Возвращает массив с айди коллекций-потомков. Сама коллекция включается с массив.
     * @param integer $collectionId айди коллекции
     * @return array массив с айди коллекций-потомков
     */
    public static function getDescendantCollectionsIds($collectionId)
    {
        $result = array($collectionId);

        $Criteria = new CDbCriteria;
        $Criteria->select = 'id';
        $Criteria->addCondition('parent_id = :parent_id');
        $Criteria->params = array(':parent_id' => $collectionId);

        $collections = self::model()->findAll($Criteria);

        foreach ($collections as $Collection) {
            $result = array_unique(array_merge($result, self::getDescendantCollectionsIds($Collection->id)));
        }

        return $result;
    }

    /**
     * Проверяет, можно ли удалить коллекцию
     * @throws CollectionsException
     * @return bool
     */
    public function isReadyToBeDeleted()
    {
        if ($this->isNewRecord) {
            throw new CollectionsException();
        }

        if ($this->temporary) {
            return true;
        }

        $Criteria = new CDbCriteria;
        $Criteria->select = 'id';
        $Criteria->addCondition('parent_id = :parent_id');
        $Criteria->params = array(':parent_id' => $this->id);

        $childCollections = self::model()->findAll($Criteria);

        if (!empty($childCollections)) {
            return false;
        }

        $Criteria = new CDbCriteria;
        $Criteria->select = 'id';
        $Criteria->addCondition('collection_id = :collection_id');
        $Criteria->params = array(':collection_id' => $this->id);

        $objects = Objects::model()->findAll($Criteria);

        if (!empty($objects)) {
            return false;
        }

        return true;
    }

    /**
     * Удаляет обычную коллекцию
     * @throws CollectionsException|Exception
     */
    public function deleteNormalCollection()
    {
        if ($this->isNewRecord || $this->temporary) {
            throw new CollectionsException();
        }

        $Transaction = Yii::app()->db->beginTransaction();

        try {

            // удаляем коллекцию
            $this->deleteRecord();

            // удаляем коллекцию из списка доступных коллекций у всех пользователей
            $Criteria = new CDbCriteria;
            $Criteria->select = 'id';
            $Criteria->addCondition('collection_id = :collection_id');
            $Criteria->params = array(':collection_id' => $this->id);

            $records = UserAllowedCollection::model()->findAll($Criteria);

            foreach ($records as $Record) {
                $Record->deleteUserAllowedCollection();
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
     * Удаляет временную коллекцию
     * @throws CollectionsException
     */
    public function deleteTempCollection()
    {
        if ($this->isNewRecord || !$this->temporary) {
            throw new CollectionsException();
        }

        $Transaction = Yii::app()->db->beginTransaction();

        try {

            // удаляем коллекцию
            $this->deleteRecord();

            // удаляем коллекцию из списка доступных коллекций у всех пользователей
            $Criteria = new CDbCriteria;
            $Criteria->select = 'id';
            $Criteria->addCondition('collection_id = :collection_id');
            $Criteria->params = array(':collection_id' => $this->id);

            $records = UserAllowedCollection::model()->findAll($Criteria);

            foreach ($records as $Record) {
                $Record->deleteRecord();
            }

            // удаляем объекты из этой временной коллекции
            $Criteria = new CDbCriteria;
            $Criteria->select = 'id';
            $Criteria->addCondition('collection_id = :collection_id');
            $Criteria->params = array(':collection_id' => $this->id);

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
     * Возвращает масиив AR всех обычных коллекций кроме той, чей айди передам параметром
     * @param $collectionId айди коллекции, которую надо исключить
     * @return CActiveRecord[]
     */
    public static function getAllNormalCollectionsExcept($collectionId)
    {
        $Criteria = new CDbCriteria();
        $Criteria->addCondition('id <> :collection_id');
        $Criteria->addCondition('temporary = 0');
        $Criteria->params = array(':collection_id' => $collectionId);

        return self::model()->findAll($Criteria);
    }

    /**
     * Возвращает массив со всеми коллекциями для построения селекта на формах
     * @return array
     */
    public static function getAllCollectionsArrayForFormSelect()
    {
        $collections = Collections::model()->findAll();

        $result = array();

        foreach ($collections as $Collection) {
            $result[$Collection->id] = $Collection->name;
        }

        return $result;
    }


}
