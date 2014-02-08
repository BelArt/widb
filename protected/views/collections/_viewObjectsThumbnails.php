<?php
/* @var $this CollectionsController */
/* @var $ObjectsDataProvider CActiveDataProvider */
/* @var $model Collections */
/* @var $tempCollectionsAllowedToUser array */
/* @var $collectionsToMoveTo array */
?>

<?php $this->renderPartial('_viewObjectsMenu', array('model' => $model, 'tempCollectionsAllowedToUser' => $tempCollectionsAllowedToUser, 'collectionsToMoveTo' => $collectionsToMoveTo)); ?>

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