<?php

class DictionariesController extends Controller
{
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
                'actions' => array('view'),
                'roles' => array('oDictionariesView'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionView()
    {
        try {
            $AuthorsDataProvider = new CActiveDataProvider('Authors');
            $this->setPageParamsForActionView();
            $this->render('view', array(
                'AuthorsDataProvider' => $AuthorsDataProvider,
            ));
        } catch (DictionariesControllerException $Exception) {
            throw $Exception;
        } catch (Exception $Exception) {
            throw new DictionariesControllerException($Exception);
        }
    }

    /**
     * Устанавливает параметры страницы просмотра объекта (тайтл, хлебные крошки, заголовок, меню)
     * @throws DictionariesControllerException
     */
    private function setPageParamsForActionView()
    {
        try {
            $this->pageTitle = array(Yii::t('admin', 'Справочники'));
            $this->breadcrumbs = array(Yii::t('admin', 'Справочники'));
            $this->pageName = Yii::t('admin', 'Справочники');
            $this->pageMenu = array();
        } catch (DictionariesControllerException $Exception) {
            throw $Exception;
        } catch (Exception $Exception) {
            throw new DictionariesControllerException($Exception);
        }
    }

}
