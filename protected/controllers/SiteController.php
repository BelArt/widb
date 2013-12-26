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
	public function actionError()
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
	}

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
        }

    }

}