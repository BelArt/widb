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
                'itemOptions' => array('class' => 'active')
            ),
            array(
                'label' => 'Удалить',
                'url' => '#',
                'itemOptions' => array('class' => 'active')
            ),
        )
    )
);

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
                'url' => $this->createUrl('collections/view', array('id' => $model->id, 'cv' => 'thumbnails', 'ov' => (!empty($_GET['ov']) ? $_GET['ov'] : ''))),
                'itemOptions' => array('class' => 'active')
            ),
            array(
                'label' => 'Списком',
                'url' => $this->createUrl('collections/view', array('id' => $model->id, 'cv' => 'list', 'ov' => (!empty($_GET['ov']) ? $_GET['ov'] : ''))),
                'itemOptions' => array('class' => 'active')
            ),
            array(
                'label' => 'Таблицей',
                'url' => $this->createUrl('collections/view', array('id' => $model->id, 'cv' => 'table', 'ov' => (!empty($_GET['ov']) ? $_GET['ov'] : ''))),
                'itemOptions' => array('class' => 'active')
            ),
        )
    )
);
?>

<div class="row-fluid">
<?php
$this->widget(
    'bootstrap.widgets.TbThumbnails',
    array(
        'dataProvider' => $ChildCollectionsDataProvider,
        'template' => "{items}\n{pager}",
        'itemView' => '_view',
    )
);
?>
</div>