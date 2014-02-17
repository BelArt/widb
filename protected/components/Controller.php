<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column2';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
    /**
     * @var string название страницы. Будет доступно в лэйауте через $this->pageName
     */
    public $pageName = '';
    /**
     * @var array меню раздела. Массив данных для поля items виджета bootstrap.widgets.TbMenu
     */
    public $pageMenu = array();

    private $_pageTitle;

    /**
     * @return string the page title.
     */
    public function getPageTitle()
    {
        if (empty($this->_pageTitle)) {
            $this->_pageTitle = array(Yii::app()->name);
        }

        return $this->_pageTitle;

    }

    public function setPageTitle($value)
    {
        if (!is_array($value)) {
            throw new CException(Yii::t('common', 'В качестве pageTitle передан не массив!'));
        }

        array_unshift($value, Yii::app()->name);
        $this->_pageTitle = $value;
    }

    public function getAdminMenu()
    {
        try {
            $adminMenu = array();
            if (Yii::app()->user->checkAccess('oDictionariesView')) {
                $adminMenu[] = array(
                    'label' => Yii::t('admin', 'Справочники'),
                    'url' => $this->createUrl('dictionaries/view'),
                );
            }
            return $adminMenu;
        } catch (ControllerException $Exception) {
            throw $Exception;
        } catch (Exception $Exception) {
            throw new ControllerException($Exception);
        }
    }
}