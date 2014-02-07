<?php

class SiteController extends Controller
{
    public function filters()
    {
        return array(
            'ajaxOnly + ajax',
        );
    }

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
            // @todo убрать
			'page'=>array(
				'class'=>'CViewAction',
			),

            'upload' => array(
                'class'=>'xupload.actions.XUploadAction',
                'path' => Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.Yii::app()->params['filesFolder'].DIRECTORY_SEPARATOR.Yii::app()->params['tempFilesFolder'],
                'publicPath' => Yii::app()->baseUrl.DIRECTORY_SEPARATOR.Yii::app()->params['filesFolder'].DIRECTORY_SEPARATOR.Yii::app()->params['tempFilesFolder'],
                'secureFileNames' => true,
                'stateVariable' => Yii::app()->params['xuploadStatePreviewsName'], // для красоты
                'subfolderVar' => false, // не надо класть временные файлы в подпапки
                'formClass' => 'MyXUploadForm' // используем собственное расширение
            ),
		);


	}

    public function actionIndex()
    {
        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->homeUrl);
        }

        $this->login('index');
    }

	/**
	 * This is the action to handle external exceptions.
	 */
	/*public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if (Yii::app()->request->isAjaxRequest) {
				echo $error['message'];
            } else {

                // параметры страницы
                //$this->pageTitle = array($error['message']);
                //$this->breadcrumbs = array();
                //$this->pageName = $error['message'];

				$this->render('error', $error);
            }
		}
	}*/

	/**
	 * Displays the contact page
     * @todo убрать
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $this->redirect(array('site/index'));
    }

    /**
     * Показывает форму логина
     * @todo Логин через AJAX
     * @param $mode string режим показа формы, определяет, какой лэйаут применяется
     */
    protected function login($mode = null)
    {
        $model=new LoginForm;

        // if it is ajax validation request
        /*if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }*/

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login()) {
                if (Yii::app()->user->returnUrl) {
                    $this->redirect(Yii::app()->user->returnUrl);
                } else {
                    $this->redirect(Yii::app()->homeUrl);
                }
            }
        }

        // параметры страницы
        //$this->pageTitle = Yii::app()->name;
        $this->pageName = 'Web Images Database';

        // применение других лэйаутов
        switch ($mode) {
            case 'index':
                $this->layout = 'empty';
                break;
        }

        $this->render('login',array(
            'model' => $model,
        ));
    }

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(array('site/index'));
	}

    /**
     * Общая точка входа всех AJAX-запросов
     */
    public function actionAjax()
    {
        $action = Yii::app()->request->getParam('action');
        $params = Yii::app()->request->getParam('params');

        switch ($action) {
            // удаляем не сохраненные превью, которые подгрузил пользователь
            case 'clearUserUploads':
                PreviewHelper::clearUserPreviewsUploads();
                break;
            // удаляем сохраненное превью
            case 'deletePreview':
                PreviewHelper::deletePreview($params);
                break;
            // удаляем выбранные объекты из обычной коллекции
            case 'deleteObjects':
                $this->deleteObjectsFromNormalCollection($params);
                break;
            // удаляем выбранные объекты из временной коллекции
            case 'deleteObjectsFromTempCollection':
                $this->deleteObjectsFromTempCollection($params);
                break;
            // удаляем выбранные дочерние коллекции
            case 'deleteChildCollections':
                $this->deleteChildCollections($params);
                break;
            // добавляем объекты во Временную коллекцию
            case 'addObjectsToTempCollection':
                $this->addObjectsToTempCollection($params);
                break;
            // перемещаем объекты в другую коллекцию
            case 'moveObjectsToOtherCollection':
                $this->moveObjectsToOtherCollection($params);
                break;
        }

    }

    /**
     * Перемещает объекты в другую коллекцию
     * @param array $params
     * @throws SiteControllerException
     * @throws Exception
     */
    protected function moveObjectsToOtherCollection(array $params)
    {
        /*
         * всякие проверки
         */

        if (!Yii::app()->user->checkAccess('oChangeObjectsCollection')) {
            throw new SiteControllerException();
        }

        if (empty($params['objectIds']) || empty($params['collectionId'])) {
            throw new SiteControllerException();
        }

        $Collection = Collections::model()->findByPk($params['collectionId']);

        if (empty($Collection) || $Collection->temporary == 1) {
            throw new SiteControllerException();
        }

        $Criteria = new CDbCriteria();
        $Criteria->addInCondition('t.id', $params['objectIds']);

        $objects = Objects::model()->findAll($Criteria);

        // если по каким-то айдшникам объектов не удалось найти запись в БД - например, не все айдишники правильные
        if (count($objects) != count($params['objectIds'])) {
            throw new SiteControllerException();
        }

        /*
         * собственно перемещение
         */

        $Transaction = Yii::app()->db->beginTransaction();

        try {
            foreach ($objects as $Object) {
                $Object->moveToCollection($params['collectionId']);
            }
            $Transaction->commit();
        } catch (Exception $Exception) {
            $Transaction->rollback();
            throw $Exception;
        }

        Yii::app()->user->setFlash(
            'success',
            count($params['objectIds']) == 1
                ? Yii::t('objects', 'Объект перемещен')
                : Yii::t('objects', 'Все объекты перемещены')
        );
    }

    /**
     * Добавляем объекты во Временную коллекцию.
     * Если какие-то объекты из переданных уже есть в этой временной коллекции, то просто их игнорируем
     * @param array $params параметры
     * @throws SiteControllerException
     * @throws Exception
     */
    protected function addObjectsToTempCollection(array $params)
    {
        /*
         * всякие проверки
         */

        if (empty($params['objectIds']) || empty($params['tempCollectionId'])) {
            throw new SiteControllerException();
        }

        $Collection = Collections::model()->findByPk($params['tempCollectionId']);

        if (empty($Collection) || $Collection->temporary == 0) {
            throw new SiteControllerException();
        }

        if (
            !Yii::app()->user->checkAccess(
                'oObjectToTempCollectionAdd_Collection',
                array(
                    'Collection' => $Collection
                )
            )
        ) {
            throw new SiteControllerException();
        }

        $Criteria = new CDbCriteria();
        $Criteria->addInCondition('t.id', $params['objectIds']);

        $objects = Objects::model()->with('collection')->findAll($Criteria);

        // если по каким-то айдшникам объектов не удалось найти запись в БД - например, не все айдишники правильные
        if (count($objects) != count($params['objectIds'])) {
            throw new SiteControllerException();
        }

        // проверим все объекты на доступность юзеру
        foreach ($objects as $Object) {

            if (!Yii::app()->user->checkAccess('oObjectToTempCollectionAdd_Object', array(
                    'Collection' => $Object->collection
            ))) {
                throw new SiteControllerException();
            }
        }

        /*
         * собственно добавление
         */

        $Transaction = Yii::app()->db->beginTransaction();

        try {
            foreach ($objects as $Object) {
                $Object->addToTempCollection($params['tempCollectionId']);
            }
            $Transaction->commit();
        } catch (Exception $Exception) {
            $Transaction->rollback();
            throw $Exception;
        }

        Yii::app()->user->setFlash(
            'success',
            count($params['objectIds']) == 1
                ? Yii::t('objects', 'Объект добавлен в выбранную Временную коллекцию')
                : Yii::t('objects', 'Все объекты добавлены в выбранную Временную коллекцию')
        );
    }

    /**
     * Удаление выбранных дочерних коллекций
     * @param mixed $params параметры
     * @throws CHttpException
     */
    protected function deleteChildCollections($params)
    {
        if (!empty($params['ids'])) {

            if (!Yii::app()->user->checkAccess('oCollectionDelete')) {
                throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
            }

            if (DeleteHelper::deleteNormalCollections($params['ids'])) {
                Yii::app()->user->setFlash(
                    'success',
                    Yii::t('collections', 'Все выбранные дочерние коллекции удалены')
                );
            } else {
                Yii::app()->user->setFlash(
                    'error',
                    Yii::t('collections', 'Не все выбранные дочерние коллекции удалены')
                );
            }
        }
    }

    /**
     * Удаление объектов из временной коллекци
     *
     * @param mixed $params параметры
     * @throws CHttpException
     */
    protected function deleteObjectsFromTempCollection($params)
    {
        if (!empty($params['ids']) && !empty($params['collectionId'])) {

            // проверям права доступа
            foreach ($params['ids'] as $objectId) {
                $Object = Objects::model()->findByPk($objectId);
                $TempCollection = Collections::model()->findByPk($params['collectionId']);

                if (!(
                    Yii::app()->user->checkAccess('oObjectFromTempCollectionDelete_Object', array('Collection' => $Object->collection))
                    && Yii::app()->user->checkAccess('oObjectFromTempCollectionDelete_Collection', array('Collection' => $TempCollection))
                )) {
                    throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
                }
            }

            DeleteHelper::deleteObjectsFromTempCollection($params['ids'], $params['collectionId']);

            Yii::app()->user->setFlash(
                'success',
                Yii::t('objects', 'Все выбранные объекты из временной коллекции удалены!')
            );

        }
    }

    /**
     * Удаление объектов из обычной коллекции
     * @param mixed $params параметры
     * @throws CHttpException
     */
    protected function deleteObjectsFromNormalCollection($params)
    {
        if (!empty($params['ids'])) {

            if (!Yii::app()->user->checkAccess('oObjectDelete')) {
                throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
            }

            if (DeleteHelper::deleteObjectsFromNormalCollection($params['ids'])) {
                Yii::app()->user->setFlash(
                    'success',
                    Yii::t('objects', 'Все выбранные объекты удалены!')
                );
            } else {
                Yii::app()->user->setFlash(
                    'error',
                    Yii::t('objects', 'Некоторые объекты удалить не получилось. У объекта не должно быть относящихся к нему изображений, чтобы его можно было удалить.')
                );
            }
        }
    }

}