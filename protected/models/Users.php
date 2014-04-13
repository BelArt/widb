<?php

/**
 * This is the model class for table "{{users}}".
 *
 * The followings are the available columns in table '{{users}}':
 * @property string $id
 * @property string $email
 * @property string $password
 * @property string $surname
 * @property string $name
 * @property string $middlename
 * @property string $initials
 * @property string $position
 * @property string $date_create
 * @property string $date_modify
 * @property string $date_delete
 * @property string $sort
 * @property integer $deleted
 */
class Users extends MyActiveRecord
{
    public $newPassword;
    public $repeatNewPassword;
    public $allowedCollections;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{users}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
            // сначала со специфичной логикой
            array('password', 'validatePassword', 'on' => 'insert, update'),
            array('email', 'unique', 'message' => Yii::t('admin', 'Пользователь с таким E-mail уже зарегистрирован!'), 'on' => 'insert, update'),
            // обязательные
            array(
                'initials, email, role',
                'validators.MyRequiredValidator',
                'on' => 'insert, update'
            ),
            // проверки на формат
            array('sort', 'validators.MyIntegerValidator', 'on' => 'insert, update'),
            array('email', 'email', 'on' => 'insert, update'),
            array('role', 'validateRole', 'on' => 'insert, update'),
            // на длину
            array('surname, name, middlename, initials, position, email', 'length', 'max'=>150, 'on' => 'insert, update'),
            array('password', 'length', 'max'=>64, 'on' => 'insert, update'),
            // и безопасные
		);
	}

    public function validateRole($attribute, $params)
    {
        $roles = array_keys($this->getArrayOfPossibleRoles());

        if (!in_array($this->$attribute, $roles)) {
            $this->addError($attribute, Yii::t('common', 'У поля задано неверное значение!'));
        }
    }

    public function validatePassword($attribute, $params)
    {
        if ($this->newPassword != $this->repeatNewPassword) {
            $this->addError('password', Yii::t('admin', 'Пароли не совпадают!'));
            return;
        }
        if ($this->scenario == 'insert' && MyValidator::valueIsEmpty($this->newPassword)) {
            $this->addError('password', Yii::t('common', 'Поле не заполнено!'));
        }
    }

    protected function beforeSave()
    {
        $this->preparePasswordForSaving();

        return parent::beforeSave();
    }

    protected function afterSave()
    {
        $this->saveUserAllowedCollections();

        parent::afterSave();
    }

    private function preparePasswordForSaving()
    {
        if ($this->scenario == 'insert' || $this->scenario == 'update') {
            if (!empty($this->newPassword)) {
                $this->password = CPasswordHelper::hashPassword($this->newPassword);
            }
        }
    }

    private function saveUserAllowedCollections()
    {
        if ($this->scenario == 'insert' || $this->scenario == 'update') {

            if (empty($this->allowedCollections)) {
                return;
            }

            $records = UserAllowedCollection::model()->findAll(
                array(
                    'select' => 'id',
                    'condition' => 'user_id = :user_id',
                    'params' => array(':user_id' => $this->id)
                )
            );

            if (!empty($records)) {
                foreach ($records as $Record) {
                    $Record->deleteUserAllowedCollection();
                }
            }

            foreach ($this->allowedCollections as $collectionId) {
                $NewRecord = new UserAllowedCollection();
                $NewRecord->user_id = $this->id;
                $NewRecord->collection_id = $collectionId;
                if (!$NewRecord->save()) {
                    throw new CException(Yii::t('common', 'Произошла ошибка!'));
                }
            }
        }
    }

    /**
	 * @return array relational rules.
	 */
	public function relations()
	{
        return array(
            //'parentCollection' => array(self::HAS_ONE, 'Collections', 'parent_id'),
            'allowedCollections' => array(self::HAS_MANY, 'UserAllowedCollection', 'user_id'),

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
            'surname' => Yii::t('admin', 'Фамилия'),
            'name' => Yii::t('admin', 'Имя'),
            'middlename' => Yii::t('admin', 'Отчество'),
            'initials' => Yii::t('admin', 'ФИО'),
            'position' => Yii::t('admin', 'Должность'),
            'sort' => Yii::t('common', 'Сортировка'),
            'email' => Yii::t('admin', 'E-mail'),
            'password' => Yii::t('admin', 'Пароль'),
            'role' => Yii::t('admin', 'Роль'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getRoleText()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $role = '';
        switch ($this->role) {
            case 'administrator':
                $role = Yii::t('admin', 'Администратор');
                break;
            case 'contentManager':
                $role = Yii::t('admin', 'Контент-менеджер');
                break;
            case 'user':
                $role = Yii::t('admin', 'Пользователь');
                break;
        }

        return $role;
    }

    public function getArrayOfPossibleRoles()
    {
        return array(
            'administrator' => Yii::t('admin', 'Администратор'),
            'contentManager' => Yii::t('admin', 'Контент-менеджер'),
            'user' => Yii::t('admin', 'Пользователь'),
        );
    }

    public function deleteUser()
    {
        if ($this->isNewRecord) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        parent::deleteRecord();
    }

    /**
     * Возвращает массив доступных пользователю коллекций для соответствующего селекта на форме.
     * @return array
     * @throws CException
     */
    public function getIdsOfAllowedCollectionsForFormSelect()
    {
        $result = array();

        if ($this->isNewRecord) {
            return $result;
        }

        $records = UserAllowedCollection::model()->findAll(
            array(
                'select' => 'collection_id',
                'condition' => 'user_id = :user_id',
                'params' => array(':user_id' => $this->id)
            )
        );

        if (!empty($records)) {
            foreach ($records as $Record) {
                $result[] = $Record->collection_id;
            }
        }

        return $result;
    }

}
