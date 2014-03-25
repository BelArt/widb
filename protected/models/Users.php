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
class Users extends ActiveRecord
{
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
            // сначала обязательные
            array(
                'initials, email, role',
                'validators.MyRequiredValidator',
                'except' => 'delete'
            ),
            // потом проверки на формат
            array('sort', 'validators.IntegerValidator', 'skipOnError' => true, 'except' => 'delete'),
            array('email', 'email', 'skipOnError' => true, 'except' => 'delete'),
            array('role', 'validatorRole', 'skipOnError' => true, 'except' => 'delete'),
            // потом отдельно на длину
            array('surname, name, middlename, initials, position, email', 'length', 'max'=>150, 'except' => 'delete', 'skipOnError' => true),
            array('password', 'length', 'max'=>64, 'except' => 'delete', 'skipOnError' => true),
            // и безопасные
		);
	}

    public function validatorRole($attribute, $params = array())
    {
        $roles = array_keys($this->getArrayOfPossibleRoles());

        if (!in_array($this->$attribute, $roles)) {
            $this->addError($attribute, Yii::t('common', 'У поля задано неверное значение!'));
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
}
