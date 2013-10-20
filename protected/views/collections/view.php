<?php
/* @var $this CollectionsController */
/* @var $model Collections */

$this->breadcrumbs=array(
	'Collections'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Collections', 'url'=>array('index')),
	array('label'=>'Create Collections', 'url'=>array('create')),
	array('label'=>'Update Collections', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Collections', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Collections', 'url'=>array('admin')),
);
?>

<h1>View Collections #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'parent_id',
		'name',
		'description',
		'code',
		'image',
		'temporary',
		'has_preview',
		'date_create',
		'date_modify',
		'date_delete',
		'sort',
		'deleted',
	),
)); ?>
