<?php

/**
 * Виджет для отрисовки поисковой формы в навбаре
 */
class SearchForm extends CWidget
{
    public $action;
    public $selectValues;

    const COLLECTIONS_VALUE = 'co';
    const OBJECTS_VALUE = 'ob';
    const IMAGES_VALUE = 'im';
    const ALL_VALUE = 'all';

    public function init()
    {
        if (empty($this->action)) {
            $this->action = Yii::app()->urlManager->createUrl('site/search');
        }

        if (empty($this->selectValues)) {
            $this->selectValues = array(
                self::ALL_VALUE => Yii::t('common', 'везде'),
                self::COLLECTIONS_VALUE => Yii::t('common', 'в коллекциях'),
                self::OBJECTS_VALUE => Yii::t('common', 'в объектах'),
                self::IMAGES_VALUE => Yii::t('common', 'в изображениях'),

            );
        }
    }

    public function run()
    {
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('widgets.search_form.assets.css').'/search-form.css'
            )
        );

        $this->render('index', array(
            'action' => $this->action,
            'selectValues' => $this->selectValues,
        ));
    }

    public static function getCategoryValues()
    {
        return array(self::COLLECTIONS_VALUE, self::OBJECTS_VALUE, self::IMAGES_VALUE, self::ALL_VALUE);
    }

} 