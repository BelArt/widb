<?php
/* @var $this CollectionsController */
/* @var $model Collections */
/* @var $ObjectsDataProvider CActiveDataProvider */
/* @var $renderViewObjects string */
/* @var $tempCollectionsAllowedToUser array */

Yii::app()->clientScript->registerPackage('collectionView');
?>

<?php /*$this->renderPartial('_viewDescription', array('model' => $model)); */?>

<!--<div class="gape"></div>-->

<div class="">
    <?php
    $this->renderPartial($renderViewObjects, array('ObjectsDataProvider' => $ObjectsDataProvider, 'model' => $model, 'tempCollectionsAllowedToUser' => $tempCollectionsAllowedToUser));
    ?>
</div>

