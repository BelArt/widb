<?php
/* @var $this CollectionsController */
/* @var $ChildCollectionsDataProvider CActiveDataProvider */
/* @var $model Collections */
?>

<?php $this->renderPartial('_viewChildCollectionsMenu', array('model' => $model)); ?>

<div class="row-fluid">
    <?php
    $this->widget('bootstrap.widgets.TbListView', array(
        'dataProvider' => $ChildCollectionsDataProvider,
        'itemView'=>'_viewListCheckbox',
        'template' => '{items}'.PHP_EOL.'{pager}',
        'ajaxUpdate' => false,
        'emptyText' => ''
    ));
    ?>
</div>