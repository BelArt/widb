<?php

class CollectionsController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

    private $model;

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
                        'Collection' => $this->loadModel(Yii::app()->request->getQuery('id'))
                    )
                ),
            ),
            array('allow',
                'actions' => array('viewTemp'),
                'roles' => array(
                    'oTempCollectionView' => array(
                        'Collection' => $this->loadModel(Yii::app()->request->getQuery('id'))
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
                        'Collection' => $this->loadModel(Yii::app()->request->getQuery('id'))
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
        $model = $this->loadModel($id);

        if ($model->temporary) {
            throw new CHttpException(404,'Такой коллекции не существует!');
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
                'label' => 'Редактировать коллекцию',
                'url' => $this->createUrl(
                    'collections/update',
                    array('id' => $id)
                ),
                //'itemOptions' => array('class' => 'small')
            );
        }
        if (Yii::app()->user->checkAccess('oCollectionDelete')) {
            $pageMenu[] = array(
                'label' => 'Удалить коллекцию',
                'url' => '#',
                //'itemOptions' => array('class' => 'small')
            );
        }
        $this->pageMenu = $pageMenu;

		$this->render(
            'view',
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
    public function actionViewTemp($id, $cv = 'th', $ov = 'th', $tb = 'cc')
    {
        $model = $this->loadModel($id);

        if (!$model->temporary) {
            throw new CHttpException(404,'Такой коллекции не существует!');
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
        if (Yii::app()->user->checkAccess(
                'oTempCollectionEdit',
                array(
                    'Collection' => $model
                )
            )
        ) {
            $pageMenu[] = array(
                'label' => 'Редактировать временную коллекцию',
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
                'label' => 'Удалить временную коллекцию',
                'url' => '#',
                //'itemOptions' => array('class' => 'small')
            );
        }
        $this->pageMenu = $pageMenu;

        $this->render(
            'view',
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
     * Создание обычной коллекции
	 */
	public function actionCreate()
	{
		$model = new Collections;

        $model->temporary = 0;
        $view = '_formNormalCollection';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Collections']))
		{
			$model->attributes=$_POST['Collections'];
			if ($model->save()) {
                $this->redirect(array('index'));
            }
		}

        // параметры страницы
        $this->pageTitle = array('Создание коллекции');
        $this->breadcrumbs = array('Создание коллекции');
        $this->pageName = 'Создание коллекции';

		$this->render('create',array(
			'model' => $model,
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

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Collections']))
        {
            $model->attributes=$_POST['Collections'];
            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        // параметры страницы
        $this->pageTitle = array('Создание временной коллекции');
        $this->breadcrumbs = array('Создание временной коллекции');
        $this->pageName = 'Создание временной коллекции';

        $this->render('create',array(
            'model' => $model,
            'view' => $view
        ));
    }

	/**
     * Редактирование обычной коллекции
	 * @param integer $id айди коллекции
     * @throws CHttpException
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

        if ($model->temporary) {
            throw new CHttpException(404,'Такой коллекции не существует!');
        }

        $view = '_formNormalCollection';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Collections']))
		{
			$model->attributes=$_POST['Collections'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

        // параметры страницы
        $this->pageTitle = array($model->name,'Редактирование');
        $this->breadcrumbs = array($model->name => array('collections/view', 'id' => $id), 'Редактирование');
        $this->pageName = $model->name;

		$this->render('update',array(
			'model' => $model,
            'view' => $view
		));
	}

    /**
     * Редактирование временной коллекции
     * @param integer $id айди коллекции
     * @throws CHttpException
     */
    public function actionUpdateTemp($id)
    {
        $model = $this->loadModel($id);

        if (!$model->temporary) {
            throw new CHttpException(404,'Такой коллекции не существует!');
        }

        $view = '_formTempCollection';

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Collections']))
        {
            $model->attributes=$_POST['Collections'];
            if($model->save())
                $this->redirect(array('viewTemp','id'=>$model->id));
        }

        // параметры страницы
        $this->pageTitle = array($model->name,'Редактирование временной коллекции');
        $this->breadcrumbs = array($model->name => array('collections/viewTemp', 'id' => $id), 'Редактирование временной коллекции');
        $this->pageName = $model->name;

        $this->render('update',array(
            'model' => $model,
            'view' => $view
        ));
    }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
        $this->pageTitle = array('Коллекции');
        $this->breadcrumbs = array('Коллекции');
        $pageMenu = array();
        if (Yii::app()->user->checkAccess('oCollectionCreate')) {
            $pageMenu[] = array(
                'label' => 'Создать коллекцию',
                'url' => $this->createUrl('collections/create'),
            );
        }
        if (Yii::app()->user->checkAccess('oTempCollectionCreate')) {
            $pageMenu[] = array(
                'label' => 'Создать временную коллекцию',
                'url' => $this->createUrl('collections/createTemp'),
            );
        }
        $this->pageMenu = $pageMenu;

		$this->render('index',array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Collections('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Collections']))
			$model->attributes=$_GET['Collections'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Collections the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
        if (!empty($id)) {
            if (empty($this->model)) {
                $this->model = Collections::model()->findByPk($id);

                if (empty($this->model)) {
                    throw new CHttpException(404,'Такой коллекции не существует!');
                }
            }

            return $this->model;
        } else {
            return null;
        }
	}

	/**
	 * Performs the AJAX validation.
	 * @param Collections $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='collections-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
