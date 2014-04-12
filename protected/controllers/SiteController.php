<?php

class SiteController extends Controller
{
    public function filters()
    {
        return array(
            'ajaxOnly + ajax',
            'forActionSearch + search',
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'login'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('logout', 'ajax', 'search', 'upload'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
            'upload' => array(
                'class'=>'xupload.actions.XUploadAction',
                'path' => Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.Yii::app()->params['filesFolder'].DIRECTORY_SEPARATOR.Yii::app()->params['tempFilesFolder'],
                'publicPath' => Yii::app()->baseUrl.DIRECTORY_SEPARATOR.Yii::app()->params['filesFolder'].DIRECTORY_SEPARATOR.Yii::app()->params['tempFilesFolder'],
                'secureFileNames' => true,
                'stateVariable' => Yii::app()->params['xuploadStatePreviewsName'], // для красоты
                'subfolderVar' => false, // не надо класть временные файлы в подпапки
                'formClass' => 'MyXUploadForm' // используем собственное расширение
            ),
		);


	}

    public function actionIndex()
    {
        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->urlManager->createCollectionsUrl());
        }

        $this->login('index');
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $this->redirect(array('site/index'));
    }

    /**
     * Показывает форму логина
     * @todo Логин через AJAX
     * @param $mode string режим показа формы, определяет, какой лэйаут применяется
     */
    protected function login($mode = null)
    {
        $model=new LoginForm;

        // if it is ajax validation request
        /*if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }*/

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login()) {
                if (Yii::app()->user->returnUrl) {
                    $this->redirect(Yii::app()->user->returnUrl);
                } else {
                    $this->redirect(Yii::app()->homeUrl);
                }
            }
        }

        // параметры страницы
        //$this->pageTitle = Yii::app()->name;
        $this->pageName = 'Web Images Database';

        // применение других лэйаутов
        switch ($mode) {
            case 'index':
                $this->layout = 'empty';
                break;
        }

        $this->render('login',array(
            'model' => $model,
        ));
    }

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(array('site/index'));
	}

    /**
     * Общая точка входа всех AJAX-запросов
     */
    public function actionAjax()
    {
        $action = Yii::app()->request->getParam('action');
        $params = Yii::app()->request->getParam('params');

        switch ($action) {
            // удаляем не сохраненные превью, которые подгрузил пользователь
            case 'clearUserUploads':
                PreviewHelper::clearUserPreviewsUploads();
                break;
            // удаляем сохраненное превью
            case 'deletePreview':
                $this->deletePreviews($params);
                break;
            // удаляем выбранные объекты из обычной коллекции
            case 'deleteObjects':
                $this->deleteObjectsFromNormalCollection($params);
                break;
            // удаляем выбранные объекты из временной коллекции
            case 'deleteObjectsFromTempCollection':
                $this->deleteObjectsFromTempCollection($params);
                break;
            // удаляем выбранные дочерние коллекции
            case 'deleteChildCollections':
                $this->deleteChildCollections($params);
                break;
            // добавляем объекты во Временную коллекцию
            case 'addObjectsToTempCollection':
                $this->addObjectsToTempCollection($params);
                break;
            // перемещаем объекты в другую коллекцию
            case 'moveObjectsToOtherCollection':
                $this->moveObjectsToOtherCollection($params);
                break;
        }

    }

    /**
     * Удаляет превью
     * @param array $params
     * @throws CException
     */
    protected function deletePreviews(array $params)
    {
        /*
         * всякие проверки
         */
        if (empty($params['type']) || empty($params['id'])) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Model = null;

        switch ($params['type']) {
            case 'collection':
                $Model = Collections::model()->findByPk($params['id']);
                break;
            case 'object':
                $Model = Objects::model()->findByPk($params['id']);
                break;
            case 'image':
                $Model = Images::model()->findByPk($params['id']);
                break;
            default:
                throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        if (empty($Model)) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Model->deletePreviews();

    }

    /**
     * Перемещает объекты в другую коллекцию
     * @param array $params
     * @throws CException
     * @throws Exception
     */
    protected function moveObjectsToOtherCollection(array $params)
    {
        /*
         * всякие проверки
         */

        if (!Yii::app()->user->checkAccess('oChangeObjectsCollection')) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        if (empty($params['objectIds']) || empty($params['collectionId'])) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Collection = Collections::model()->findByPk($params['collectionId']);

        if (empty($Collection) || $Collection->temporary == 1) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Criteria = new CDbCriteria();
        $Criteria->addInCondition('t.id', $params['objectIds']);

        $objects = Objects::model()->findAll($Criteria);

        // если по каким-то айдшникам объектов не удалось найти запись в БД - например, не все айдишники правильные
        if (count($objects) != count($params['objectIds'])) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        /*
         * собственно перемещение
         */

        $Transaction = Yii::app()->db->beginTransaction();

        try {
            foreach ($objects as $Object) {
                $Object->moveToCollection($params['collectionId']);
            }
            $Transaction->commit();
        } catch (Exception $Exception) {
            $Transaction->rollback();
            throw $Exception;
        }

        Yii::app()->user->setFlash(
            'success',
            count($params['objectIds']) == 1
                ? Yii::t('objects', 'Объект перемещен')
                : Yii::t('objects', 'Все объекты перемещены')
        );
    }

    /**
     * Добавляем объекты во Временную коллекцию.
     * Если какие-то объекты из переданных уже есть в этой временной коллекции, то просто их игнорируем
     * @param array $params параметры
     * @throws CException
     * @throws Exception
     */
    protected function addObjectsToTempCollection(array $params)
    {
        /*
         * всякие проверки
         */

        if (empty($params['objectIds']) || empty($params['tempCollectionId'])) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Collection = Collections::model()->findByPk($params['tempCollectionId']);

        if (empty($Collection) || $Collection->temporary == 0) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        if (
            !Yii::app()->user->checkAccess(
                'oObjectToTempCollectionAdd_Collection',
                array(
                    'Collection' => $Collection
                )
            )
        ) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        $Criteria = new CDbCriteria();
        $Criteria->addInCondition('t.id', $params['objectIds']);

        $objects = Objects::model()->with('collection')->findAll($Criteria);

        // если по каким-то айдшникам объектов не удалось найти запись в БД - например, не все айдишники правильные
        if (count($objects) != count($params['objectIds'])) {
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        // проверим все объекты на доступность юзеру
        foreach ($objects as $Object) {

            if (!Yii::app()->user->checkAccess('oObjectToTempCollectionAdd_Object', array(
                    'Collection' => $Object->collection
            ))) {
                throw new CException(Yii::t('common', 'Произошла ошибка!'));
            }
        }

        /*
         * собственно добавление
         */

        $Transaction = Yii::app()->db->beginTransaction();

        try {
            foreach ($objects as $Object) {
                $Object->addToTempCollection($params['tempCollectionId']);
            }
            $Transaction->commit();
        } catch (Exception $Exception) {
            $Transaction->rollback();
            throw $Exception;
        }

        Yii::app()->user->setFlash(
            'success',
            count($params['objectIds']) == 1
                ? Yii::t('objects', 'Объект добавлен в выбранную Временную коллекцию')
                : Yii::t('objects', 'Все объекты добавлены в выбранную Временную коллекцию')
        );
    }

    /**
     * Удаление выбранных дочерних коллекций
     * @param mixed $params параметры
     * @throws CHttpException
     */
    protected function deleteChildCollections($params)
    {
        if (!empty($params['ids'])) {

            if (!Yii::app()->user->checkAccess('oCollectionDelete')) {
                throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
            }

            if (DeleteHelper::deleteNormalCollections($params['ids'])) {
                Yii::app()->user->setFlash(
                    'success',
                    Yii::t('collections', 'Все выбранные дочерние коллекции удалены')
                );
            } else {
                Yii::app()->user->setFlash(
                    'error',
                    Yii::t('collections', 'Не все выбранные дочерние коллекции удалены')
                );
            }
        }
    }

    /**
     * Удаление объектов из временной коллекци
     *
     * @param mixed $params параметры
     * @throws CHttpException
     */
    protected function deleteObjectsFromTempCollection($params)
    {
        if (!empty($params['ids']) && !empty($params['collectionId'])) {

            // проверям права доступа
            foreach ($params['ids'] as $objectId) {
                $Object = Objects::model()->findByPk($objectId);
                $TempCollection = Collections::model()->findByPk($params['collectionId']);

                if (!(
                    Yii::app()->user->checkAccess('oObjectFromTempCollectionDelete_Object', array('Collection' => $Object->collection))
                    && Yii::app()->user->checkAccess('oObjectFromTempCollectionDelete_Collection', array('Collection' => $TempCollection))
                )) {
                    throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
                }
            }

            DeleteHelper::deleteObjectsFromTempCollection($params['ids'], $params['collectionId']);

            Yii::app()->user->setFlash(
                'success',
                Yii::t('objects', 'Все выбранные объекты из временной коллекции удалены!')
            );

        }
    }

    /**
     * Удаление объектов из обычной коллекции
     * @param mixed $params параметры
     * @throws CHttpException
     */
    protected function deleteObjectsFromNormalCollection($params)
    {
        if (!empty($params['ids'])) {

            if (!Yii::app()->user->checkAccess('oObjectDelete')) {
                throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
            }

            if (DeleteHelper::deleteObjectsFromNormalCollection($params['ids'])) {
                Yii::app()->user->setFlash(
                    'success',
                    Yii::t('objects', 'Все выбранные объекты удалены!')
                );
            } else {
                Yii::app()->user->setFlash(
                    'error',
                    Yii::t('objects', 'Некоторые объекты удалить не получилось. У объекта не должно быть относящихся к нему изображений, чтобы его можно было удалить.')
                );
            }
        }
    }

    public function actionSearch()
    {
        $query = Yii::app()->request->getQuery('search');
        $category = Yii::app()->request->getQuery('category');

        if (empty($query)) {
            Yii::app()->request->redirect($_SERVER['HTTP_REFERER']);
        }

        $this->setPageParamsForActionSearch();

        $this->render('search', array(
            'resultsDataProvider' => $this->getResultsDataProviderForActionSearch($query, $category),
        ));
    }

    public function filterForActionSearch($filterChain)
    {
        Yii::import('widgets.search_form.SearchForm');

        if (!in_array(Yii::app()->request->getQuery('category'), SearchForm::getCategoryValues())) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

    private function setPageParamsForActionSearch()
    {
        // параметры страницы
        $this->pageTitle = array(Yii::t('common','Поиск'));
        $this->breadcrumbs = array(Yii::t('common','Поиск'));
    }

    private function getResultsDataProviderForActionSearch($query, $category)
    {
        $result = array();
        switch ($category) {
            case SearchForm::COLLECTIONS_VALUE:
                $result = $this->getCollectionsFoundModels($query);
                break;
            case SearchForm::OBJECTS_VALUE:
                $result = $this->getObjectsFoundModels($query);
                break;
            case SearchForm::IMAGES_VALUE:
                $result = $this->getImagesFoundModels($query);
                break;
            case SearchForm::ALL_VALUE:
                $result = $this->getAllFoundModels($query);
                break;
        }

        return new CArrayDataProvider($result, array(
            'pagination' => array(
                'pageVar' => 'p'
            ),
        ));
    }

    private function getCollectionsFoundModels($query)
    {
        $Criteria = new CDbCriteria;

        $Criteria->compare('name', $query, true, 'OR');
        $Criteria->compare('description', $query, true, 'OR');
        $Criteria->mergeWith(Collections::getAllowedCollectionsCriteria(Yii::app()->user->id));

        return Collections::model()->findAll($Criteria);
    }

    private function getObjectsFoundModels($query)
    {
        $Criteria = new CDbCriteria;

        $Criteria->compare('t.name', $query, true, 'OR');
        $Criteria->compare('t.description', $query, true, 'OR');
        $Criteria->compare('t.inventory_number', $query, true, 'OR');
        $Criteria->compare('t.width', $query, true, 'OR');
        $Criteria->compare('t.height', $query, true, 'OR');
        $Criteria->compare('t.depth', $query, true, 'OR');
        $Criteria->compare('t.department', $query, true, 'OR');
        $Criteria->compare('t.keeper', $query, true, 'OR');
        $Criteria->compare('t.period', $query, true, 'OR');
        $Criteria->compare('t.author_text', $query, true, 'OR');
        $Criteria->join .= ' LEFT JOIN {{authors}} AS authors ON t.author_id = authors.id';
        $Criteria->addSearchCondition('authors.surname', $query, true, 'OR');
        $Criteria->addSearchCondition('authors.name', $query, true, 'OR');
        $Criteria->addSearchCondition('authors.middlename', $query, true, 'OR');
        $Criteria->addSearchCondition('authors.initials', $query, true, 'OR');
        $Criteria->addInCondition('t.collection_id', Collections::getIdsOfNormalCollectionsAllowedToUser(Yii::app()->user->id));

        return Objects::model()->findAll($Criteria);
    }

    private function getImagesFoundModels($query)
    {
        $Criteria = new CDbCriteria;

        $Criteria->compare('t.description', $query, true, 'OR');
        $Criteria->compare('t.width', $query, true, 'OR');
        $Criteria->compare('t.height', $query, true, 'OR');
        $Criteria->compare('t.dpi', $query, true, 'OR');
        $Criteria->compare('t.original', $query, true, 'OR');
        $Criteria->compare('t.source', $query, true, 'OR');
        $Criteria->compare('t.request', $query, true, 'OR');
        $Criteria->compare('t.width_cm', $query, true, 'OR');
        $Criteria->compare('t.height_cm', $query, true, 'OR');
        $Criteria->join .= ' JOIN {{objects}} AS objects ON t.object_id = objects.id';
        $Criteria->addInCondition('objects.collection_id', Collections::getIdsOfNormalCollectionsAllowedToUser(Yii::app()->user->id));

        return Images::model()->findAll($Criteria);
    }

    private function getAllFoundModels($query)
    {
        return array_merge(
            $this->getCollectionsFoundModels($query),
            $this->getObjectsFoundModels($query),
            $this->getImagesFoundModels($query)
        );
    }

}