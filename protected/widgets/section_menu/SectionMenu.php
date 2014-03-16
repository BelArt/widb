<?php
/**
 * Виджет для меню раздела
 */

class SectionMenu extends CWidget
{
    public $menuItems;

    private $iconTypes = array(
        'create_normal_col' => 'create_normal_col.png',
        'create_temp_col' => 'create_temp_col.png',
        'create' => 'create.png',
        'edit' => 'edit.png',
        'delete' => 'delete.png',
        'move' => 'move.png',
        'add_to_temp' => 'add_to_temp.png',
        'create_author' => 'create_author.png',
        'create_object_type' => 'create_object_type.png',
        'create_photo_type' => 'create_photo_type.png',
    );

    public function run()
    {
        if (empty($this->menuItems)) {
            return;
        }

        $imagesFolderUrl = Yii::app()->assetManager->publish(
            dirname(__FILE__).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'img'
        );

        $this->render('index', array(
            'menuItems' => $this->menuItems,
            'imagesFolderUrl' => $imagesFolderUrl,
            'iconTypes' => $this->iconTypes
        ));

    }

} 