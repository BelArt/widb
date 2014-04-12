<?php

/**
 * Контроллер коллекций
 */
class CollectionsController extends MyController
{
    private $_collection;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
            'forActionView + view',
            'forActionViewTemp + viewTemp',
            'forActionUpdate + update',
            'forActionUpdateTemp + updateTemp',
            'forActionDelete + delete',
            'forActionDeleteTemp + deleteTemp',
            'forActionIndex + index',
            array(
                'application.components.filters.MySaveGetParamsToSessionFilter + view, viewTemp, index',
            ),
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
                'actions' => array('index'),
                'roles' => array('oCollectionsView'),
            ),
            array('allow',
                'actions' => array('create'),
                'roles' => array('oCollectionCreate'),
            ),
            array('allow',
                'actions' => array('createTemp'),
                'roles' => array('oTempCollectionCreate'),
            ),
            array('allow',
                'actions' => array('view'),
                'roles' => array(
                    'oCollectionView' => array(
                        'Collection' => $this->loadCollection(Yii::app()->request->getQuery('id'))
                    )
                ),
            ),
            array('allow',
                'actions' => array('viewTemp'),
                'roles' => array(
                    'oTempCollectionView' => array(
                        'Collection' => $this->loadCollection(Yii::app()->request->getQuery('id'))
                    )
                ),
            ),
            array('allow',
                'actions' => array('update'),
                'roles' => array('oCollectionEdit'),
            ),
            array('allow',
                'actions' => array('updateTemp'),
                'roles' => array(
                    'oTempCollectionEdit' => array(
                        'Collection' => $this->loadCollection(Yii::app()->request->getQuery('id'))
                    )
                ),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('oCollectionDelete'),
            ),
            array('allow',
                'actions' => array('deleteTemp'),
                'roles' => array(
                    'oTempCollectionDelete' => array(
                        'Collection' => $this->loadCollection(Yii::app()->request->getQuery('id'))
                    )
                ),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }


    /**
     * Просмотр обычной коллекции.
     *
     * @param int $id айди коллекции
     * @param string $cv как отображать дочерние коллекции: th - картинками, ls - списком, tb - таблицей
     * @param string $ov как отображать объекты в коллекции: th - картинками, ls - списком, tb - таблицей
     * @param string $tb какая вкладка открыта: cc - дочерние коллекции, ob - объекты
     * @param string $opp - кол-во выводимых объектов на страницу
     */
    public function actionView($id, $cv, $ov, $tb, $opp)
	{
        $Collection = $this->loadCollection($id);

        $this->setPageParamsForActionView($id);

		$this->render('viewNormal', array(
            'model' => $Collection,
            'ObjectsDataProvider' => $this->getCollectionObjectsDataProviderForActionView($id,$opp),
            'ChildCollectionsDataProvider' => $this->getChildCollectionDataProviderForActionView($id),
            'renderViewChildCollections' => $this->getChildCollectionsViewNameForActionView($cv),
            'renderViewObjects' => $this->getCollectionObjectsViewName($ov),
            'tempCollectionsAllowedToUser' => Collections::getTempCollectionsAllowedToUser(Yii::app()->user->id),
            'collectionsToMoveTo' => Collections::getAllNormalCollectionsExcept($id),
            'activeTab' => $this->getNameOfActiveTabForActionView($tb)
        ));
	}

    public function filterForActionView($filterChain)
    {
        /*
         * Проверяем первый параметр - айди коллекции
         * если чот-то не так, что будет брошено исключение в методе loadCollection()
         * Заодно подгрузим модель коллекции
         */
        $Collection = $this->loadCollection(Yii::app()->request->getQuery('id'));

        // проверяем, что колелкция не временная
        if (empty($Collection) || $Collection->temporary) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        // тип отображения дочерних коллекций
        if (!in_array(Yii::app()->request->getQuery('cv'), array('th','ls','tb'))) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        // тип отображения объектов в коллекции
        if (!in_array(Yii::app()->request->getQuery('ov'), array('th','ls','tb'))) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        // какая вкладка открыта
        if (!in_array(Yii::app()->request->getQuery('tb'), array('cc','ob'))) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        // кол-во объектов на страницу
        $objectsPerPage = Yii::app()->request->getQuery('opp');
        if (!preg_match('/^[1-9]{1}\d*$/', $objectsPerPage)) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

    /**
     * Возвращает имя вью, которую надо исопльзовать для рендеринга дочерних коллекций
     * @param string $cv параметр, определяющий вью
     * @return string имя вью
     */
    private function getChildCollectionsViewNameForActionView($cv)
    {
        // как отображать дочерние коллекции
        switch ($cv) {
            case 'th': // картинками
                $renderViewChildCollections = '_viewChildCollectionsThumbnails';
                break;
            case 'ls': // списком
                $renderViewChildCollections = '_viewChildCollectionsList';
                break;
            case 'tb': // таблицей
                $renderViewChildCollections = '_viewChildCollectionsTable';
                break;
        }

        return $renderViewChildCollections;
    }

    /**
     * Возвращает имя вью, которую надо исопльзовать для рендеринга объектов коллекции
     * @param string $ov параметр, определяющий вью
     * @return string имя вью
     */
    private function getCollectionObjectsViewName($ov)
    {
        // как отображать объекты в коллекции
        switch ($ov) {
            case 'th': // картинками
                $renderViewObjects = '_viewObjectsThumbnails';
                break;
            case 'ls': // списком
                $renderViewObjects = '_viewObjectsList';
                break;
            case 'tb': // таблицей
                $renderViewObjects = '_viewObjectsTable';
                break;
        }

        return $renderViewObjects;
    }

    /**
     * Устанавливаем параметры страницы коллекции - тайтл, крошки и т.д.
     *
     * @param int $id айди коллекции
     */
    private function setPageParamsForActionView($id)
    {
        $Collection = $this->loadCollection($id);

        // параметры страницы
        $this->pageTitle = array($Collection->name);
        $this->breadcrumbs = array($Collection->name);
        //$this->pageName = $model->name;
        $pageMenu = array();
        if (Yii::app()->user->checkAccess('oCollectionCreate')) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Создать коллекцию'),
                'url' => $this->createUrl('collections/create'),
                'iconType' => 'create_normal_col'
            );
        }
        if (Yii::app()->user->checkAccess('oTempCollectionCreate')) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Создать временную коллекцию'),
                'url' => $this->createUrl('collections/createTemp'),
                'iconType' => 'create_temp_col'
            );
        }
        if (Yii::app()->user->checkAccess('oCollectionEdit')) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Редактировать коллекцию'),
                'url' => $this->createUrl(
                        'collections/update',
                        array('id' => $id)
                    ),
                'iconType' => 'edit'
            );
        }
        if (Yii::app()->user->checkAccess('oCollectionDelete')) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Удалить коллекцию'),
                'url' => $this->createUrl('collections/delete', array('id' => $id)),
                'iconType' => 'delete',
                'itemOptions' => array(
                    'class' => '_deleteCollection',
                    'data-dialog-title' => Yii::t('collections', 'Удалить коллекцию?'),
                    'data-dialog-message' => Yii::t('collections', 'Вы уверены, что хотите удалить коллекцию? Ее нельзя будет восстановить!'),
                )
            );
        }
        if (Yii::app()->user->checkAccess('oObjectCreate')) {
            $pageMenu[] = array(
                'label' => Yii::t('objects', 'Создать объект в коллекции'),
                'url' => $this->createUrl('objects/create', array('ci' => $id)),
                'iconType' => 'create'
            );
        }
        $this->pageMenu = $pageMenu;
    }

    /**
     * Возвращает датапровайдер дочерних коллекци.
     *
     * @param int $id айди коллекции
     * @return CActiveDataProvider датапровайдер дочерних коллекци
     */
    private function getChildCollectionDataProviderForActionView($id)
    {
        $ChildCollectionsDataProvider = new CActiveDataProvider(
            'Collections',
            array(
                'criteria' => array(
                    'condition' => 'parent_id = :parent_id',
                    'params' => array(':parent_id' => $id)
                ),
                'pagination' => array(
                    'pageVar' => 'p',
                    // чтобы правильно строить урлы пейджера на вкладке Дочерние коллекции при просмотре картинками
                    'params' => (empty($_GET['tb']) ? array('id' => $id,'tb' => 'cc', 'cv' => 'th') : null)
                ),
            )
        );

        return $ChildCollectionsDataProvider;
    }

    /**
     * Возвращает датапровайдер объектов коллекции.
     *
     * @param int $id айди коллекции
     * @param int $opp кол-во объектов на страницу
     * @return CActiveDataProvider датапровайдер объектов коллекци
     */
    private function getCollectionObjectsDataProviderForActionView($id, $opp)
    {
        $ObjectsCriteria = new CDbCriteria();
        $ObjectsCriteria->condition = 't.collection_id = :collection_id';
        $ObjectsCriteria->params = array(':collection_id' => $id);
        $ObjectsCriteria->with = array('author');

        $ObjectsDataProvider = new CActiveDataProvider('Objects', array(
            'criteria' => $ObjectsCriteria,
            'pagination' => array(
                'pageVar' => 'p',
                'pageSize' => $opp
            ),
        ));

        return $ObjectsDataProvider;
    }

    /**
     * Возвращает тип таба, который дожен быть открыт.
     *
     * @param string $tb какая вкладка открыта: cc - дочерние коллекции, ob - объекты
     * @return string тип таба, который дожен быть открыт
     */
    private function getNameOfActiveTabForActionView($tb)
    {
        // какая вкладка открыта
        switch ($tb) {
            case 'cc': // дочерние коллекции
                $activeTab = 'childCollections';
                break;
            case 'ob': // объекты
                $activeTab = 'objects';
                break;
        }

        return $activeTab;
    }

    /**
     * Просмотр временной коллекции.
     *
     * @param int $id айди коллекции
     * @param string $ov как отображать объекты в коллекции: th - картинками, ls - списком, tb - таблицей
     * @param int $opp - кол-во выводимых объектов на страницу
     */
    public function actionViewTemp($id, $ov, $opp)
    {
        $Collection = $this->loadCollection($id);
        $this->setPageParamsForActionViewTemp($id);

        $this->render('viewTemp', array(
            'model' => $Collection,
            'ObjectsDataProvider' => $this->getCollectionObjectsDataProviderForActionViewTemp($id, $opp),
            'renderViewObjects' => $this->getCollectionObjectsViewName($ov),
            'tempCollectionsAllowedToUser' => Collections::getTempCollectionsAllowedToUser(Yii::app()->user->id),
        ));
    }

    public function filterForActionViewTemp($filterChain)
    {
        /*
         * Проверяем первый параметр - айди коллекции
         * если чот-то не так, что будет брошено исключение в методе loadCollection()
         * Заодно подгрузим модель коллекции
         */
        $Collection = $this->loadCollection(Yii::app()->request->getQuery('id'));

        // проверяем, что колелкция не временная
        if (empty($Collection) || !$Collection->temporary) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        // тип отображения объектов в коллекции
        if (!in_array(Yii::app()->request->getQuery('ov'), array('th','ls','tb'))) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        // кол-во объектов на страницу
        $objectsPerPage = Yii::app()->request->getQuery('opp');
        if (!preg_match('/^[1-9]{1}\d*$/', $objectsPerPage)) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

    /**
     * Устанавливаем параметры страницы временной коллекции - тайтл, крошки и т.д.
     *
     * @param int $id айди коллекции
     */
    private function setPageParamsForActionViewTemp($id)
    {
        $Collection = $this->loadCollection($id);

        // параметры страницы
        $this->pageTitle = array($Collection->name);
        $this->breadcrumbs = array($Collection->name);
        //$this->pageName = $model->name;
        $pageMenu = array();
        if (Yii::app()->user->checkAccess('oCollectionCreate')) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Создать коллекцию'),
                'url' => $this->createUrl('collections/create'),
                'iconType' => 'create_normal_col'
            );
        }
        if (Yii::app()->user->checkAccess('oTempCollectionCreate')) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Создать временную коллекцию'),
                'url' => $this->createUrl('collections/createTemp'),
                'iconType' => 'create_temp_col'
            );
        }
        if (Yii::app()->user->checkAccess('oTempCollectionEdit', array(
            'Collection' => $Collection
        ))) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Редактировать временную коллекцию'),
                'url' => $this->createUrl(
                        'collections/updateTemp',
                        array('id' => $id)
                    ),
                'iconType' => 'edit'
                //'itemOptions' => array('class' => 'small')
            );
        }
        if (Yii::app()->user->checkAccess('oTempCollectionDelete', array(
            'Collection' => $Collection
        ))) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Удалить временную коллекцию'),
                'url' => $this->createUrl('collections/deleteTemp', array('id' => $id)),
                'itemOptions' => array(
                    'class' => '_deleteTempCollection',
                    'data-dialog-title' => Yii::t('collections', 'Удалить временную коллекцию?'),
                    'data-dialog-message' => Yii::t('collections', 'Вы уверены, что хотите удалить временную коллекцию? Ее нельзя будет восстановить!'),
                ),
                'iconType' => 'delete'
            );
        }
        $this->pageMenu = $pageMenu;
    }

    /**
     * Возвращает датапровайдер объектов временной коллекции.
     *
     * @param int $id айди коллекции
     * @param int $opp кол-во объектов на страницу
     * @return CActiveDataProvider датапровайдер объектов временной коллекци
     */
    private function getCollectionObjectsDataProviderForActionViewTemp($id, $opp)
    {
        $TempCollectionObjectsCriteria = new CDbCriteria();
        $TempCollectionObjectsCriteria->select = 'object_id';
        $TempCollectionObjectsCriteria->condition = 'collection_id = :collection_id';
        $TempCollectionObjectsCriteria->params = array(':collection_id' => $id);
        $tempCollectionObjects = TempCollectionObject::model()->findAll($TempCollectionObjectsCriteria);

        $objectIds = array();

        foreach ($tempCollectionObjects as $Record) {
            if (Objects::getObjectIsAllowedToUser($Record->object_id, Yii::app()->user->id)) {
                $objectIds[] = $Record->object_id;
            }
        }

        $ObjectsCriteria = new CDbCriteria();
        $ObjectsCriteria->addInCondition('t.id', $objectIds);
        $ObjectsCriteria->with = array('author');

        $ObjectsDataProvider = new CActiveDataProvider('Objects', array(
            'criteria' => $ObjectsCriteria,
            'pagination' => array(
                'pageVar' => 'p',
                'pageSize' => $opp
            ),
        ));

        return $ObjectsDataProvider;
    }

	/**
     * Создание обычной коллекции
	 */
	public function actionCreate()
	{
		$model = new Collections;

        $model->temporary = 0;

        Yii::import( "xupload.models.XUploadForm" );
        $PhotoUploadModel = new MyXUploadForm;

        $view = '_formNormalCollection';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

        if(isset($_POST['Collections']))
        {
            $transaction = Yii::app()->db->beginTransaction();

            try {
                $model->attributes = $_POST['Collections'];
                if ($model->save()) {
                    $model->saveUploadedPreviews();
                    $transaction->commit();
                    $this->redirect(array('index'));
                } else {
                    $transaction->rollback();
                    MyPreviewHelper::clearUserPreviewsUploads();
                }
            } catch (Exception $e) {
                $transaction->rollback();
                MyPreviewHelper::clearUserPreviewsUploads();
                throw $e;
            }
        }

        // параметры страницы
        $this->pageTitle = array(Yii::t('collections', 'Создание коллекции'));
        $this->breadcrumbs = array(Yii::t('collections', 'Создание коллекции'));
        $this->pageName = Yii::t('collections', 'Создание коллекции');

		$this->render('create',array(
			'model' => $model,
            'photoUploadModel' => $PhotoUploadModel,
            'view' => $view
		));
	}

    /**
     * Создание временной коллекции
     */
    public function actionCreateTemp()
    {
        $model = new Collections;

        $model->temporary = 1;
        $view = '_formTempCollection';

        Yii::import( "xupload.models.XUploadForm" );
        $PhotoUploadModel = new MyXUploadForm;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Collections']))
        {
            $transaction = Yii::app()->db->beginTransaction();

            try {
                $model->attributes = $_POST['Collections'];
                if ($model->save()) {
                    $model->saveUploadedPreviews();
                    $transaction->commit();
                    $this->redirect(array('index'));
                } else {
                    $transaction->rollback();
                    MyPreviewHelper::clearUserPreviewsUploads();
                }
            } catch (Exception $e) {
                $transaction->rollback();
                MyPreviewHelper::clearUserPreviewsUploads();
                throw $e;
            }
        }

        // параметры страницы
        $this->pageTitle = array(Yii::t('collections', 'Создание временной коллекции'));
        $this->breadcrumbs = array(Yii::t('collections', 'Создание временной коллекции'));
        $this->pageName = Yii::t('collections', 'Создание временной коллекции');

        $this->render('create',array(
            'model' => $model,
            'photoUploadModel' => $PhotoUploadModel,
            'view' => $view
        ));
    }

	/**
     * Редактирование обычной коллекции
	 * @param integer $id айди коллекции
     * @throws CHttpException
     * @throws Exception
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadCollection($id);

        Yii::import( "xupload.models.XUploadForm" );
        $PhotoUploadModel = new MyXUploadForm;

        $view = '_formNormalCollection';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Collections']))
		{
            // @todo доделать смену кода
            /*$movePreviews = false;
            if (!empty($_POST['Collections']['code']) && $model->code != $_POST['Collections']['code']) {
                $oldCollection = clone $model;
                $movePreviews = true;
            }*/

			$model->attributes = $_POST['Collections'];

            $transaction = Yii::app()->db->beginTransaction();

            try {
                if ($model->save()) {
                    $model->saveUploadedPreviews();
                    // @todo доделать смену кода
                    /*if ($movePreviews) {
                        MyPreviewHelper::changePreviewPath($oldCollection, $_POST['Collections']['code']);
                    }*/
                    $transaction->commit();
                    $this->redirect(Yii::app()->urlManager->createNormalCollectionUrl($model));
                } else {
                    $transaction->rollback();
                    MyPreviewHelper::clearUserPreviewsUploads();
                }
            } catch (Exception $e) {
                $transaction->rollback();
                MyPreviewHelper::clearUserPreviewsUploads();
                throw $e;
            }
		}

        // параметры страницы
        $this->pageTitle = array($model->name, Yii::t('collections', 'Редактирование коллекции'));
        $this->breadcrumbs = array($model->name => Yii::app()->urlManager->createNormalCollectionUrl($model), Yii::t('collections', 'Редактирование коллекции'));
        $this->pageName = $model->name;

		$this->render('update',array(
			'model' => $model,
            'photoUploadModel' => $PhotoUploadModel,
            'view' => $view
		));
	}

    public function filterForActionUpdate($filterChain)
    {
        $Collection = $this->loadCollection(Yii::app()->request->getQuery('id'));

        if (empty($Collection) || $Collection->temporary) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

    /**
     * Редактирование временной коллекции
     * @param integer $id айди коллекции
     * @throws CHttpException
     * @throws Exception
     */
    public function actionUpdateTemp($id)
    {
        $model = $this->loadCollection($id);

        Yii::import( "xupload.models.XUploadForm" );
        $PhotoUploadModel = new MyXUploadForm;

        $view = '_formTempCollection';

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Collections']))
        {
            // @todo доделать смену кода
            /*$movePreviews = false;
            if (!empty($_POST['Collections']['code']) && $model->code != $_POST['Collections']['code']) {
                $oldCollection = clone $model;
                $movePreviews = true;
            }*/

            $model->attributes = $_POST['Collections'];

            $transaction = Yii::app()->db->beginTransaction();

            try {
                if ($model->save()) {
                    $model->saveUploadedPreviews();
                    // @todo доделать смену кода
                    /*if ($movePreviews) {
                        MyPreviewHelper::changePreviewPath($oldCollection, $_POST['Collections']['code']);
                    }*/
                    $transaction->commit();
                    $this->redirect(Yii::app()->urlManager->createTempCollectionUrl($model));
                } else {
                    $transaction->rollback();
                    MyPreviewHelper::clearUserPreviewsUploads();
                }
            } catch (Exception $e) {
                $transaction->rollback();
                MyPreviewHelper::clearUserPreviewsUploads();
                throw $e;
            }
        }

        // параметры страницы
        $this->pageTitle = array($model->name, Yii::t('collections', 'Редактирование временной коллекции'));
        $this->breadcrumbs = array($model->name => Yii::app()->urlManager->createTempCollectionUrl($model), Yii::t('collections', 'Редактирование временной коллекции'));
        $this->pageName = $model->name;

        $this->render('update',array(
            'model' => $model,
            'photoUploadModel' => $PhotoUploadModel,
            'view' => $view
        ));
    }

    public function filterForActionUpdateTemp($filterChain)
    {
        $Collection = $this->loadCollection(Yii::app()->request->getQuery('id'));

        if (empty($Collection) || !$Collection->temporary) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

	/**
	 * Удаляет коллекцию
	 * @param integer $id айди коллекции
	 */
	public function actionDelete($id)
	{
        if (MyDeleteHelper::deleteNormalCollection($id)) {
            Yii::app()->user->setFlash(
                'success',
                Yii::t('collections', 'Коллекция удалена')
            );
            $this->redirect(array('index'));
        } else {
            Yii::app()->user->setFlash(
                'error',
                Yii::t('collections', 'Коллекция не удалена. У коллекции не должно быть дочерних коллекций и относящихся к ней объектов, чтобы ее можно было удалить')
            );
            $this->redirect(Yii::app()->request->urlReferrer);
        }
	}

    public function filterForActionDelete($filterChain)
    {
        $Collection = $this->loadCollection(Yii::app()->request->getQuery('id'));

        if (empty($Collection) || $Collection->temporary) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

    /**
     * Удаляет временную коллекцию
     * @param integer $id айди временной коллекции
     */
    public function actionDeleteTemp($id)
    {
        MyDeleteHelper::deleteTempCollection($id);

        Yii::app()->user->setFlash(
            'success',
            Yii::t('collections', 'Временная коллекция удалена')
        );
        $this->redirect(array('index'));
    }

    public function filterForActionDeleteTemp($filterChain)
    {
        $Collection = $this->loadCollection(Yii::app()->request->getQuery('id'));

        if (empty($Collection) || !$Collection->temporary) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }


    /**
     * Страница со списком всех коллекций, главная страница
     * @param string $cv как отображать коллекции: th - картинками, ls - списком, tb - таблицей
     */
    public function actionIndex($cv)
	{
        $this->setPageParamsForActionIndex();
		$this->render('index', array(
			'dataProvider' => $this->getCollectionsDataProviderForActionIndex(),
            'viewType' => $this->getCollectionsViewTypeForActionIndex($cv),
		));
	}

    public function filterForActionIndex($filterChain)
    {
        // тип отображения коллекций
        if (!in_array(Yii::app()->request->getQuery('cv'), array('th','ls','tb'))) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

    private function getCollectionsDataProviderForActionIndex()
    {
        $dataProvider = new CActiveDataProvider('Collections',
            array(
                'criteria' => Collections::getAllowedCollectionsCriteria(Yii::app()->user->id),
                'pagination'=>array(
                    //'pageSize' => 2,
                    'pageVar' => 'p'
                ),
            )
        );

        return $dataProvider;
    }

    private function setPageParamsForActionIndex()
    {
        // параметры страницы
        //$this->pageTitle = array(Yii::t('collections', 'Коллекции'));
        //$this->breadcrumbs = array(Yii::t('collections', 'Коллекции'));
        //$this->breadcrumbs = array();
        $pageMenu = array();
        if (Yii::app()->user->checkAccess('oCollectionCreate')) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Создать коллекцию'),
                'url' => $this->createUrl('collections/create'),
                'iconType' => 'create_normal_col'
            );
        }
        if (Yii::app()->user->checkAccess('oTempCollectionCreate')) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Создать временную коллекцию'),
                'url' => $this->createUrl('collections/createTemp'),
                'iconType' => 'create_temp_col'
            );
        }
        $this->pageMenu = $pageMenu;
    }

    private function getCollectionsViewTypeForActionIndex($cv)
    {
        // как отображать коллекции
        switch ($cv) {
            case 'th': // картинками
                $viewType = 'thumbnails';
                break;
            case 'ls': // списком
                $viewType = 'list';
                break;
            case 'tb': // таблицей
                $viewType = 'table';
                break;
        }

        return $viewType;
    }

	/**
     * Загружает модель коллекции.
     *
     * @param int $id айди коллекции
     * @return mixed модель коллекции (Collections) или null, если айди не передан
     * @throws CHttpException
	 */
	public function loadCollection($id)
	{
        if (empty($id)) {
            return null;
        }

        if (empty($this->_collection)) {
            $this->_collection = Collections::model()->findByPk($id);

            if (empty($this->_collection)) {
                throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
            }
        }

        return $this->_collection;
	}
}
