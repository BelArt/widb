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
     * @throws ImagesControllerException
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
                throw new ImagesControllerException();
            }
        }

        return $this->object;
	}

    /**
     * Возвращает коллекцию, к которой принадлежит объект, к которому принадлежит изображение
     * @param int $id айди изображения
     * @return Collections|null
     * @throws ImagesControllerException
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
                throw new ImagesControllerException();
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

        $imageName = $Image->width.' х '.$Image->height.' '.Yii::t('common', 'px').' ['.$Image->dpi.' '.Yii::t('common', 'dpi').']';

        $attributes = array();
        //$attributes[] = array('label' => $Image->getAttributeLabel('object_id'), 'value' => CHtml::encode($Image->object->name));
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('date_photo'),
            'value' => CHtml::encode($Image->date_photo != '0000-00-00' ? Yii::app()->dateFormatter->formatDateTime(strtotime($Image->date_photo), 'medium', null) : '')
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('photo_type_id'),
            'value' => CHtml::encode(!empty($Image->photoType->name) ? $Image->photoType->name : '')
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('description'),
            'value' => CHtml::encode(!empty($Image->description) ? $Image->description : '')
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('width'),
            'value' => CHtml::encode(!empty($Image->width) ? $Image->width.' '.Yii::t('common', 'px') : '')
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('height'),
            'value' => CHtml::encode(!empty($Image->height) ? $Image->height.' '.Yii::t('common', 'px') : '')
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('dpi'),
            'value' => CHtml::encode(!empty($Image->dpi) ? $Image->dpi : '')
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
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('request'),
            'value' => CHtml::encode(!empty($Image->request) ? $Image->request : '')
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('code'),
            'value' => CHtml::encode(!empty($Image->code) ? $Image->code : '')
        );
        $attributes[] = array(
            'label' => $Image->getAttributeLabel('sort'),
            'value' => CHtml::encode(!empty($Image->sort) ? $Image->sort : '')
        );

        // параметры страницы
        $this->pageTitle = array($Collection->name, $Object->name, $imageName);
        $this->breadcrumbs = array(
            $Collection->name => array('collections/view', 'id' => $Collection->id),
            $Object->name => array('objects/view', 'id' => $Object->id),
            $imageName
        );
        $this->pageName = $imageName;

        $pageMenu = array();

        if (Yii::app()->user->checkAccess('oImageEdit')) {
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
                    'class' => '_deleteImage',
                    'data-dialog-title' => CHtml::encode(Yii::t('images', 'Удалить изображение?')),
                    'data-dialog-message' => CHtml::encode(Yii::t('images', 'Вы уверены, что хотите удалить изображение? Его нельзя будет восстановить!')),
                )
            );
        }

        $this->pageMenu = $pageMenu;

        $this->render(
            'view',
            array(
                'Image' => $Image,
                'imageName' => $imageName,
                'attributes' => $attributes
            )
        );
    }

    /**
     * Создание изображения
     * @param string $oi айди объекта, к которому относится изображение
     * @throws ImagesControllerException
     */
    public function actionCreate($oi)
    {
        $Object = Objects::model()->findByPk($oi);
        if (empty($Object)) {
            throw new ImagesControllerException();
        }

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
                    $this->redirect(array('objects/view', 'id' => $oi));
                } else {
                    $Transaction->rollback();
                    PreviewHelper::clearUserPreviewsUploads();
                }
            } catch (Exception $Exception) {
                $Transaction->rollback();
                PreviewHelper::clearUserPreviewsUploads();
                throw new ImagesControllerException($Exception);
            }
        }

        // параметры страницы
        $this->pageTitle = array($Object->collection->name, $Object->name, Yii::t('images', 'Создание изображения'));
        $this->breadcrumbs = array(
            $Object->collection->name => array('collections/view', 'id' => $Object->collection->id),
            $Object->name => array('objects/view', 'id' => $oi),
            Yii::t('images', 'Создание изображения')
        );
        $this->pageName = Yii::t('images', 'Создание изображения');

        $this->render('create',array(
            'Image' => $Image,
            'photoUploadModel' => $PhotoUploadModel,
        ));
    }

    /**
     * Редактирование изображения
     * @param $id айди изобржаения
     * @throws ImagesControllerException
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
            } catch (ImagesControllerException $Exception) {
                $transaction->rollback();
                PreviewHelper::clearUserPreviewsUploads();
                throw $Exception;
            } catch (Exception $e) {
                $transaction->rollback();
                PreviewHelper::clearUserPreviewsUploads();
                throw new ImagesControllerException($e);
            }
        }

        // параметры страницы
        $this->pageTitle = array($Collection->name, $Object->name, $imageName, Yii::t('images', 'Редактирование изображения'));
        $this->breadcrumbs = array(
            $Collection->name => array('collections/view', 'id' => $Collection->id),
            $Object->name => array('objects/view', 'id' => $Object->id),
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

    /**
     * Удаление изображения
     * @param $id айди изображения
     * @throws ImagesControllerException
     */
    public function actionDelete($id)
    {
        try {
            $Object = $this->loadObject($id);
            DeleteHelper::deleteImage($id);
            Yii::app()->user->setFlash('success', Yii::t('images', 'Изображение удалено'));
            $this->redirect(array('objects/view', 'id' => $Object->id));
        } catch (ImagesControllerException $Exception) {
            Yii::app()->user->setFlash('success', null);
            throw $Exception;
        } catch (Exception $Exception) {
            Yii::app()->user->setFlash('success', null);
            throw new ImagesControllerException($Exception);
        }
    }

}
