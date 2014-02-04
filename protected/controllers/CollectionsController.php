<?php

class CollectionsController extends Controller
{
    private $collection;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
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
     * Просмотр обычной коллекции
     * @param string $id айди коллекции
     * @param string $cv как отображать дочерние коллекции: th - картинками, ls - списком, tb - таблицей
     * @param string $ov как отображать объекты в коллекции: th - картинками, ls - списком, tb - таблицей
     * @param string $tb какая вкладка открыта (параметр используется уже во view): cc - дочерние коллекции, ob - объекты
     * @throws CHttpException
     */
    public function actionView($id, $cv = 'th', $ov = 'th', $tb = 'cc')
	{
        $model = $this->loadCollection($id);

        if ($model->temporary) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

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
            default: // картинками
                $renderViewChildCollections = '_viewChildCollectionsThumbnails';
        }

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
            default: // картинками
                $renderViewObjects = '_viewObjectsThumbnails';
        }

        $ObjectsCriteria = new CDbCriteria();
        $ObjectsCriteria->condition = 't.collection_id = :collection_id';
        $ObjectsCriteria->params = array(':collection_id' => $id);
        $ObjectsCriteria->with = array('author');

        $ObjectsDataProvider = new CActiveDataProvider('Objects', array('criteria' => $ObjectsCriteria));

        $ChildCollectionsDataProvider = new CActiveDataProvider(
            'Collections',
            array(
                'criteria' => array(
                    'condition' => 'parent_id = :parent_id',
                    'params' => array(':parent_id' => $id)
                ),
            )
        );

        // параметры страницы
        $this->pageTitle = array($model->name);
        $this->breadcrumbs = array($model->name);
        $this->pageName = $model->name;
        $pageMenu = array();
        if (Yii::app()->user->checkAccess('oCollectionEdit')) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Редактировать коллекцию'),
                'url' => $this->createUrl(
                    'collections/update',
                    array('id' => $id)
                ),
            );
        }
        if (Yii::app()->user->checkAccess('oCollectionDelete')) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Удалить коллекцию'),
                'url' => $this->createUrl('collections/delete', array('id' => $id)),
                'itemOptions' => array(
                    'class' => '_deleteCollection',
                    'data-dialog-title' => CHtml::encode(Yii::t('collections', 'Удалить коллекцию?')),
                    'data-dialog-message' => CHtml::encode(Yii::t('collections', 'Вы уверены, что хотите удалить коллекцию? Ее нельзя будет восстановить!')),
                )
            );
        }
        if (Yii::app()->user->checkAccess('oObjectCreate')) {
            $pageMenu[] = array(
                'label' => Yii::t('objects', 'Создать объект в коллекции'),
                'url' => $this->createUrl('objects/create', array('ci' => $id)),
            );
        }
        $this->pageMenu = $pageMenu;

		$this->render(
            'viewNormal',
            array(
                'model' => $model,
                'ObjectsDataProvider' => $ObjectsDataProvider,
                'ChildCollectionsDataProvider' => $ChildCollectionsDataProvider,
                'renderViewChildCollections' => $renderViewChildCollections,
                'renderViewObjects' => $renderViewObjects,
            )
        );
	}

    /**
     * Просмотр временной коллекции
     * @param string $id айди коллекции
     * @param string $cv как отображать дочерние коллекции: th - картинками, ls - списком, tb - таблицей
     * @param string $ov как отображать объекты в коллекции: th - картинками, ls - списком, tb - таблицей
     * @param string $tb какая вкладка открыта (параметр используется уже во view): cc - дочерние коллекции, ob - объекты
     * @throws CHttpException
     */
    public function actionViewTemp($id, /*$cv = 'th',*/ $ov = 'th'/*, $tb = 'cc'*/)
    {
        $model = $this->loadCollection($id);

        if (!$model->temporary) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

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
            default: // картинками
                $renderViewObjects = '_viewObjectsThumbnails';
        }

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

        $ObjectsDataProvider = new CActiveDataProvider('Objects', array('criteria' => $ObjectsCriteria));

        // параметры страницы
        $this->pageTitle = array($model->name);
        $this->breadcrumbs = array($model->name);
        $this->pageName = $model->name;
        $pageMenu = array();
        if (Yii::app()->user->checkAccess(
                'oTempCollectionEdit',
                array(
                    'Collection' => $model
                )
            )
        ) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Редактировать временную коллекцию'),
                'url' => $this->createUrl(
                        'collections/updateTemp',
                        array('id' => $id)
                    ),
                //'itemOptions' => array('class' => 'small')
            );
        }
        if (Yii::app()->user->checkAccess(
                'oTempCollectionDelete',
                array(
                    'Collection' => $model
                )
            )
        ) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Удалить временную коллекцию'),
                'url' => $this->createUrl('collections/deleteTemp', array('id' => $id)),
                'itemOptions' => array(
                    'class' => '_deleteTempCollection',
                    'data-dialog-title' => CHtml::encode(Yii::t('collections', 'Удалить временную коллекцию?')),
                    'data-dialog-message' => CHtml::encode(Yii::t('collections', 'Вы уверены, что хотите удалить временную коллекцию? Ее нельзя будет восстановить!')),
                )
            );
        }
        $this->pageMenu = $pageMenu;

        $this->render(
            'viewTemp',
            array(
                'model' => $model,
                'ObjectsDataProvider' => $ObjectsDataProvider,
                //'ChildCollectionsDataProvider' => $ChildCollectionsDataProvider,
                //'renderViewChildCollections' => $renderViewChildCollections,
                'renderViewObjects' => $renderViewObjects,
            )
        );
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
            $model->attributes = $_POST['Collections'];

            $transaction = Yii::app()->db->beginTransaction();

            try {
                if ($model->save()) {
                    $transaction->commit();
                    $this->redirect(array('index'));
                } else {
                    $transaction->rollback();
                    PreviewHelper::clearUserPreviewsUploads();
                }
            } catch (Exception $e) {
                $transaction->rollback();
                PreviewHelper::clearUserPreviewsUploads();
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
            $model->attributes = $_POST['Collections'];

            $transaction = Yii::app()->db->beginTransaction();

            try {
                if ($model->save()) {
                    $transaction->commit();
                    $this->redirect(array('index'));
                } else {
                    $transaction->rollback();
                    PreviewHelper::clearUserPreviewsUploads();
                }
            } catch (Exception $e) {
                $transaction->rollback();
                PreviewHelper::clearUserPreviewsUploads();
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

        if ($model->temporary) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $view = '_formNormalCollection';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Collections']))
		{
			$model->attributes = $_POST['Collections'];

            $transaction = Yii::app()->db->beginTransaction();

            try {
                if ($model->save()) {
                    $transaction->commit();
                    $this->redirect(array('view','id'=>$model->id));
                } else {
                    $transaction->rollback();
                    PreviewHelper::clearUserPreviewsUploads();
                }
            } catch (Exception $e) {
                $transaction->rollback();
                PreviewHelper::clearUserPreviewsUploads();
                throw $e;
            }
		}

        // параметры страницы
        $this->pageTitle = array($model->name, Yii::t('collections', 'Редактирование коллекции'));
        $this->breadcrumbs = array($model->name => array('collections/view', 'id' => $id), Yii::t('collections', 'Редактирование коллекции'));
        $this->pageName = $model->name;

		$this->render('update',array(
			'model' => $model,
            'photoUploadModel' => $PhotoUploadModel,
            'view' => $view
		));
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

        if (!$model->temporary) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        Yii::import( "xupload.models.XUploadForm" );
        $PhotoUploadModel = new MyXUploadForm;

        $view = '_formTempCollection';

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Collections']))
        {
            $model->attributes = $_POST['Collections'];

            $transaction = Yii::app()->db->beginTransaction();

            try {
                if ($model->save()) {
                    $transaction->commit();
                    $this->redirect(array('viewTemp','id'=>$model->id));
                } else {
                    $transaction->rollback();
                    PreviewHelper::clearUserPreviewsUploads();
                }
            } catch (Exception $e) {
                $transaction->rollback();
                PreviewHelper::clearUserPreviewsUploads();
                throw $e;
            }
        }

        // параметры страницы
        $this->pageTitle = array($model->name, Yii::t('collections', 'Редактирование временной коллекции'));
        $this->breadcrumbs = array($model->name => array('collections/viewTemp', 'id' => $id), Yii::t('collections', 'Редактирование временной коллекции'));
        $this->pageName = $model->name;

        $this->render('update',array(
            'model' => $model,
            'photoUploadModel' => $PhotoUploadModel,
            'view' => $view
        ));
    }

	/**
	 * Удаляет коллекцию
	 * @param integer $id айди коллекции
	 */
	public function actionDelete($id)
	{
        if (DeleteHelper::deleteNormalCollection($id)) {
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

    /**
     * Удаляет временную коллекцию
     * @param integer $id айди временной коллекции
     */
    public function actionDeleteTemp($id)
    {
        DeleteHelper::deleteTempCollection($id);

        Yii::app()->user->setFlash(
            'success',
            Yii::t('collections', 'Временная коллекция удалена')
        );
        $this->redirect(array('index'));
    }


    /**
     * Страница со списком всех коллекций, главная страница
     * @param string $cv как отображать коллекции: th - картинками, ls - списком, tb - таблицей
     */
    public function actionIndex($cv = 'th')
	{
        $allowedCollectionsCriteria = Collections::getAllowedCollectionsCriteria(Yii::app()->user->id);

		$dataProvider=new CActiveDataProvider(
            'Collections',
            array('criteria' => $allowedCollectionsCriteria)
        );

        // параметры страницы
        //$this->pageTitle = array(Yii::t('collections', 'Коллекции'));
        //$this->breadcrumbs = array(Yii::t('collections', 'Коллекции'));
        //$this->breadcrumbs = array();
        $pageMenu = array();
        if (Yii::app()->user->checkAccess('oCollectionCreate')) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Создать коллекцию'),
                'url' => $this->createUrl('collections/create'),
            );
        }
        if (Yii::app()->user->checkAccess('oTempCollectionCreate')) {
            $pageMenu[] = array(
                'label' => Yii::t('collections', 'Создать временную коллекцию'),
                'url' => $this->createUrl('collections/createTemp'),
            );
        }
        $this->pageMenu = $pageMenu;

		$this->render('index',array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Collections the loaded model
	 * @throws CHttpException
	 */
	public function loadCollection($id)
	{
        if (!empty($id)) {
            if (empty($this->collection)) {
                $this->collection = Collections::model()->findByPk($id);

                if (empty($this->collection)) {
                    throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
                }
            }

            return $this->collection;
        } else {
            return null;
        }
	}

	/**
	 * Performs the AJAX validation.
	 * @param Collections $model the model to be validated
	 */
	/*protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='collections-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}*/
}
