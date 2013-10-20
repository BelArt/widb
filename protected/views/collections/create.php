<?php
/* @var $this CollectionsController */
/* @var $model Collections */

$this->breadcrumbs=array(
	'Collections'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Collections', 'url'=>array('index')),
	array('label'=>'Manage Collections', 'url'=>array('admin')),
);
?>

<h1>Create Collections</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>