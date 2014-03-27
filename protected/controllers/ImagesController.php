<?php
/**
 * Контроллер действий с изображениями
 */

class ImagesController extends Controller
{
    private $_image;
    private $_object;
    private $_collection;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
            'forActionView + view',
            'forActionUpdate + update',
            'forActionDelete + delete',
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
                'roles' => array('oImageCreate'),
            ),
            array('allow',
                'actions' => array('view'),
                'roles' => array(
                    'oImageView' => array(
                        'Collection' => $this->loadCollection(Yii::app()->request->getQuery('id'))
                    )
                ),
            ),
            array('allow',
                'actions' => array('update'),
                'roles' => array('oImageEdit'),
            ),

            array('allow',
                'actions' => array('delete'),
                'roles' => array('oImageDelete'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function loadImage($id)
    {
        if (empty($id)) {
            return null;
        }

        if (empty($this->_image)) {

            $this->_image = Images::model()->findByPk($id);

            if (empty($this->_image)) {
                throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
            }
        }

        return $this->_image;
    }

    /**
     * Возвращает объект, к которому принадлежит изображение
     * @param int $id айди изображения
     * @return Objects
     * @throws CException
     */
    public function loadObject($id)
	{
        if (empty($id)) {
            return null;
        }

        if (empty($this->_object)) {

            $Image = $this->loadImage($id);
            $this->_object = $Image->object;

            if (empty($this->_object)) {
                throw new CException();
            }
        }

        return $this->_object;
	}

    /**
     * Возвращает коллекцию, к которой принадлежит объект, к которому принадлежит изображение
     * @param int $id айди изображения
     * @return Collections
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
     * Просмотр изображения
     * @param int $id айди изображения
     */
    public function actionView($id)
    {
        $Image = $this->loadImage($id);
        $this->setPageParamsForActionView($id);

        $this->render('view', array(
            'Image' => $Image,
            'attributesForMainDetailViewWidget' => $this->getAttributesForMainDetailViewWidget($Image),
            'attributesForSystemDetailViewWidget' => $this->getAttributesForSystemDetailViewWidget($Image)
        ));
    }

    public function filterForActionView($filterChain)
    {
        $Image = $this->loadImage(Yii::app()->request->getQuery('id'));

        if (empty($Image)) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

    /**
     * Устанавливает параметры страницы просмотра изображения (тайтл, хлебные крошки, заголовок, меню)
     * @param int $id айди изображения
     */
    private function setPageParamsForActionView($id)
    {
        $Image = $this->loadImage($id);
        $Object = $this->loadObject($id);
        $Collection = $this->loadCollection($id);

        // параметры страницы
        $this->pageTitle = array($Collection->name, $Object->name, $Image->name);
        $this->breadcrumbs = array(
            $Collection->name => Yii::app()->urlManager->createNormalCollectionUrl($Collection),
            $Object->name => Yii::app()->urlManager->createObjectUrl($Object),
            $Image->name
        );
        $this->pageName = $Image->name;

        $pageMenu = array();
        if (Yii::app()->user->checkAccess('oImageEdit')) {
            $pageMenu[] = array(
                'label' => Yii::t('images', 'Редактировать изображение'),
                'url' => $this->createUrl('update', array('id' => $id)),
                'iconType' => 'edit'
            );
        }

        if (Yii::app()->user->checkAccess('oImageDelete')) {
            $pageMenu[] = array(
                'label' => Yii::t('images', 'Удалить изображение'),
                'url' => $this->createUrl('delete', array('id' => $id)),
                'itemOptions' => array(
                    'class' => '_deleteImage',
                    'data-dialog-title' => Yii::t('images', 'Удалить изображение?'),
                    'data-dialog-message' => Yii::t('images', 'Вы уверены, что хотите удалить изображение? Его нельзя будет восстановить!'),
                ),
                'iconType' => 'delete'
            );
        }
        $this->pageMenu = $pageMenu;
    }

    /**
     * Возвращает массив с данными для виджета TbDetailView с основной информацией, который используется при рендеринге
     * страницы изображения
     * @param Images $Image модель изображения
     * @return array массив с данными для виджета TbDetailView
     */
    private function getAttributesForMainDetailViewWidget($Image)
    {
        $attributes = array();

        $attributes[] = array(
            'label' => $Image->getAttributeLabel('request'),
            'value' => CHtml::encode(!empty($Image->request) ? $Image->request : '')
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('description'),
            'value' => CHtml::encode(!empty($Image->description) ? $Image->description : '')
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('original'),
            'value' => CHtml::encode(!empty($Image->original) ? $Image->original : '')
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('source'),
            'value' => CHtml::encode(!empty($Image->source) ? $Image->source : '')
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('deepzoom'),
            'value' => CHtml::encode($Image->deepzoom ? Yii::t('common', 'Да') : Yii::t('common', 'Нет'))
        );

        return $attributes;
    }

    /**
     * Возвращает массив с данными для виджета TbDetailView с системной информацией, который используется при рендеринге
     * страницы изображения
     * @param Images $Image модель изображения
     * @return array массив с данными для виджета TbDetailView
     */
    private function getAttributesForSystemDetailViewWidget($Image)
    {
        $attributes = array();
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('width'),
            'value' => CHtml::encode(!empty($Image->width_cm) ? OutputHelper::formatSize($Image->width_cm) : ''),
            'cssClass' => 'detailViewNowrap'
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('height'),
            'value' => CHtml::encode(!empty($Image->height_cm) ? OutputHelper::formatSize($Image->height_cm) : ''),
            'cssClass' => 'detailViewNowrap'
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('code'),
            'value' => CHtml::encode(!empty($Image->code) ? $Image->code : ''),
            'cssClass' => 'detailViewNowrap'
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('has_preview'),
            'value' => CHtml::encode($Image->has_preview ? Yii::t('common', 'Да') : Yii::t('common', 'Нет')),
            'cssClass' => 'detailViewNowrap'
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('sort'),
            'value' => CHtml::encode(!empty($Image->sort) ? $Image->sort : ''),
            'cssClass' => 'detailViewNowrap'
        );

        return $attributes;
    }

    /**
     * Создание изображения
     * @param string $oi айди объекта, к которому относится изображение
     * @throws Exception
     */
    public function actionCreate($oi)
    {
        $Object = Objects::model()->findByPk($oi);

        $Image = new Images();

        Yii::import( "xupload.models.XUploadForm" );
        $PhotoUploadModel = new MyXUploadForm;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Images']))
        {
            $Transaction = Yii::app()->db->beginTransaction();

            try {
                $Image->attributes = $_POST['Images'];
                $Image->object_id = $oi;
                if ($Image->save()) {
                    $Transaction->commit();
                    $this->redirect(Yii::app()->urlManager->createObjectUrl($Object));
                } else {
                    $Transaction->rollback();
                    PreviewHelper::clearUserPreviewsUploads();
                }
            } catch (Exception $Exception) {
                $Transaction->rollback();
                PreviewHelper::clearUserPreviewsUploads();
                throw $Exception;
            }
        }

        // параметры страницы
        $this->pageTitle = array($Object->collection->name, $Object->name, Yii::t('images', 'Создание изображения'));
        $this->breadcrumbs = array(
            $Object->collection->name => Yii::app()->urlManager->createNormalCollectionUrl($Object->collection),
            $Object->name => Yii::app()->urlManager->createObjectUrl($Object),
            Yii::t('images', 'Создание изображения')
        );
        $this->pageName = Yii::t('images', 'Создание изображения');

        $this->render('create',array(
            'Image' => $Image,
            'photoUploadModel' => $PhotoUploadModel,
        ));
    }

    public function filterForActionCreate($filterChain)
    {
        $Object = $this->loadImage(Yii::app()->request->getQuery('oi'));

        if (empty($Object)) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

    /**
     * Редактирование изображения
     * @param $id айди изобржаения
     * @throws Exception
     */
    public function actionUpdate($id)
    {
        $Image = $this->loadImage($id);
        $Object = $this->loadObject($id);
        $Collection = $this->loadCollection($id);

        $imageName = $Image->width.' х '.$Image->height.' ['.$Image->dpi.']';

        Yii::import( "xupload.models.XUploadForm" );
        $PhotoUploadModel = new MyXUploadForm;

        if(isset($_POST['Images']))
        {
            $movePreviews = false;
            if (!empty($_POST['Images']['code']) && $Image->code != $_POST['Images']['code']) {
                $oldImage = clone $Image;
                $movePreviews = true;
            }

            $transaction = Yii::app()->db->beginTransaction();

            try {
                $Image->attributes = $_POST['Images'];
                if ($Image->save()) {
                    if ($movePreviews) {
                        PreviewHelper::changePreviewPath($oldImage, $_POST['Images']['code']);
                    }
                    $transaction->commit();
                    $this->redirect(array('view','id'=>$Image->id));
                } else {
                    $transaction->rollback();
                    PreviewHelper::clearUserPreviewsUploads();
                }
            } catch (Exception $Exception) {
                $transaction->rollback();
                PreviewHelper::clearUserPreviewsUploads();
                throw $Exception;
            }
        }

        // параметры страницы
        $this->pageTitle = array($Collection->name, $Object->name, $imageName, Yii::t('images', 'Редактирование изображения'));
        $this->breadcrumbs = array(
            $Collection->name => Yii::app()->urlManager->createNormalCollectionUrl($Collection),
            $Object->name => Yii::app()->urlManager->createObjectUrl($Object),
            $imageName => array('images/view', 'id' => $id),
            Yii::t('images', 'Редактирование изображения')
        );
        $this->pageName = $imageName;

        // рендеринг
        $this->render('update',array(
            'Image' => $Image,
            'photoUploadModel' => $PhotoUploadModel,
        ));
    }

    public function filterForActionUpdate($filterChain)
    {
        $Image = $this->loadImage(Yii::app()->request->getQuery('id'));

        if (empty($Image)) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

    /**
     * Удаление изображения
     * @param $id айди изображения
     */
    public function actionDelete($id)
    {
        $Object = $this->loadObject($id);
        DeleteHelper::deleteImage($id);
        Yii::app()->user->setFlash('success', Yii::t('images', 'Изображение удалено'));
        $this->redirect(Yii::app()->urlManager->createObjectUrl($Object));
    }

    public function filterForActionDelete($filterChain)
    {
        $Image = $this->loadImage(Yii::app()->request->getQuery('id'));

        if (empty($Image)) {
            throw new CHttpException(404, Yii::t('common', 'Запрашиваемая Вами страница недоступна!'));
        }

        $filterChain->run();
    }

}
