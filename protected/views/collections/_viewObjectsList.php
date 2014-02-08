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
    $this->widget('zii.widgets.CListView', array(
        'dataProvider' => $ObjectsDataProvider,
        'itemView' => 'application.views.objects._viewListCheckbox',
        'template' => '{items}',
        'emptyText' => ''
    ));
    ?>
</div>