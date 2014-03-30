<?php

/**
 * Контроллер админских действий
 */
class AdminController extends Controller
{

	public function filters()
	{
		return array(
			'accessControl',
		);
	}

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'deleteDeleted', 'repairPreview'),
                'roles' => array('oSystemManagement'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Показывает страницу с меню возможных действий
     */
    public function actionIndex()
    {
        $this->setPageParamsForActionIndex();

        $this->render('index', array(
            'actions' => $this->getActionsMenuForActionIndex(),
        ));
    }

    private function getActionsMenuForActionIndex()
    {
        return array(
            array(
                'label' => Yii::t('admin', 'Реально удалить "удаленные" записи из всех таблиц'),
                'url' => $this->createUrl('admin/deleteDeleted'),
            ),
            array(
                'label' => Yii::t('admin', 'Восстановить согласованность данных о превью'),
                'url' => $this->createUrl('admin/repairPreview'),
            )
        );
    }

    private function setPageParamsForActionIndex()
    {
        $this->pageTitle = array(Yii::t('admin', 'Управление системой'));
        $this->breadcrumbs = array(Yii::t('admin', 'Управление системой'));
    }

    /**
     * Реально удаляет все "удаленные" записи
     */
    public function actionDeleteDeleted()
    {
        DeleteHelper::deleteDeletedRecords();

        Yii::app()->user->setFlash('success', Yii::t('admin', 'Все "удаленные" записи реально удалены'));
        $this->redirect(array('admin/index'));
    }

    /**
     * Восстанавливает согласованность данных о превью
     */
    public function actionRepairPreview()
    {
        // удаляем несохраненные превью
        DeleteHelper::deleteUnsavedPreviews();
        // удаляем пустые папки
        DeleteHelper::deleteEmptyFoldersInPreviews();
        // если стоит галочка Есть превью, а на диске превью нет - снимаем галочку
        DeleteHelper::uncheckHasPreviewCheckboxIfReallyHasNoPreview();

        Yii::app()->user->setFlash('success', Yii::t('admin', 'Теперь все круто'));
        $this->redirect(array('admin/index'));
    }
}
