<?php
/* @var $this CollectionsController */
/* @var $ChildCollectionsDataProvider CActiveDataProvider */
/* @var $model Collections */
?>

<?php $this->renderPartial('_viewChildCollectionsMenu', array('model' => $model)); ?>

<div class="row-fluid">
    <?php
    $this->widget('zii.widgets.CListView', array(
        'dataProvider' => $ChildCollectionsDataProvider,
        'itemView'=>'_viewListCheckbox',
    ));
    ?>
</div>