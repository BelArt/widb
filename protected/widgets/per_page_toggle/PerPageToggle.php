<?php

/**
 * Виджет для создания преключателя "сущностей на страницу".
 */
class PerPageToggle extends CWidget
{
    public $text;
    public $valueSet;
    public $varName;
    public $pageSize;

    public function init()
    {
        if (empty($this->text)) {
            $this->text = Yii::t('common', 'На страницу');
        }

        if (empty($this->valueSet)) {
            $this->valueSet = array(1,2,5,10,20,30,50,100,500);
        }

        if (empty($this->varName)) {
            $this->varName = 'pp';
        }

        if (empty($this->pageSize)) {
            $this->pageSize = CPagination::DEFAULT_PAGE_SIZE;
        }
    }

    public function run()
    {
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('widgets.per_page_toggle.assets.css').'/per-page-toggle.css'
            )
        );

        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('widgets.per_page_toggle.assets.js').'/per-page-toggle.js'
            )
        );

        $perPageValue = Yii::app()->request->getQuery($this->varName, $this->pageSize);

        $this->render('index', array(
            'text' => $this->text,
            'valueSet' => $this->valueSet,
            'varName' => $this->varName,
            'perPageValue' => $perPageValue,
        ));
    }

} 