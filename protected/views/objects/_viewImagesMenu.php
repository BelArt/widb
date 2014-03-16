<?php
/* @var $Object Objects */

$this->widget(
    'bootstrap.widgets.TbMenu',
    array(
        'type' => 'pills',
        'items' => array(
            array(
                'label' => Yii::t('common', 'С отмеченными'),
                'itemOptions' => array('class' => 'nav-header')
            ),
            array(
                'label' => Yii::t('common', 'Выгрузить'),
                'url' => '#',
                'itemOptions' => array('class' => 'small')
            ),
            array(
                'label' => Yii::t('common', 'Удалить'),
                'url' => '#',
                'itemOptions' => array(
                    'class' => 'small _deleteSelectedImages',
                    'data-dialog-title' => CHtml::encode(Yii::t('images', 'Удалить выбранные изображения?')),
                    'data-dialog-message' => CHtml::encode(Yii::t('images', 'Выбранные изображения будут удалены, и их нельзя будет восстановить')),
                )
            ),
        )
    )
);
?>

<!--<div class="gapeSmall"></div>-->

<?php

$classThumbnails = (empty($_GET['iv']) || $_GET['iv'] == 'th') ? 'selected' : '';
$classList = (!empty($_GET['iv']) && $_GET['iv'] == 'ls') ? 'selected' : '';
$classTable = (!empty($_GET['iv']) && $_GET['iv'] == 'tb') ? 'selected' : '';

$this->widget(
    'bootstrap.widgets.TbMenu',
    array(
        'type' => 'pills',
        'items' => array(
            array(
                'label' => Yii::t('common', 'Отображение'),
                'itemOptions' => array('class' => 'nav-header')
            ),
            array(
                'label' => Yii::t('common', 'Картинками'),
                'url' => $this->createUrl('objects/view', array('id' => $Object->id, 'iv' => 'th')),
                'itemOptions' => array('class' => 'small '.$classThumbnails)
            ),
            array(
                'label' => Yii::t('common', 'Списком'),
                'url' => $this->createUrl('objects/view', array('id' => $Object->id, 'iv' => 'ls')),
                'itemOptions' => array('class' => 'small '.$classList)
            ),
            array(
                'label' => Yii::t('common', 'Таблицей'),
                'url' => $this->createUrl('objects/view', array('id' => $Object->id, 'iv' => 'tb')),
                'itemOptions' => array('class' => 'small '.$classTable)
            ),
        )
    )
);
?>

<!--<div class="gape"></div>-->