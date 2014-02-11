<?php

class ImagesController extends Controller
{
    private $image;
    private $object;
    private $collection;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'forActionCreate + create',
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
            /*array('allow',
                'actions' => array('create'),
                'roles' => array('oObjectCreate'),
            ),*/
            array('allow',
                'actions' => array('view'),
                'roles' => array(
                    'oImageView' => array(
                        'Collection' => $this->loadCollection(Yii::app()->request->getQuery('id'))
                    )
                ),
            ),
            /*array('allow',
                'actions' => array('update'),
                'roles' => array('oObjectEdit'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('oObjectDelete'),
            ),*/
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

	/**
     * Создание объекта
	 */
	/*public function actionCreate($ci)
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
	}*/

    /*public function filterForActionCreate($filterChain)
    {
        $collectionId = Yii::app()->request->getQuery('ci');

        $Collection = Collections::model()->findByPk($collectionId);

        if (empty($Collection) || $Collection->temporary == 1) {
            throw new ObjectsControllerException();
        }

        $filterChain->run();
    }*/


    public function loadImage($id)
    {
        if (empty($id)) {
            return null;
        }

        if (empty($this->image)) {

            $this->image = Images::model()->findByPk($id);

            if (empty($this->image)) {
                throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
            }
        }

        return $this->image;
    }

    /**
     * Возвращает объект, к которому принадлежит изображение
     * @param int $id айди изображения
     * @return Objects|null
     * @throws ObjectsControllerException
     */
    public function loadObject($id)
	{
        if (empty($id)) {
            return null;
        }

        if (empty($this->object)) {

            $Image = $this->loadImage($id);

            $this->object = $Image->object;

            if (empty($this->object)) {
                throw new ObjectsControllerException();
            }
        }

        return $this->object;
	}

    /**
     * Возвращает коллекцию, к которой принадлежит объект, к которому принадлежит изображение
     * @param int $id айди изображения
     * @return Collections|null
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
     * Просмотр изображения
     * @param int $id айди изображения
     */
    public function actionView($id)
    {
        $Image = $this->loadImage($id);
        $Object = $this->loadObject($id);
        $Collection = $this->loadCollection($id);

        $imageName = $Image->width.' х '.$Image->height.' ['.$Image->dpi.']';

        // параметры страницы
        $this->pageTitle = array($Collection->name, $Object->name, $imageName);
        $this->breadcrumbs = array(
            $Collection->name => array('collections/view', 'id' => $Collection->id),
            $Object->name => array('objects/view', 'id' => $Object->id),
            $imageName
        );
        //$this->pageName = $imageName;

        $pageMenu = array();

        /*if (Yii::app()->user->checkAccess('oImageEdit')) {
            $pageMenu[] = array(
                'label' => Yii::t('images', 'Редактировать изображение'),
                'url' => $this->createUrl('update', array('id' => $id)),
            );
        }
        if (Yii::app()->user->checkAccess('oImageDelete')) {
            $pageMenu[] = array(
                'label' => Yii::t('images', 'Удалить изображение'),
                'url' => $this->createUrl('delete', array('id' => $id)),
                'itemOptions' => array(
                    'class' => '_deleteObject',
                    'data-dialog-title' => CHtml::encode(Yii::t('objects', 'Удалить объект?')),
                    'data-dialog-message' => CHtml::encode(Yii::t('objects', 'Вы уверены, что хотите удалить объект? Его нельзя будет восстановить!')),
                )
            );
        }*/

        $this->pageMenu = $pageMenu;

        $this->render(
            'view',
            array(
                'Image' => $Image,
                'imageName' => $imageName
            )
        );
    }

    /**
     * Удаляет объект
     * @param integer $id айди объекта
     */
    /*public function actionDelete($id)
    {
        $Collection = $this->loadCollection($id);

        if (DeleteHelper::deleteObjectFromNormalCollection($id)) {
            Yii::app()->user->setFlash(
                'success',
                Yii::t('objects', 'Объект удален')
            );
            $this->redirect(array('collections/view', 'id' => $Collection->id));
        } else {
            Yii::app()->user->setFlash(
                'error',
                Yii::t('objects', 'Объект не удален. У объекта не должно быть относящихся к нему изображений, чтобы его можно было удалить')
            );
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }*/

    /**
     * Редактирование объекта
     * @param int $id айди объекта
     * @throws Exception
     */
    /*public function actionUpdate($id)
    {
        $Object = $this->loadObject($id);
        $Collection = $this->loadCollection($id);

        Yii::import( "xupload.models.XUploadForm" );
        $PhotoUploadModel = new MyXUploadForm;

        $view = '_formObject';

        if(isset($_POST['Objects']))
        {
            $movePreviews = false;
            if (!empty($_POST['Objects']['code']) && $Object->code != $_POST['Objects']['code']) {
                $oldObject = clone $Object;
                $movePreviews = true;
            }

            $Object->attributes = $_POST['Objects'];

            $transaction = Yii::app()->db->beginTransaction();

            try {
                if ($Object->save()) {
                    if ($movePreviews) {
                        PreviewHelper::changePreviewPath($oldObject, $_POST['Objects']['code']);
                    }
                    $transaction->commit();
                    $this->redirect(array('view','id'=>$Object->id));
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
        $this->pageTitle = array($Collection->name, $Object->name, Yii::t('objects', 'Редактирование объекта'));
        $this->breadcrumbs = array($Collection->name => array('collections/view', 'id' => $Collection->id), $Object->name => array('objects/view', 'id' => $Object->id), Yii::t('objects', 'Редактирование объекта'));
        $this->pageName = $Object->name;

        $this->render('update',array(
            'Object' => $Object,
            'photoUploadModel' => $PhotoUploadModel,
            'view' => $view
        ));
    }*/
}
