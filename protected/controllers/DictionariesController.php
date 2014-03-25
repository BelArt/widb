<?php

class DictionariesController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('view'),
                'roles' => array('oDictionariesView'),
            ),
            array('allow',
                'actions' => array('update'),
                'roles' => array('oDictionaryRecordEdit'),
            ),
            array('allow',
                'actions' => array('create'),
                'roles' => array('oDictionaryRecordCreate'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('oDictionaryRecordDelete'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Страница просмотра справочников
     */
    public function actionView()
    {
        $AuthorsDataProvider = new CActiveDataProvider('Authors', array(
            'pagination' => array(
                'pageVar' => 'p',
            ),
        ));
        $ObjectTypesDataProvider = new CActiveDataProvider('ObjectTypes', array(
            'pagination' => array(
                'pageVar' => 'p',
            ),
        ));
        $PhotoTypesDataProvider = new CActiveDataProvider('PhotoTypes', array(
            'pagination' => array(
                'pageVar' => 'p',
            ),
        ));

        $this->setPageParamsForActionView();

        $this->render('view', array(
            'AuthorsDataProvider' => $AuthorsDataProvider,
            'ObjectTypesDataProvider' => $ObjectTypesDataProvider,
            'PhotoTypesDataProvider' => $PhotoTypesDataProvider
        ));
    }

    /**
     * Устанавливает параметры страницы просмотра объекта (тайтл, хлебные крошки, заголовок, меню)
     * @throws DictionariesControllerException
     */
    private function setPageParamsForActionView()
    {
        $this->pageTitle = array(Yii::t('admin', 'Справочники'));
        $this->breadcrumbs = array(Yii::t('admin', 'Справочники'));
        //$this->pageName = Yii::t('admin', 'Справочники');
        $pageMenu = array();
        if (Yii::app()->user->checkAccess('oDictionaryRecordCreate')) {
            $pageMenu[] = array(
                'label' => Yii::t('admin', 'Создать нового автора'),
                'url' => $this->createUrl('dictionaries/create', array('type' => 'authors')),
                'iconType' => 'create_author'
            );
            $pageMenu[] = array(
                'label' => Yii::t('admin', 'Создать новый тип объекта'),
                'url' => $this->createUrl('dictionaries/create', array('type' => 'object_types')),
                'iconType' => 'create_object_type'
            );
            $pageMenu[] = array(
                'label' => Yii::t('admin', 'Создать новый тип съемки'),
                'url' => $this->createUrl('dictionaries/create' , array('type' => 'photo_types')),
                'iconType' => 'create_photo_type'
            );
        }
        $this->pageMenu = $pageMenu;
    }

    /**
     * Страница редактирования записи в справочнике
     * @param string $id айди записи в справочнике
     * @param string $type тип справочника, см. {@link getDictionaryRecordModel()}
     */
    public function actionUpdate($id, $type)
    {
        $Model = $this->getDictionaryRecordModel($id, $type);
        $modelName = get_class($Model);
        if(isset($_POST[$modelName]))
        {
            $Model->attributes = $_POST[$modelName];
            if ($Model->save()) {
                $this->redirect(array('view'));
            }
        }
        $this->setPageParamsForActionUpdate($Model);
        $viewName = $this->getViewNameForActionUpdate($Model);
        $this->render($viewName, array(
            'Model' => $Model,
        ));
    }

    /**
     * Возвращает модель записи в соответствующем справочнике
     * @param string $id айди записи в справочнике
     * @param string $type тип справочника
     * @return ActiveRecord модель записи в справочнике
     * @throws DictionariesControllerException
     */
    private function getDictionaryRecordModel($id, $type)
    {
        $Model = null;
        switch ($type) {
            case 'authors':
                $Model = Authors::model()->findByPk($id);
                break;
            case 'object_types':
                $Model = ObjectTypes::model()->findByPk($id);
                break;
            case 'photo_types':
                $Model = PhotoTypes::model()->findByPk($id);
                break;
            default:
                throw new DictionariesControllerException();
        }

        if (empty($Model)) {
            throw new DictionariesControllerException();
        }

        return $Model;
    }

    /**
     * Устанавливает параметры страницы редактирования записи в справочнике (тайтл, хлебные крошки, заголовок)
     * @param ActiveRecord $Model модель записи в справочнике
     * @throws DictionariesControllerException
     */
    private function setPageParamsForActionUpdate($Model)
    {
        switch (get_class($Model)) {
            case 'Authors':
                $this->setPageParamsForActionUpdateForAuthors($Model);
                break;
            case 'ObjectTypes':
                $this->setPageParamsForActionUpdateForObjectTypes($Model);
                break;
            case 'PhotoTypes':
                $this->setPageParamsForActionUpdateForPhotoTypes($Model);
                break;
            default:
                throw new DictionariesControllerException();
        }
    }

    private function setPageParamsForActionUpdateForAuthors($Model)
    {
        $pageTitle = array(Yii::t('admin', 'Справочники'), Yii::t('admin', 'Авторы'), $Model->initials, Yii::t('common', 'Редактирование'));
        $breadcrumbs = array(
            Yii::t('admin', 'Справочники') => array('dictionaries/view'),
            Yii::t('admin', 'Авторы'),
            $Model->initials,
            Yii::t('common', 'Редактирование')
        );
        $pageName = $Model->initials;

        $this->pageTitle = $pageTitle;
        $this->breadcrumbs = $breadcrumbs;
        $this->pageName = $pageName;
    }

    private function setPageParamsForActionUpdateForObjectTypes($Model)
    {
        $pageTitle = array(Yii::t('admin', 'Справочники'), Yii::t('admin', 'Типы объектов'), $Model->name, Yii::t('common', 'Редактирование'));
        $breadcrumbs = array(
            Yii::t('admin', 'Справочники') => array('dictionaries/view'),
            Yii::t('admin', 'Типы объектов'),
            $Model->name,
            Yii::t('common', 'Редактирование')
        );
        $pageName = $Model->name;

        $this->pageTitle = $pageTitle;
        $this->breadcrumbs = $breadcrumbs;
        $this->pageName = $pageName;
    }

    private function setPageParamsForActionUpdateForPhotoTypes($Model)
    {
        $pageTitle = array(Yii::t('admin', 'Справочники'), Yii::t('admin', 'Типы съемки'), $Model->name, Yii::t('common', 'Редактирование'));
        $breadcrumbs = array(
            Yii::t('admin', 'Справочники') => array('dictionaries/view'),
            Yii::t('admin', 'Типы съемки'),
            $Model->name,
            Yii::t('common', 'Редактирование')
        );
        $pageName = $Model->name;

        $this->pageTitle = $pageTitle;
        $this->breadcrumbs = $breadcrumbs;
        $this->pageName = $pageName;
    }

    private function getViewNameForActionUpdate($Model)
    {
        switch (get_class($Model)) {
            case 'Authors':
                return 'updateAuthor';
                break;
            case 'ObjectTypes':
                return 'updateObjectType';
                break;
            case 'PhotoTypes':
                return 'updatePhotoType';
                break;
            default:
                throw new DictionariesControllerException();
        }
    }

    public function actionCreate($type)
    {
        $Model = $this->getDictionaryModel($type);
        $modelName = get_class($Model);
        if(isset($_POST[$modelName]))
        {
            $Model->attributes = $_POST[$modelName];
            if ($Model->save()) {
                $this->redirect(array('view'));
            }
        }
        $this->setPageParamsForActionCreate($Model);
        $viewName = $this->getViewNameForActionCreate($Model);
        $this->render($viewName, array(
            'Model' => $Model,
        ));
    }

    private function getDictionaryModel($type)
    {
        $Model = null;
        switch ($type) {
            case 'authors':
                $Model = new Authors();
                break;
            case 'object_types':
                $Model = new ObjectTypes;
                break;
            case 'photo_types':
                $Model = new PhotoTypes;
                break;
            default:
                throw new DictionariesControllerException();
        }
        return $Model;
    }

    private function setPageParamsForActionCreate($Model)
    {
        switch (get_class($Model)) {
            case 'Authors':
                $this->setPageParamsForActionCreateForAuthors($Model);
                break;
            case 'ObjectTypes':
                $this->setPageParamsForActionCreateForObjectTypes($Model);
                break;
            case 'PhotoTypes':
                $this->setPageParamsForActionCreateForPhotoTypes($Model);
                break;
            default:
                throw new DictionariesControllerException();
        }
    }

    private function setPageParamsForActionCreateForAuthors($Model)
    {
        $pageTitle = array(Yii::t('admin', 'Справочники'), Yii::t('admin', 'Создание автора'));
        $breadcrumbs = array(
            Yii::t('admin', 'Справочники') => array('dictionaries/view'),
            Yii::t('admin', 'Создание автора'),
        );
        $pageName = Yii::t('admin', 'Создание автора');

        $this->pageTitle = $pageTitle;
        $this->breadcrumbs = $breadcrumbs;
        $this->pageName = $pageName;
    }

    private function setPageParamsForActionCreateForObjectTypes($Model)
    {
        $pageTitle = array(Yii::t('admin', 'Справочники'), Yii::t('admin', 'Создание типа объекта'));
        $breadcrumbs = array(
            Yii::t('admin', 'Справочники') => array('dictionaries/view'),
            Yii::t('admin', 'Создание типа объекта'),
        );
        $pageName = Yii::t('admin', 'Создание типа объекта');

        $this->pageTitle = $pageTitle;
        $this->breadcrumbs = $breadcrumbs;
        $this->pageName = $pageName;
    }

    private function setPageParamsForActionCreateForPhotoTypes($Model)
    {
        $pageTitle = array(Yii::t('admin', 'Справочники'), Yii::t('admin', 'Создание типа съемки'));
        $breadcrumbs = array(
            Yii::t('admin', 'Справочники') => array('dictionaries/view'),
            Yii::t('admin', 'Создание типа съемки'),
        );
        $pageName = Yii::t('admin', 'Создание типа съемки');

        $this->pageTitle = $pageTitle;
        $this->breadcrumbs = $breadcrumbs;
        $this->pageName = $pageName;
    }

    private function getViewNameForActionCreate($Model)
    {
        switch (get_class($Model)) {
            case 'Authors':
                return 'createAuthor';
                break;
            case 'ObjectTypes':
                return 'createObjectType';
                break;
            case 'PhotoTypes':
                return 'createPhotoType';
                break;
            default:
                throw new DictionariesControllerException();
        }
    }

    /**
     * Удаляет запись из справочника
     * @param string $id айди записи
     * @param string $type тип справочника, см. {@link getDictionaryRecordModel()}
     * @throws DictionariesControllerException
     */
    public function actionDelete($id, $type)
    {
        $Model = $this->getDictionaryRecordModel($id, $type);
        try {
            if (DeleteHelper::deleteDictionaryRecord($Model)) {
                Yii::app()->user->setFlash('success', Yii::t('admin', 'Запись удалена'));
                $this->redirect(array('dictionaries/view'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('admin', 'Запись не удалена, т.к. она где-то используется'));
                $this->redirect(Yii::app()->request->urlReferrer);
            }
        } catch (DeleteHelperException $Exception) {
            throw new DictionariesControllerException($Exception);
        }
    }
}
