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
                'url' => $this->createUrl('collections/view', array('id' => $model->id, 'ov' => 'thumbnails', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''))),
                'itemOptions' => array('class' => 'small')
            ),
            array(
                'label' => 'Списком',
                'url' => $this->createUrl('collections/view', array('id' => $model->id, 'ov' => 'list', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''))),
                'itemOptions' => array('class' => 'small')
            ),
            array(
                'label' => 'Таблицей',
                'url' => $this->createUrl('collections/view', array('id' => $model->id, 'ov' => 'table', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''))),
                'itemOptions' => array('class' => 'small')
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