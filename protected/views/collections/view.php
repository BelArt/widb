<?php
/* @var $this CollectionsController */
/* @var $model Collections */
/* @var $ObjectsDataProvider CActiveDataProvider */
/* @var $ChildCollectionsDataProvider CActiveDataProvider */
/* @var $renderViewChildCollections string */
/* @var $renderViewObjects string */

Yii::app()->clientScript->registerPackage('collectionView');
?>

<?php $this->renderPartial('_viewDescription', array('model' => $model)); ?>

<div class="gape"></div>

<?php
$this->widget(
    'bootstrap.widgets.TbMenu',
    array(
        'type' => 'pills',
        'items' => array(
            array(
                'label' => 'Редактировать коллекцию',
                'url' => $this->createUrl('collections/update', array('id' => $model->id)),
                'itemOptions' => array('class' => 'active small')
            ),
            array(
                'label' => 'Удалить коллекцию',
                'url' => $this->createUrl('collections/delete', array('id' => $model->id)),
                'itemOptions' => array('class' => 'active small')
            ),
            array(
                'label' => 'Добавить объект в коллекцию',
                'url' => '#',
                'itemOptions' => array('class' => 'active small')
            ),
        )
    )
);
?>

<div class="gape"></div>

<?php
$this->widget(
    'bootstrap.widgets.TbTabs',
    array(
        'type' => 'tabs', // 'tabs' or 'pills'
        'tabs' => array(
            array(
                'label' => 'Дочерние коллекции',
                'content' => $this->renderPartial(
                    $renderViewChildCollections,
                    array(
                        'ChildCollectionsDataProvider' => $ChildCollectionsDataProvider,
                        'model' => $model
                    ),
                    true
                ),
                'active' => true
            ),
            array(
                'label' => 'Объекты в коллекции',
                'content' => $this->renderPartial($renderViewObjects, array('ObjectsDataProvider' => $ObjectsDataProvider, 'model' => $model), true),
            ),
        ),
    )
);
?>

