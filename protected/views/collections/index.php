<?php
/* @var $this CollectionsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Collections',
);

$this->menu=array(
    array('label'=>'Операции', 'itemOptions' => array('class' => 'nav-header')),
	array('label'=>'Create Collections', 'url'=>array('create')),
	array('label'=>'Manage Collections', 'url'=>array('admin')),
);
?>

<h1>Collections</h1>

<?php
    /*
     $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
    ));
    */
    echo CHtml::openTag('div', array('class' => 'row-fluid'));
    $this->widget(
        'bootstrap.widgets.TbThumbnails',
        array(
            'dataProvider' => $dataProvider,
            'template' => "{items}\n{pager}",
            'itemView' => '_view',
        )
    );
    echo CHtml::closeTag('div');
 ?>
