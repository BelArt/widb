<?php
/* @var $this CollectionsController */
/* @var $ObjectsDataProvider CActiveDataProvider */
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
                'label' => 'Добавить во временную коллекцию',
                'url' => '#',
                'itemOptions' => array('class' => 'small')
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

$classThumbnails = (empty($_GET['ov']) || $_GET['ov'] == 'th') ? 'selected' : '';
$classList = (!empty($_GET['ov']) && $_GET['ov'] == 'ls') ? 'selected' : '';
$classTable = (!empty($_GET['ov']) && $_GET['ov'] == 'tb') ? 'selected' : '';

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
                'url' => $this->createUrl('collections/view', array('id' => $model->id, 'ov' => 'th', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''), 'tb' => 'ob')),
                'itemOptions' => array('class' => 'small '.$classThumbnails)
            ),
            array(
                'label' => 'Списком',
                'url' => $this->createUrl('collections/view', array('id' => $model->id, 'ov' => 'ls', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''), 'tb' => 'ob')),
                'itemOptions' => array('class' => 'small '.$classList)
            ),
            array(
                'label' => 'Таблицей',
                'url' => $this->createUrl('collections/view', array('id' => $model->id, 'ov' => 'tb', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''), 'tb' => 'ob')),
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
        'dataProvider' => $ObjectsDataProvider,
        'template' => "{items}\n{pager}",
        'itemView' => 'application.views.objects._viewCheckbox',
    )
);
?>
</div>