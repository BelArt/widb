<?php

class ObjectsController extends Controller
{
    private $object;
    private $collection;

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
	public function loadObject($id)
	{
        if (empty($id)) {
            return null;
        }

        if (empty($this->object)) {

            $this->object = Objects::model()->findByPk($id);

            if (empty($this->object)) {
                throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
            }
        }

        return $this->object;
	}

    /**
     * Возвращает модель коллекции, к которой принадлежит объект
     * @param $id
     * @return array|mixed|null
     * @throws ObjectsControllerException
     */
    public function loadCollection($id)
    {
        if (empty($id)) {
            return null;
        }

        if (empty($this->collection)) {

            $Object = $this->loadObject($id);

            $this->collection = $Object->collection;

            if (empty($this->collection)) {
                throw new ObjectsControllerException();
            }
        }

        return $this->collection;

    }

    /**
     * Просмотр обычной коллекции
     * @param string $id айди коллекции
     * @param string $iv как отображать дочерние коллекции: th - картинками, ls - списком, tb - таблицей
     */
    public function actionView($id, $iv = 'th')
    {
        $Object = $this->loadObject($id);
        $Collection = $this->loadCollection($id);

        // как отображать дочерние коллекции
        switch ($iv) {
            case 'th': // картинками
                $renderViewImages = '_viewImagesThumbnails';
                break;
            case 'ls': // списком
                $renderViewImages = '_viewImagesList';
                break;
            case 'tb': // таблицей
                $renderViewImages = '_viewImagesTable';
                break;
            default: // картинками
                $renderViewImages = '_viewImagesThumbnails';
        }

        $ImagesDataProvider = new CActiveDataProvider(
            'Images',
            array(
                'criteria' => array(
                    'condition' => 'object_id = :object_id',
                    'params' => array(':object_id' => $id),
                    'with' => array('photoType'),
                ),
            )
        );

        // параметры страницы
        $this->pageTitle = array($Collection->name, $Object->name);
        $this->breadcrumbs = array($Collection->name => array('collections/view', 'id' => $Collection->id), $Object->name);
        $this->pageName = $Object->name;
        $pageMenu = array();
        /*if (Yii::app()->user->checkAccess('oCollectionEdit')) {
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
        }*/
        $this->pageMenu = $pageMenu;

        $this->render(
            'view',
            array(
                'Object' => $Object,
                'renderViewImages' => $renderViewImages,
                'ImagesDataProvider' => $ImagesDataProvider
            )
        );
    }
}
