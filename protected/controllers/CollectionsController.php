<?php

class CollectionsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
                'actions' => array('view'),
                'roles' => array(
                    'oCollectionView' => array(
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
     * Просмотр коллекции
     * @param string $id айди коллекции
     * @param string $cv как отображать дочерние коллекции: th - картинками, ls - списком, tb - таблицей
     * @param string $ov как отображать объекты в коллекции: th - картинками, ls - списком, tb - таблицей
     * @param string $tb какая вкладка открыта (параметр используется уже во view): cc - дочерние коллекции, ob - объекты
     */
    public function actionView($id, $cv = 'th', $ov = 'th', $tb = 'cc')
	{
        $model = $this->loadModel($id);

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
        $this->pageMenu = array(
            array(
                'label' => 'Редактировать коллекцию',
                'url' => '#',
                //'itemOptions' => array('class' => 'small')
            ),
            array(
                'label' => 'Удалить коллекцию',
                'url' => '#',
                //'itemOptions' => array('class' => 'small')
            ),
            array(
                'label' => 'Добавить объект в коллекцию',
                'url' => '#',
                //'itemOptions' => array('class' => 'small')
            ),
        );

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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Collections;

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
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Collections']))
		{
			$model->attributes=$_POST['Collections'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
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
     * @param string $v как отображать коллекции: th - картинками, ls - списком, tb - таблицей
     */
    public function actionIndex($v = 'th')
	{
        $allowedCollectionsCriteria = Collections::getAllowedCollectionsCriteria(Yii::app()->user->id);

		$dataProvider=new CActiveDataProvider(
            'Collections',
            array('criteria' => $allowedCollectionsCriteria)
        );

        // параметры страницы
        $this->pageTitle = array('Коллекции');
        $this->breadcrumbs = array('Коллекции');
        $this->pageMenu = array(
            array(
                'label' => 'Создать коллекцию',
                'url' => $this->createUrl('collections/create'),
            ),
        );

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
                    throw new CHttpException(404,'The requested page does not exist.');
                }
            }

            return $this->model;
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
