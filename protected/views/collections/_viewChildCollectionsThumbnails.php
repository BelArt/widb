<?php
/* @var $this CollectionsController */
/* @var $ChildCollectionsDataProvider CActiveDataProvider */
/* @var $model Collections */
?>

<?php $this->renderPartial('_viewChildCollectionsMenu', array('model' => $model)); ?>

<div class="row-fluid">
<?php
$this->widget(
    'bootstrap.widgets.TbThumbnails',
    array(
        'dataProvider' => $ChildCollectionsDataProvider,
        'template' => "{items}",
        'itemView' => '_viewThumbnailCheckbox',
        'emptyText' => ''
    )
);
?>
</div>