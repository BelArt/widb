<?php

class ObjectsController extends Controller
{
    private $model;
    private $collectionModel;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'forActionCreate + create',
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
                'actions' => array('create'),
                'roles' => array('oObjectCreate'),
            ),
            array('allow',
                'actions' => array('view'),
                'roles' => array(
                    'oObjectView' => array(
                        'Collection' => $this->loadCollectionModel(Yii::app()->request->getQuery('id'))
                    )
                ),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

	/**
     * Создание объекта
	 */
	public function actionCreate($ci)
	{
		$model = new Objects();

        Yii::import( "xupload.models.XUploadForm" );
        $PhotoUploadModel = new MyXUploadForm;

        $view = '_formObject';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

        if(isset($_POST['Objects']))
        {
            $model->attributes = $_POST['Objects'];
            $model->collection_id = $ci;

            $transaction = Yii::app()->db->beginTransaction();

            try {
                if ($model->save()) {
                    $transaction->commit();
                    $this->redirect(array('collections/view', 'id' => $ci));
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

        $Collection = Collections::model()->findByPk($ci);

        // параметры страницы
        $this->pageTitle = array($Collection->name, Yii::t('objects', 'Создание объекта'));
        $this->breadcrumbs = array($Collection->name => array('collections/view', 'id' => $ci), Yii::t('objects', 'Создание объекта'));
        $this->pageName = Yii::t('objects', 'Создание объекта');

		$this->render('create',array(
			'model' => $model,
            'photoUploadModel' => $PhotoUploadModel,
            'view' => $view
		));
	}

    public function filterForActionCreate($filterChain)
    {
        $collectionId = Yii::app()->request->getQuery('ci');

        $Collection = Collections::model()->findByPk($collectionId);

        if (empty($collectionId) || empty($Collection) || $Collection->temporary == 1) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Objects the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
        if (!empty($id)) {
            if (empty($this->model)) {
                $this->model = Objects::model()->findByPk($id);

                if (empty($this->model)) {
                    throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
                }
            }

            return $this->model;
        } else {
            return null;
        }
	}

    /**
     * Возвращает модель коллекции, к которой принадлежит объект
     * @param $id
     * @return array|mixed|null
     * @throws ObjectsControllerException
     */
    public function loadCollectionModel($id)
    {
        if (!empty($id)) {
            if (empty($this->collectionModel)) {

                $Object = $this->loadModel($id);
                $Collection = $Object->collection;

                if (empty($Collection)) {
                    throw new ObjectsControllerException();
                }

                $this->collectionModel = $Collection;
            }

            return $this->collectionModel;
        } else {
            return null;
        }
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
}
