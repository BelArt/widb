<?php
/* @var $model Collections */

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
                'label' => Yii::t('collections', 'Добавить во временную коллекцию'),
                'url' => '#',
                'itemOptions' => array('class' => 'small')
            ),
            array(
                'label' => Yii::t('common', 'Переместить'),
                'url' => '#',
                'itemOptions' => array('class' => 'small')
            ),
            array(
                'label' => Yii::t('common', 'Удалить'),
                'url' => '#',
                'itemOptions' => array('class' => 'small')
            ),
        )
    )
);
?>

<div class="gapeSmall"></div>

<?php

$classThumbnails = (empty($_GET['ov']) || $_GET['ov'] == 'th') ? 'selected' : '';
$classList = (!empty($_GET['ov']) && $_GET['ov'] == 'ls') ? 'selected' : '';
$classTable = (!empty($_GET['ov']) && $_GET['ov'] == 'tb') ? 'selected' : '';

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
                'url' => $this->createUrl($model->temporary ? 'collections/viewTemp' : 'collections/view', array('id' => $model->id, 'ov' => 'th', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''), 'tb' => 'ob')),
                'itemOptions' => array('class' => 'small '.$classThumbnails)
            ),
            array(
                'label' => Yii::t('common', 'Списком'),
                'url' => $this->createUrl($model->temporary ? 'collections/viewTemp' : 'collections/view', array('id' => $model->id, 'ov' => 'ls', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''), 'tb' => 'ob')),
                'itemOptions' => array('class' => 'small '.$classList)
            ),
            array(
                'label' => Yii::t('common', 'Таблицей'),
                'url' => $this->createUrl($model->temporary ? 'collections/viewTemp' : 'collections/view', array('id' => $model->id, 'ov' => 'tb', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''), 'tb' => 'ob')),
                'itemOptions' => array('class' => 'small '.$classTable)
            ),
        )
    )
);

?>

<div class="gape"></div>