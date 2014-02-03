<?php
/* @var $this ObjectsController */
/* @var $Object Objects */
/* @var $ImagesDataProvider CActiveDataProvider */
?>
<?php $this->renderPartial('_viewImagesMenu', array('Object' => $Object)); ?>

<div class="row-fluid">
    <?php
    $this->widget(
        'bootstrap.widgets.TbThumbnails',
        array(
            'dataProvider' => $ImagesDataProvider,
            'template' => "{items}",
            'itemView' => 'application.views.images._viewThumbnailCheckbox',
            'emptyText' => ''
        )
    );
    ?>
</div>