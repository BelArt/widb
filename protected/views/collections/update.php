<?php
/* @var $this CollectionsController */
/* @var $model Collections */

$this->breadcrumbs=array(
	'Collections'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Collections', 'url'=>array('index')),
	array('label'=>'Create Collections', 'url'=>array('create')),
	array('label'=>'View Collections', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Collections', 'url'=>array('admin')),
);
?>

<h1>Update Collections <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>