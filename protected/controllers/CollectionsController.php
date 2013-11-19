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
                //'roles' => array('oCollectionView'), // @todo эта проверка должна работать
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id, $cv = 'thumbnails', $ov = 'thumbnails')
	{
        $model = $this->loadModel($id);

        switch ($cv) {
            case 'thumbnails':
                $renderViewChildCollections = '_viewChildCollectionsThumbnails';
                break;
            case 'list':
                $renderViewChildCollections = '_viewChildCollectionsList';
                break;
            case 'table':
                $renderViewChildCollections = '_viewChildCollectionsTable';
                break;
            default:
                $renderViewChildCollections = '_viewChildCollectionsThumbnails';
        }

        switch ($ov) {
            case 'thumbnails':
                $renderViewObjects = '_viewObjectsThumbnails';
                break;
            case 'list':
                $renderViewObjects = '_viewObjectsList';
                break;
            case 'table':
                $renderViewObjects = '_viewObjectsTable';
                break;
            default:
                $renderViewObjects = '_viewObjectsThumbnails';
        }

        $ObjectsDataProvider = new CActiveDataProvider(
            'Objects',
            array(
                'criteria' => array(
                    'condition' => 't.collection_id = :collection_id AND t.deleted = 0',
                    'params' => array(':collection_id' => $id),
                    'with' => array('author')
                ),
            )
        );

        $ChildCollectionsDataProvider = new CActiveDataProvider(
            'Collections',
            array(
                'criteria' => array(
                    'condition' => 'parent_id = :parent_id AND deleted = 0',
                    'params' => array(':parent_id' => $id)
                ),
            )
        );

        // параметры страницы
        $this->pageTitle = array($model->name);
        $this->breadcrumbs = array($model->name);
        $this->pageName = $model->name;

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
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Collections');

        // параметры страницы
        $this->pageTitle = array('Коллекции');
        $this->breadcrumbs = array('Коллекции');

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
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
		$model=Collections::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
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
