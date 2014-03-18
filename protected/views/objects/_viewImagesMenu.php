<?php
/* @var $Object Objects */
?>

<div class="childrenMenuLevel1Wrapper">
    <?php

    $classThumbnails = (empty($_GET['iv']) || $_GET['iv'] == 'th') ? 'selected' : '';
    $classList = (!empty($_GET['iv']) && $_GET['iv'] == 'ls') ? 'selected' : '';
    $classTable = (!empty($_GET['iv']) && $_GET['iv'] == 'tb') ? 'selected' : '';

    $this->widget('widgets.children_menu.ChildrenMenu', array(
        'menuName' => Yii::t('common', 'Отображение'),
        'menuItems' => array(
            array(
                'label' => Yii::t('common', 'Картинками'),
                'url' => $this->createUrl('objects/view', array('id' => $Object->id, 'iv' => 'th')),
                'tdOptions' => array('class' => 'childrenMenuItem '.$classThumbnails),
                'iconType' => 'thumbs',
            ),
            array(
                'label' => Yii::t('common', 'Списком'),
                'url' => $this->createUrl('objects/view', array('id' => $Object->id, 'iv' => 'ls')),
                'tdOptions' => array('class' => 'childrenMenuItem '.$classList),
                'iconType' => 'list',
            ),
            array(
                'label' => Yii::t('common', 'Таблицей'),
                'url' => $this->createUrl('objects/view', array('id' => $Object->id, 'iv' => 'tb')),
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
        array(
            'label' => Yii::t('common', 'Выгрузить'),
            'url' => '#',
            'tdOptions' => array('class' => 'childrenMenuItem '),
            'iconType' => 'download',
        ),
        array(
            'label' => Yii::t('common', 'Удалить'),
            'url' => '#',
            'itemOptions' => array(
                'class' => '_deleteSelectedImages',
                'data-dialog-title' => Yii::t('images', 'Удалить выбранные изображения?'),
                'data-dialog-message' => Yii::t('images', 'Выбранные изображения будут удалены, и их нельзя будет восстановить'),
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