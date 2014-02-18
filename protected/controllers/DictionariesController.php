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
        $AuthorsDataProvider = new CActiveDataProvider('Authors');
        $this->setPageParamsForActionView();
        $this->render('view', array(
            'AuthorsDataProvider' => $AuthorsDataProvider,
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
        $this->pageName = Yii::t('admin', 'Справочники');
    }

    /**
     * Страница редактирования записи в справочнике
     * @param string $id айди записи в справочнике
     * @param string $type тип справочника, см. {@link getModelForActionUpdate()}
     */
    public function actionUpdate($id, $type)
    {
        $Model = $this->getModelForActionUpdate($id, $type);
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
    private function getModelForActionUpdate($id, $type)
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

}
