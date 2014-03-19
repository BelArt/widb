<?php
/* @var $model Collections */
?>

<div class="childrenMenuLevel1Wrapper">
<?php

$classThumbnails = (empty($_GET['cv']) || $_GET['cv'] == 'th') ? 'selected' : '';
$classList = (!empty($_GET['cv']) && $_GET['cv'] == 'ls') ? 'selected' : '';
$classTable = (!empty($_GET['cv']) && $_GET['cv'] == 'tb') ? 'selected' : '';

$this->widget('widgets.children_menu.ChildrenMenu', array(
    'menuName' => Yii::t('common', 'Отображение'),
    'menuItems' => array(
        array(
            'label' => Yii::t('common', 'Картинками'),
            'url' => $this->createUrl('collections/view', array('id' => $model->id, 'cv' => 'th', 'ov' => (!empty($_GET['ov']) ? $_GET['ov'] : ''), 'tb' => 'cc')),
            'tdOptions' => array('class' => 'childrenMenuItem '.$classThumbnails),
            'iconType' => 'thumbs',
        ),
        array(
            'label' => Yii::t('common', 'Списком'),
            'url' => $this->createUrl('collections/view', array('id' => $model->id, 'cv' => 'ls', 'ov' => (!empty($_GET['ov']) ? $_GET['ov'] : ''), 'tb' => 'cc')),
            'tdOptions' => array('class' => 'childrenMenuItem '.$classList),
            'iconType' => 'list',
        ),
        array(
            'label' => Yii::t('common', 'Таблицей'),
            'url' => $this->createUrl('collections/view', array('id' => $model->id, 'cv' => 'tb', 'ov' => (!empty($_GET['ov']) ? $_GET['ov'] : ''), 'tb' => 'cc')),
            'tdOptions' => array('class' => 'childrenMenuItem '.$classTable),
            'iconType' => 'table',
        ),
    )
));
?>
</div>

<div class="childrenMenuLevel2Wrapper">
<?php
$this->widget('widgets.children_menu.ChildrenMenu', array(
    'menuName' => Yii::t('common', 'С отмеченными'),
    'menuItems' => array(
        /*array(
            'label' => Yii::t('common', 'Переместить'),
            'url' => '#',
            'tdOptions' => array('class' => 'childrenMenuItem '),
            'iconType' => 'move',
        ),*/
        array(
            'label' => Yii::t('common', 'Удалить'),
            'url' => '#',
            'itemOptions' => array(
                'class' => '_deleteSelectedChildCollections',
                'data-dialog-title' => CHtml::encode(Yii::t('collections', 'Удалить выбранные дочерние коллекции?')),
                'data-dialog-message' => CHtml::encode(Yii::t('collections', 'Выбранные дочерние коллекции будут удалены, и их нельзя будет восстановить')),
            ),
            'tdOptions' => array('class' => 'childrenMenuItem '),
            'iconType' => 'delete',
        ),
    )
));
?>
</div>

<div class="clear"></div>

<div class="gapeSmall"></div>