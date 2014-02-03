<?php
/* @var $this ObjectsController */
/* @var $Object Objects */
/* @var $ImagesDataProvider CActiveDataProvider */
?>

<?php $this->renderPartial('_viewImagesMenu', array('Object' => $Object)); ?>

<div class="row-fluid">
    <?php
    $this->widget('zii.widgets.CListView', array(
        'dataProvider' => $ImagesDataProvider,
        'itemView' => 'application.views.images._viewListCheckbox',
        'template' => '{items}',
        'emptyText' => ''
    ));
    ?>
</div>