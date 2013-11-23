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
/*$this->widget(
    'bootstrap.widgets.TbMenu',
    array(
        'type' => 'pills',
        //'stacked' => true,
        'items' => array(
            array(
                'label' => 'Редактировать коллекцию',
                'url' => '#',
                'itemOptions' => array('class' => 'small')
            ),
            array(
                'label' => 'Удалить коллекцию',
                'url' => '#',
                'itemOptions' => array('class' => 'small')
            ),
            array(
                'label' => 'Добавить объект в коллекцию',
                'url' => '#',
                'itemOptions' => array('class' => 'small')
            ),
        ),
    )
);*/
?>

<!--<div class="gape"></div>-->

<?php
$this->widget(
    'bootstrap.widgets.TbTabs',
    array(
        'type' => 'tabs', // 'tabs' or 'pills'
        //'placement' => 'below',
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
                'active' => (empty($_GET['tb']) || $_GET['tb'] == 'cc' || (!empty($_GET['tb']) && $_GET['tb'] != 'ob' && $_GET['tb'] != 'cc'))
            ),
            array(
                'label' => 'Объекты в коллекции',
                'content' => $this->renderPartial($renderViewObjects, array('ObjectsDataProvider' => $ObjectsDataProvider, 'model' => $model), true),
                'active' => (!empty($_GET['tb']) && $_GET['tb'] == 'ob')
            ),
        ),
    )
);
?>

