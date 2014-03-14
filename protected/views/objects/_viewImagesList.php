<?php
/* @var $this ObjectsController */
/* @var $Object Objects */
/* @var $ImagesDataProvider CActiveDataProvider */
?>

<?php $this->renderPartial('_viewImagesMenu', array('Object' => $Object)); ?>

<div class="row-fluid">
    <?php
    $this->widget('bootstrap.widgets.TbListView', array(
        'dataProvider' => $ImagesDataProvider,
        'itemView' => 'application.views.images._viewListCheckbox',
        'template' => '{items}'.PHP_EOL.'{pager}',
        'ajaxUpdate' => false,
        'emptyText' => ''
    ));
    ?>
</div>