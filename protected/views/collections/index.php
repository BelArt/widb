<?php
/* @var $this CollectionsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Collections',
);

$this->menu=array(
	array('label'=>'Create Collections', 'url'=>array('create')),
	array('label'=>'Manage Collections', 'url'=>array('admin')),
);
?>

<h1>Collections</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
