<?php

/**
 * Контроллер действий с пользователями
 */
class UsersController extends Controller
{
    private $_user;

	public function filters()
	{
		return array(
			'accessControl',
            'forActionUpdate + update',
            'forActionDelete + delete',
		);
	}

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('view'),
                'roles' => array('oUsersView'),
            ),
            array('allow',
                'actions' => array('update'),
                'roles' => array('oUserEdit'),
            ),
            array('allow',
                'actions' => array('create'),
                'roles' => array('oUserCreate'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('oUserDelete'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionView()
    {
        $UsersDataProvider = new CActiveDataProvider('Users', array(
            'pagination' => array(
                'pageVar' => 'p',
            ),
        ));

        $this->setPageParamsForActionView();

        $this->render('view', array(
            'UsersDataProvider' => $UsersDataProvider,
        ));
    }

    private function setPageParamsForActionView()
    {
        $this->pageTitle = array(Yii::t('admin', 'Пользователи'));
        $this->breadcrumbs = array(Yii::t('admin', 'Пользователи'));
        //$this->pageName = Yii::t('admin', 'Справочники');
        $pageMenu = array();
        if (Yii::app()->user->checkAccess('oUserCreate')) {
            $pageMenu[] = array(
                'label' => Yii::t('admin', 'Создать нового пользователя'),
                'url' => $this->createUrl('users/create'),
                'iconType' => 'create_author'
            );
        }
        $this->pageMenu = $pageMenu;
    }

    public function actionUpdate($id)
    {
        $User = $this->loadUser($id);

        if(isset($_POST['Users']))
        {
            $User->attributes = $_POST['Users'];
            $User->newPassword = $_POST['Users']['newPassword'];
            $User->repeatNewPassword = $_POST['Users']['repeatNewPassword'];

            if ($User->save()) {
                $this->redirect(array('view'));
            }
        }

        $this->setPageParamsForActionUpdate($User);

        $this->render('update', array(
            'User' => $User,
        ));
    }

    public function filterForActionUpdate($filterChain)
    {
        $User = $this->loadUser(Yii::app()->request->getQuery('id'));

        if (empty($User)) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

    public function loadUser($id)
    {
        if (empty($id)) {
            return null;
        }

        if (empty($this->_user)) {
            $this->_user = Users::model()->findByPk($id);

            if (empty($this->_user)) {
                throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
            }
        }

        return $this->_user;
    }

    public function setPageParamsForActionUpdate($User)
    {
        $pageTitle = array(Yii::t('admin', 'Пользователи'), $User->initials, Yii::t('common', 'Редактирование'));
        $breadcrumbs = array(
            Yii::t('admin', 'Пользователи') => array('users/view'),
            $User->initials,
            Yii::t('common', 'Редактирование')
        );
        $pageName = $User->initials;

        $this->pageTitle = $pageTitle;
        $this->breadcrumbs = $breadcrumbs;
        $this->pageName = $pageName;
    }

    public function actionCreate()
    {
        $User = new Users();

        if(isset($_POST['Users']))
        {
            $User->attributes = $_POST['Users'];
            $User->newPassword = $_POST['Users']['newPassword'];
            $User->repeatNewPassword = $_POST['Users']['repeatNewPassword'];

            if ($User->save()) {
                $this->redirect(array('view'));
            }
        }

        $this->setPageParamsForActionCreate();

        $this->render('create', array(
            'User' => $User,
        ));
    }

    private function setPageParamsForActionCreate()
    {
        $pageTitle = array(Yii::t('admin', 'Пользователи'), Yii::t('admin', 'Создание пользователя'));
        $breadcrumbs = array(
            Yii::t('admin', 'Пользователи') => array('users/view'),
            Yii::t('admin', 'Создание пользователя'),
        );
        $pageName = Yii::t('admin', 'Создание пользователя');

        $this->pageTitle = $pageTitle;
        $this->breadcrumbs = $breadcrumbs;
        $this->pageName = $pageName;
    }

    public function actionDelete($id)
    {
        $User = $this->loadUser($id);

        DeleteHelper::deleteUser($User);
        Yii::app()->user->setFlash('success', Yii::t('admin', 'Пользователь удален'));

        $this->redirect(array('users/view'));
    }

    public function filterForActionDelete($filterChain)
    {
        $User = $this->loadUser(Yii::app()->request->getQuery('id'));

        if (empty($User)) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

}
