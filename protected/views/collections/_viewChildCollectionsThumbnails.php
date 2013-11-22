<?php
/* @var $this CollectionsController */
/* @var $ChildCollectionsDataProvider CActiveDataProvider */
/* @var $model Collections */

$this->widget(
    'bootstrap.widgets.TbMenu',
    array(
        'type' => 'pills',
        'items' => array(
            array(
                'label' => 'С отмеченными',
                'itemOptions' => array('class' => 'nav-header')
            ),
            array(
                'label' => 'Переместить',
                'url' => '#',
                'itemOptions' => array('class' => 'small')
            ),
            array(
                'label' => 'Удалить',
                'url' => '#',
                'itemOptions' => array('class' => 'small')
            ),
        )
    )
);
?>

<div class="gapeSmall"></div>

<?php

$classThumbnails = (empty($_GET['cv']) || $_GET['cv'] == 'th') ? 'selected' : '';
$classList = (!empty($_GET['cv']) && $_GET['cv'] == 'ls') ? 'selected' : '';
$classTable = (!empty($_GET['cv']) && $_GET['cv'] == 'tb') ? 'selected' : '';

$this->widget(
    'bootstrap.widgets.TbMenu',
    array(
        'type' => 'pills',
        'items' => array(
            array(
                'label' => 'Отображение',
                'itemOptions' => array('class' => 'nav-header')
            ),
            array(
                'label' => 'Картинками',
                'url' => $this->createUrl('collections/view', array('id' => $model->id, 'cv' => 'th', 'ov' => (!empty($_GET['ov']) ? $_GET['ov'] : ''), 'tb' => 'cc')),
                'itemOptions' => array('class' => 'small '.$classThumbnails)
            ),
            array(
                'label' => 'Списком',
                'url' => $this->createUrl('collections/view', array('id' => $model->id, 'cv' => 'ls', 'ov' => (!empty($_GET['ov']) ? $_GET['ov'] : ''), 'tb' => 'cc')),
                'itemOptions' => array('class' => 'small '.$classList)
            ),
            array(
                'label' => 'Таблицей',
                'url' => $this->createUrl('collections/view', array('id' => $model->id, 'cv' => 'tb', 'ov' => (!empty($_GET['ov']) ? $_GET['ov'] : ''), 'tb' => 'cc')),
                'itemOptions' => array('class' => 'small '.$classTable)
            ),
        )
    )
);
?>

<div class="gape"></div>

<div class="row-fluid">
<?php
$this->widget(
    'bootstrap.widgets.TbThumbnails',
    array(
        'dataProvider' => $ChildCollectionsDataProvider,
        'template' => "{items}\n{pager}",
        'itemView' => '_viewCheckbox',
    )
);
?>
</div>