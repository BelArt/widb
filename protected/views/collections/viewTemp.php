<?php
/* @var $this CollectionsController */
/* @var $model Collections */
/* @var $ObjectsDataProvider CActiveDataProvider */
/* @var $renderViewObjects string */

Yii::app()->clientScript->registerPackage('collectionView');
?>

<?php /*$this->renderPartial('_viewDescription', array('model' => $model)); */?>

<!--<div class="gape"></div>-->

<div class="">
    <?php
    $this->renderPartial($renderViewObjects, array('ObjectsDataProvider' => $ObjectsDataProvider, 'model' => $model));
    ?>
</div>

