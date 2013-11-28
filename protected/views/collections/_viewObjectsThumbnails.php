<?php
/* @var $this CollectionsController */
/* @var $ObjectsDataProvider CActiveDataProvider */
/* @var $model Collections */
?>

<?php $this->renderPartial('_viewObjectsMenu', array('model' => $model)); ?>

<div class="row-fluid">
<?php
$this->widget(
    'bootstrap.widgets.TbThumbnails',
    array(
        'dataProvider' => $ObjectsDataProvider,
        'template' => "{items}",
        'itemView' => 'application.views.objects._viewThumbnailCheckbox',
        'emptyText' => ''
    )
);
?>
</div>