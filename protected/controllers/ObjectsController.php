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
            array('allow',
                'actions' => array('update'),
                'roles' => array('oObjectEdit'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('oObjectDelete'),
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
            $transaction = Yii::app()->db->beginTransaction();

            try {
                $model->attributes = $_POST['Objects'];
                $model->collection_id = $ci;
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

        if (empty($Collection) || $Collection->temporary == 1) {
            throw new ObjectsControllerException();
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
     * Просмотр объекта
     * @param string $id айди объекта
     * @param string $iv как отображать изображения: th - картинками, ls - списком, tb - таблицей
     * @throws ObjectsControllerException
     */
    public function actionView($id, $iv = 'th')
    {
        try {
            $ImagesDataProvider = new CActiveDataProvider('Images', array(
                'criteria' => array(
                    'condition' => 'object_id = :object_id',
                    'params' => array(':object_id' => $id),
                    'with' => array('photoType'),
                ),
            ));
            $this->setPageParamsForActionView($id);
            $this->render('view', array(
                'Object' => $this->loadObject($id),
                'renderViewImages' => $this->getObjectsImagesViewName($iv),
                'ImagesDataProvider' => $ImagesDataProvider,
                'attributesForDetailViewWidget' => $this->getAttributesForTbDetailViewWidget($id),
            ));
        } catch (ObjectsControllerException $Exception) {
            throw $Exception;
        } catch (Exception $Exception) {
            throw new ObjectsControllerException($Exception);
        }
    }

    /**
     * Возвращает массив с данными для виджета TbDetailView, который используется при рендеринге страницы объекта
     * @param string $id айди объекта
     * @return array массив с данными для виджета TbDetailView
     * @throws ObjectsControllerException
     */
    private function getAttributesForTbDetailViewWidget($id)
    {
        try {
            $Object = $this->loadObject($id);
            $attributes = array(
                array(
                    'label' => $Object->getAttributeLabel('author_id'),
                    'value' => !empty($Object->author->initials) ? CHtml::encode($Object->author->initials) : ''
                ),
                array(
                    'label' => $Object->getAttributeLabel('type_id'),
                    'value' => !empty($Object->type->name) ? CHtml::encode($Object->type->name) : ''
                ),
                array(
                    'label' => $Object->getAttributeLabel('description'),
                    'value' => !empty($Object->description) ? CHtml::encode($Object->description) : ''
                ),
                array(
                    'label' => $Object->getAttributeLabel('inventory_number'),
                    'value' => !empty($Object->inventory_number) ? CHtml::encode($Object->inventory_number) : ''
                ),
                array(
                    'label' => $Object->getAttributeLabel('code'),
                    'value' => !empty($Object->code) ? CHtml::encode($Object->code) : ''
                ),
                array(
                    'label' => $Object->getAttributeLabel('width'),
                    'value' => $Object->width === '0.00' ? CHtml::encode(floatval($Object->width).' '.Yii::t('common', 'см')) : ''
                ),
                array(
                    'label' => $Object->getAttributeLabel('height'),
                    'value' => $Object->width === '0.00' ? CHtml::encode(floatval($Object->height).' '.Yii::t('common', 'см')) : ''
                ),
                array(
                    'label' => $Object->getAttributeLabel('depth'),
                    'value' => $Object->width === '0.00' ? CHtml::encode(floatval($Object->depth).' '.Yii::t('common', 'см')) : ''
                ),
                array(
                    'label' => $Object->getAttributeLabel('has_preview'),
                    'value' => !empty($Object->has_preview) ? CHtml::encode(Yii::t('common', 'Да')) : CHtml::encode(Yii::t('common', 'Нет'))
                ),
                array(
                    'label' => $Object->getAttributeLabel('department'),
                    'value' => !empty($Object->department) ? CHtml::encode($Object->department) : ''
                ),
                array(
                    'label' => $Object->getAttributeLabel('keeper'),
                    'value' => !empty($Object->keeper) ? CHtml::encode($Object->keeper) : ''
                ),
                array(
                    'label' => $Object->getAttributeLabel('period'),
                    'value' => !empty($Object->period) ? CHtml::encode($Object->period) : ''
                ),
                array(
                    'label' => $Object->getAttributeLabel('sort'),
                    'value' => !empty($Object->sort) ? CHtml::encode($Object->sort) : ''
                ),
            );
            return $attributes;
        } catch (ObjectsControllerException $Exception) {
            throw $Exception;
        } catch (Exception $Exception) {
            throw new ObjectsControllerException($Exception);
        }
    }

    /**
     * Возвращает название вьюхи, с помощью которой надо рендерить изображений объекта
     * @param string $iv GET-параметр, определающий, как отображать изображения: th - картинками, ls - списком, tb - таблицей
     * @return string имя вьюхи для рендеринга изображений объекта
     */
    private function getObjectsImagesViewName($iv)
    {
        switch ($iv) {
            case 'th': // картинками
                $objectsImagesViewName = '_viewImagesThumbnails';
                break;
            case 'ls': // списком
                $objectsImagesViewName = '_viewImagesList';
                break;
            case 'tb': // таблицей
                $objectsImagesViewName = '_viewImagesTable';
                break;
            default: // картинками
                $objectsImagesViewName = '_viewImagesThumbnails';
        }
        return $objectsImagesViewName;
    }

    /**
     * Устанавливает параметры страницы просмотра объекта (тайтл, хлебные крошки, заголовок, меню)
     * @param string $id айди объекта
     * @throws ObjectsControllerException
     */
    private function setPageParamsForActionView($id)
    {
        try {
            $Object = $this->loadObject($id);
            $Collection = $this->loadCollection($id);

            $this->pageTitle = array($Collection->name, $Object->name);
            $this->breadcrumbs = array($Collection->name => array('collections/view', 'id' => $Collection->id), $Object->name);
            $this->pageName = $Object->name;

            $pageMenu = array();
            if (Yii::app()->user->checkAccess('oObjectEdit')) {
                $pageMenu[] = array(
                    'label' => Yii::t('objects', 'Редактировать объект'),
                    'url' => $this->createUrl('update', array('id' => $id)),
                );
            }
            if (Yii::app()->user->checkAccess('oObjectDelete')) {
                $pageMenu[] = array(
                    'label' => Yii::t('objects', 'Удалить объект'),
                    'url' => $this->createUrl('delete', array('id' => $id)),
                    'itemOptions' => array(
                        'class' => '_deleteObject',
                        'data-dialog-title' => CHtml::encode(Yii::t('objects', 'Удалить объект?')),
                        'data-dialog-message' => CHtml::encode(Yii::t('objects', 'Вы уверены, что хотите удалить объект? Его нельзя будет восстановить!')),
                    )
                );
            }
            $tempCollections = Collections::getTempCollectionsAllowedToUser(Yii::app()->user->id);
            if (!empty($tempCollections)) {
                $pageMenu[] = array(
                    'label' => Yii::t('objects', 'Добавить объект во временную коллекцию'),
                    'url' => '#',
                    'itemOptions' => array(
                        'class' => '_addObjectToTempCollection',
                        'data-dialog-title' => CHtml::encode(Yii::t('objects', 'Выберите временную коллекцию, в которую хотите добавить объект')),
                        'data-dialog-message' => CHtml::encode($this->renderPartial('_tempCollectionsSelect', array('Object' => $Object, 'tempCollections' => $tempCollections), true)),
                    )
                );
            }
            if (Yii::app()->user->checkAccess('oChangeObjectsCollection')) {
                $collectionsToMoveTo = Collections::getAllNormalCollectionsExcept($Collection->id);
                $pageMenu[] = array(
                    'label' => Yii::t('objects', 'Переместить объект в другую коллекцию'),
                    'url' => '#',
                    'itemOptions' => array(
                        'class' => '_moveObjectToOtherCollection',
                        'data-dialog-title' => CHtml::encode(Yii::t('objects', 'Выберите коллекцию, в которую хотите переместить объект/объекты')),
                        'data-dialog-message' => CHtml::encode($this->renderPartial('_collectionsToMoveToSelect', array('Object' => $Object, 'collectionsToMoveTo' => $collectionsToMoveTo), true)),
                    )
                );
            }
            if (Yii::app()->user->checkAccess('oImageCreate')) {
                $pageMenu[] = array(
                    'label' => Yii::t('images', 'Создать изображение'),
                    'url' => $this->createUrl('images/create', array('oi' => $id)),
                );
            }
            $this->pageMenu = $pageMenu;
        } catch (ObjectsControllerException $Exception) {
            throw $Exception;
        } catch (Exception $Exception) {
            throw new ObjectsControllerException($Exception);
        }
    }

    /**
     * Удаляет объект
     * @param integer $id айди объекта
     */
    public function actionDelete($id)
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
    }

    /**
     * Редактирование объекта
     * @param int $id айди объекта
     * @throws Exception
     */
    public function actionUpdate($id)
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
    }
}
