<?php
/* @var $this CollectionsController */
/* @var $ObjectsDataProvider CActiveDataProvider */
/* @var $model Collections */
/* @var $tempCollectionsAllowedToUser array */
/* @var $collectionsToMoveTo array */
?>

<?php $this->renderPartial('_viewObjectsMenu', array('model' => $model, 'tempCollectionsAllowedToUser' => $tempCollectionsAllowedToUser, 'collectionsToMoveTo' => !empty($collectionsToMoveTo) ? $collectionsToMoveTo : null)); ?>

<div class="row-fluid">
    <?php
    $this->widget('bootstrap.widgets.TbListView', array(
        'dataProvider' => $ObjectsDataProvider,
        'itemView' => 'application.views.objects._viewListCheckbox',
        'template' => '{items}'.PHP_EOL.'{pager}',
        'ajaxUpdate' => false,
        'emptyText' => ''
    ));
    ?>
</div>