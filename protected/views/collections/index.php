<?php
/* @var $this CollectionsController */
/* @var $dataProvider CActiveDataProvider */

Yii::app()->clientScript->registerPackage('collections');
?>

<div class="childrenMenuLevel1Wrapper hidden-print">
<?php

$classThumbnails = (empty($_GET['cv']) || $_GET['cv'] == 'th') ? 'selected' : '';
$classList = (!empty($_GET['cv']) && $_GET['cv'] == 'ls') ? 'selected' : '';
$classTable = (!empty($_GET['cv']) && $_GET['cv'] == 'tb') ? 'selected' : '';

$this->widget('widgets.children_menu.ChildrenMenu', array(
    'menuName' => Yii::t('common', 'Отображение'),
    'menuItems' => array(
        array(
            'label' => Yii::t('common', 'Картинками'),
            'url' => Yii::app()->urlManager->createCollectionsUrl(array('cv' => 'th')),
            'tdOptions' => array('class' => 'childrenMenuItem '.$classThumbnails),
            'iconType' => 'thumbs',
        ),
        array(
            'label' => Yii::t('common', 'Списком'),
            'url' => Yii::app()->urlManager->createCollectionsUrl(array('cv' => 'ls')),
            'tdOptions' => array('class' => 'childrenMenuItem '.$classList),
            'iconType' => 'list',
        ),
        array(
            'label' => Yii::t('common', 'Таблицей'),
            'url' => Yii::app()->urlManager->createCollectionsUrl(array('cv' => 'tb')),
            'tdOptions' => array('class' => 'childrenMenuItem '.$classTable),
            'iconType' => 'table',
        ),
    )
));
?>
</div>
<div class="clear"></div>
<div class="gapeSmall"></div>
<div class="row-fluid">
<?
    switch ($viewType) {
        case 'thumbnails':
            $this->widget('bootstrap.widgets.TbThumbnails', array(
                'dataProvider' => $dataProvider,
                'template' => '{items}'.PHP_EOL.'{pager}',
                'itemView' => '_viewThumbnailNoCheckbox',
                'ajaxUpdate' => false,
                'emptyText' => '',
                //'htmlOptions' => array('class' => 'collections_thumbnails')
            ));
            break;
        case 'list':
            $this->widget('bootstrap.widgets.TbListView', array(
                'dataProvider' => $dataProvider,
                'itemView'=>'_viewListNoCheckbox',
                'template' => '{items}'.PHP_EOL.'{pager}',
                'ajaxUpdate' => false,
                'emptyText' => ''
            ));
            break;
        case 'table':
            $this->widget('bootstrap.widgets.TbGridView', array(
                'type' => 'striped bordered',
                'dataProvider' => $dataProvider,
                'template' => "{items}\n{pager}",
                'ajaxUpdate' => false,
                'columns' => array(
                    /*array(
                        'class' => 'CCheckBoxColumn',
                        'value' => 'return null;',
                        'checked' => 'return null;',
                        'selectableRows' => 2,
                    ),*/
                    array(
                        'class' => 'CLinkColumn',
                        'labelExpression' => '$data->name',
                        'urlExpression' => '$data->temporary ? Yii::app()->urlManager->createTempCollectionUrl($data) : Yii::app()->urlManager->createNormalCollectionUrl($data)',
                        'header'=>'Название',
                        'headerHtmlOptions' => array(
                            'class' => 'valignedMiddle halignedCenter'
                        ),
                    ),
                    /*array(
                        'header'=>'Действия',
                        'htmlOptions' => array('nowrap'=>'nowrap'),
                        'class'=>'bootstrap.widgets.TbButtonColumn',
                        'viewButtonUrl' => '$data->temporary ? Yii::app()->urlManager->createUrl("collections/viewTemp", array("id" => $data->id)) : Yii::app()->urlManager->createUrl("collections/view", array("id" => $data->id))',
                        'updateButtonUrl'=> null,
                        'deleteButtonUrl' => null,
                    )*/
                ),
                'showTableOnEmpty' => false,
                'emptyText' => ''
            ));
            break;
    }

 ?>
 </div>
