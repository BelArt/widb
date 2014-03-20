<?php
/* @var $this CollectionsController */
/* @var $model Collections */
/* @var $ObjectsDataProvider CActiveDataProvider */
/* @var $ChildCollectionsDataProvider CActiveDataProvider */
/* @var $renderViewChildCollections string */
/* @var $renderViewObjects string */
/* @var $tempCollectionsAllowedToUser array */
/* @var $collectionsToMoveTo array */
/* @var $activeTab string */

Yii::app()->clientScript->registerPackage('collectionView');
?>

<?php
$this->widget('bootstrap.widgets.TbTabs', array(
    'type' => 'tabs', // 'tabs' or 'pills'
    //'placement' => 'below',
    'tabs' => array(
        array(
            'label' => Yii::t('collections', 'Объекты в коллекции'),
            'content' => $this->renderPartial($renderViewObjects, array(
                    'ObjectsDataProvider' => $ObjectsDataProvider,
                    'model' => $model,
                    'tempCollectionsAllowedToUser' => $tempCollectionsAllowedToUser,
                    'collectionsToMoveTo' => $collectionsToMoveTo
            ), true),
            'active' => $activeTab == 'objects'
        ),
        array(
            'label' => Yii::t('collections', 'Дочерние коллекции'),
            'content' => $this->renderPartial($renderViewChildCollections, array(
                'ChildCollectionsDataProvider' => $ChildCollectionsDataProvider,
                'model' => $model
            ), true),
            'active' => $activeTab == 'childCollections'
        ),

    ),
));
?>

