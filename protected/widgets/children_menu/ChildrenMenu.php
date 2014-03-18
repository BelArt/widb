<?php
/**
 * Виджет для меню дочерних элементов - объектов в коллекции, изображений объекта и т.д.
 */

class ChildrenMenu extends CWidget
{
    public $menuName;
    public $menuItems;

    private $iconTypes = array(
        'list' => 'list.png',
        'table' => 'table.png',
        'thumbs' => 'thumbs.png',
        'delete' => 'delete.png',
        'download' => 'download.png',
        'delete_from_temp' => 'delete_from_temp.png',
        'move' => 'move.png',
        'add_to_temp' => 'add_to_temp.png'
    );

    public function run()
    {
        if (empty($this->menuName)) {
            throw new CException();
        }

        if (empty($this->menuItems)) {
            return;
        }

        $imagesFolderUrl = Yii::app()->assetManager->publish(
            dirname(__FILE__).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'img'
        );

        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('widgets.children_menu.assets.css').'/children_menu.css'
            )
        );

        $this->render('index', array(
            'menuItems' => $this->menuItems,
            'menuName' => $this->menuName,
            'imagesFolderUrl' => $imagesFolderUrl,
            'iconTypes' => $this->iconTypes
        ));

    }

} 