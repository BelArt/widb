<?php
/* @var $this CollectionsController */
/* @var $ObjectsDataProvider CActiveDataProvider */
/* @var $model Collections */
/* @var $tempCollectionsAllowedToUser array */
?>

<?php $this->renderPartial('_viewObjectsMenu', array('model' => $model, 'tempCollectionsAllowedToUser' => $tempCollectionsAllowedToUser)); ?>

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