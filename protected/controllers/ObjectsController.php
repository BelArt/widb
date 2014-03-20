<?php

class ObjectsController extends Controller
{
    private $_object;
    private $_collection;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'forActionCreate + create',
            'forActionView + view',
            'forActionDelete + delete',
            'forActionUpdate + update',
            array(
                'application.components.SaveGetParamsToSessionFilter + view',
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
     * Создание объекта.
     *
     * @param int $ci айди коллекции, в которой создается объект
     * @throws Exception
	 */
	public function actionCreate($ci)
	{
		$model = new Objects();

        Yii::import( "xupload.models.XUploadForm" );
        $PhotoUploadModel = new MyXUploadForm;

        $view = '_formObject';

        $Collection = Collections::model()->findByPk($ci);

        if(isset($_POST['Objects']))
        {
            $transaction = Yii::app()->db->beginTransaction();

            try {
                $model->attributes = $_POST['Objects'];
                $model->collection_id = $ci;
                if ($model->save()) {
                    $transaction->commit();
                    $this->redirect(Yii::app()->urlManager->createNormalCollectionUrl($Collection));
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
        $this->breadcrumbs = array($Collection->name => Yii::app()->urlManager->createNormalCollectionUrl($Collection), Yii::t('objects', 'Создание объекта'));
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
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

    /**
     * Возвращает модель объекта
     * @param int $id айди объекта
     * @return Objects модель объекта
     * @throws CHttpException
     */
    public function loadObject($id)
	{
        if (empty($id)) {
            return null;
        }

        if (empty($this->_object)) {
            $this->_object = Objects::model()->findByPk($id);

            if (empty($this->_object)) {
                throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
            }
        }

        return $this->_object;
	}

    /**
     * Возвращает модель коллекции, к которой принадлежит объект
     * @param int $id айди объекта
     * @return Collections модель коллекции, к которой принадлежит объект
     * @throws CException
     */
    public function loadCollection($id)
    {
        if (empty($id)) {
            return null;
        }

        if (empty($this->_collection)) {
            $Object = $this->loadObject($id);
            $this->_collection = $Object->collection;

            if (empty($this->_collection)) {
                throw new CException();
            }
        }

        return $this->_collection;
    }

    /**
     * Просмотр объекта
     * @param string $id айди объекта
     * @param string $iv как отображать изображения: th - картинками, ls - списком, tb - таблицей
     */
    public function actionView($id, $iv = 'th')
    {
        $Object = $this->loadObject($id);

        $this->setPageParamsForActionView($id);

        $this->render('view', array(
            'Object' => $Object,
            'renderViewImages' => $this->getObjectsImagesViewName($iv),
            'ImagesDataProvider' => $this->getImagesDataProviderForActionView($id),
            'attributesForMainDetailViewWidget' => $this->getAttributesForMainDetailViewWidget($Object),
            'attributesForSystemDetailViewWidget' => $this->getAttributesForSystemDetailViewWidget($Object),
        ));
    }

    public function filterForActionView($filterChain)
    {
        /*
         * Проверяем первый параметр - айди объекта
         * если чот-то не так, что будет брошено исключение в методе loadObject()
         * Заодно подгрузим модель объекта
         */
        $this->loadObject(Yii::app()->request->getQuery('id'));

        // тип отображения изображений
        if (!in_array(Yii::app()->request->getQuery('iv',''), array('th','ls','tb',''))) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

    /**
     * Возвращает датапровайдер изображений объекта.
     *
     * @param int $id айди объекта
     * @return CActiveDataProvider датапровайдер изображений объекта
     */
    private function getImagesDataProviderForActionView($id)
    {
        $ImagesDataProvider = new CActiveDataProvider('Images', array(
            'criteria' => array(
                'condition' => 'object_id = :object_id',
                'params' => array(':object_id' => $id),
                'with' => array('photoType'),
            ),
            'pagination' => array(
                'pageVar' => 'p',
            ),
        ));

        return $ImagesDataProvider;
    }

    /**
     * Возвращает массив с данными для виджета TbDetailView с основной информацией, который используется при рендеринге
     * страницы объекта
     * @param Objects $Object модель объекта
     * @return array массив с данными для виджета TbDetailView
     */
    private function getAttributesForMainDetailViewWidget($Object)
    {
        $attributes = array(
            array(
                'label' => $Object->getAttributeLabel('inventory_number'),
                'value' => !empty($Object->inventory_number) ? CHtml::encode($Object->inventory_number) : ''
            ),
            array(
                'label' => $Object->getAttributeLabel('type_id'),
                'value' => !empty($Object->type->name) ? CHtml::encode($Object->type->name) : ''
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
                'label' => $Object->getAttributeLabel('description'),
                'value' => !empty($Object->description) ? CHtml::encode($Object->description) : ''
            ),
        );

        return $attributes;
    }

    /**
     * Возвращает массив с данными для виджета TbDetailView с системной информацией, который используется при рендеринге
     * страницы объекта
     * @param Objects $Object модель объекта
     * @return array массив с данными для виджета TbDetailView
     */
    private function getAttributesForSystemDetailViewWidget($Object)
    {
        $attributes = array(
            array(
                'label' => $Object->getAttributeLabel('width'),
                'value' => $Object->width !== '0.00' ? CHtml::encode(OutputHelper::formatSize($Object->width)) : '',
                'cssClass' => 'detailViewNowrap'
            ),
            array(
                'label' => $Object->getAttributeLabel('height'),
                'value' => $Object->height !== '0.00' ? CHtml::encode(OutputHelper::formatSize($Object->height)) : '',
                'cssClass' => 'detailViewNowrap'
            ),
            array(
                'label' => $Object->getAttributeLabel('depth'),
                'value' => $Object->depth !== '0.00' ? CHtml::encode(OutputHelper::formatSize($Object->depth)) : '',
                'cssClass' => 'detailViewNowrap'
            ),
            array(
                'label' => $Object->getAttributeLabel('code'),
                'value' => !empty($Object->code) ? CHtml::encode($Object->code) : '',
                'cssClass' => 'detailViewNowrap'
            ),
            array(
                'label' => $Object->getAttributeLabel('has_preview'),
                'value' => !empty($Object->has_preview) ? CHtml::encode(Yii::t('common', 'Да')) : CHtml::encode(Yii::t('common', 'Нет')),
                'cssClass' => 'detailViewNowrap'
            ),
            array(
                'label' => $Object->getAttributeLabel('sort'),
                'value' => !empty($Object->sort) ? CHtml::encode($Object->sort) : '',
                'cssClass' => 'detailViewNowrap'
            ),
        );

        return $attributes;
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
        $Object = $this->loadObject($id);
        $Collection = $this->loadCollection($id);

        $this->pageTitle = array($Collection->name, $Object->name);
        $this->breadcrumbs = array($Collection->name => Yii::app()->urlManager->createNormalCollectionUrl($Collection), $Object->name);
        $this->pageName = $Object->name;

        $pageMenu = array();
        $tempCollections = Collections::getTempCollectionsAllowedToUser(Yii::app()->user->id);
        if (!empty($tempCollections)) {
            $pageMenu[] = array(
                'label' => Yii::t('objects', 'Добавить объект во временную коллекцию'),
                'url' => '#',
                'itemOptions' => array(
                    'class' => '_addObjectToTempCollection',
                    'data-dialog-title' => Yii::t('objects', 'Выберите временную коллекцию, в которую хотите добавить объект'),
                    'data-dialog-message' => $this->renderPartial('_tempCollectionsSelect', array('Object' => $Object, 'tempCollections' => $tempCollections), true),
                ),
                'iconType' => 'add_to_temp'
            );
        }
        if (Yii::app()->user->checkAccess('oChangeObjectsCollection')) {
            $collectionsToMoveTo = Collections::getAllNormalCollectionsExcept($Collection->id);
            $pageMenu[] = array(
                'label' => Yii::t('objects', 'Переместить объект в другую коллекцию'),
                'url' => '#',
                'itemOptions' => array(
                    'class' => '_moveObjectToOtherCollection',
                    'data-dialog-title' => Yii::t('objects', 'Выберите коллекцию, в которую хотите переместить объект/объекты'),
                    'data-dialog-message' => $this->renderPartial('_collectionsToMoveToSelect', array('Object' => $Object, 'collectionsToMoveTo' => $collectionsToMoveTo), true),
                ),
                'iconType' => 'move'
            );
        }
        if (Yii::app()->user->checkAccess('oObjectEdit')) {
            $pageMenu[] = array(
                'label' => Yii::t('objects', 'Редактировать объект'),
                'url' => $this->createUrl('update', array('id' => $id)),
                'iconType' => 'edit'
            );
        }
        if (Yii::app()->user->checkAccess('oObjectDelete')) {
            $pageMenu[] = array(
                'label' => Yii::t('objects', 'Удалить объект'),
                'url' => $this->createUrl('delete', array('id' => $id)),
                'itemOptions' => array(
                    'class' => '_deleteObject',
                    'data-dialog-title' => Yii::t('objects', 'Удалить объект?'),
                    'data-dialog-message' => Yii::t('objects', 'Вы уверены, что хотите удалить объект? Его нельзя будет восстановить!'),
                ),
                'iconType' => 'delete'
            );
        }
        if (Yii::app()->user->checkAccess('oImageCreate')) {
            $pageMenu[] = array(
                'label' => Yii::t('images', 'Создать изображение'),
                'url' => $this->createUrl('images/create', array('oi' => $id)),
                'iconType' => 'create'
            );
        }
        $this->pageMenu = $pageMenu;
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
            $this->redirect(Yii::app()->urlManager->createNormalCollectionUrl($Collection));
        } else {
            Yii::app()->user->setFlash(
                'error',
                Yii::t('objects', 'Объект не удален. У объекта не должно быть относящихся к нему изображений, чтобы его можно было удалить')
            );
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    public function filterForActionDelete($filterChain)
    {
        /*
         * Проверяем первый параметр - айди объекта
         * если чот-то не так, что будет брошено исключение в методе loadObject()
         * Заодно подгрузим модель объекта
         */
        $this->loadObject(Yii::app()->request->getQuery('id'));

        $filterChain->run();
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
                    $this->redirect(Yii::app()->urlManager->createObjectUrl($Object));
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
        $this->breadcrumbs = array($Collection->name => Yii::app()->urlManager->createNormalCollectionUrl($Collection), $Object->name => Yii::app()->urlManager->createObjectUrl($Object), Yii::t('objects', 'Редактирование объекта'));
        $this->pageName = $Object->name;

        $this->render('update',array(
            'Object' => $Object,
            'photoUploadModel' => $PhotoUploadModel,
            'view' => $view
        ));
    }

    public function filterForActionUpdate($filterChain)
    {
        /*
         * Проверяем первый параметр - айди объекта
         * если чот-то не так, что будет брошено исключение в методе loadObject()
         * Заодно подгрузим модель объекта
         */
        $this->loadObject(Yii::app()->request->getQuery('id'));

        $filterChain->run();
    }
}
