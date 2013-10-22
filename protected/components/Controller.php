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
	public $layout='//layouts/column1';
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

    private $_pageTitle;

    /**
     * @return string the page title. Defaults to the controller name and the action name.
     */
    public function getPageTitle()
    {
        if($this->_pageTitle!==null)
            return $this->_pageTitle;
        else
        {
            return $this->_pageTitle = Yii::app()->name;
        }
    }

    /**
     * @param string $value the page title.
     */
    public function setPageTitle($value)
    {
        $this->_pageTitle = Yii::app()->name.' | '.$value;
    }
}