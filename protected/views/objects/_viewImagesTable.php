<?php
/* @var $this ObjectsController */
/* @var $Object Objects */
/* @var $ImagesDataProvider CActiveDataProvider */
?>

<?php $this->renderPartial('_viewImagesMenu', array('Object' => $Object)); ?>

<?php
$this->widget(
    'bootstrap.widgets.TbGridView',
    array(
        'type' => 'striped bordered',
        'dataProvider' => $ImagesDataProvider,
        'template' => '{items}'.PHP_EOL.'{pager}',
        'ajaxUpdate' => false,
        'columns' => array(
            array(
                'class' => 'CCheckBoxColumn',
                'value' => 'false',
                'checked' => 'false',
                'selectableRows' => 2,
                'checkBoxHtmlOptions' => array(
                    'class' => '_imageItem',
                    'data-image-id' => '' // айдишник изображения будет записываться сюда джаваскриптом
                ),
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
            ),
            array(
                'class' => 'CLinkColumn',
                'labelExpression' => '$data->name',
                'urlExpression' => 'Yii::app()->urlManager->createUrl("images/view", array("id" => $data->id))',
                'header' => CHtml::encode(Yii::t('common', 'Размер')),
                'linkHtmlOptions' => array(
                    'class' => '_imageLinkInTableRow'
                ),
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
            ),
            /*array(
                'value'=>'(!empty($data->resolution) ? $data->resolution : "")',
                'header' => Yii::t('images', 'Разрешение'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTableResolution'
                ),
            ),*/
            array(
                'value'=>'(!empty($data->date_photo) ? OutputHelper::formatDate($data->date_photo) : "")',
                'header' => Yii::t('images', 'Дата съемки'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTableDatePhoto'
                ),
            ),
            /*array(
                'value'=>'(!empty($data->photoType->name) ? OutputHelper::stringToLower($data->photoType->name) : "")',
                'header' => Yii::t('images', 'Тип съемки'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTablePhotoType'
                ),
            ),*/
            array(
                'value'=>'(!empty($data->original) ? $data->original : "")',
                'header' => Yii::t('images', 'Путь хранения оригинала'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTableOriginalPath'
                ),
            ),
            array(
                'value'=>'(!empty($data->description) ? $data->description : "")',
                'header' => Yii::t('common', 'Комментарий'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTableDescription'
                ),
            ),
            /*array(
                'value'=>'(!empty($data->period) ? $data->period : "")',
                'header' => Yii::t('objects', 'Период создания'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTableCreationPeriod'
                ),
            ),
            array(
                'value'=>'(!empty($data->inventory_number) ? $data->inventory_number : "")',
                'header' => Yii::t('objects', 'Инвентарный номер'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTableInventoryNumber'
                ),
            ),
            array(
                'value'=>'(!empty($data->type->name) ? $data->type->name : "")',
                'header' => Yii::t('objects', 'Тип объекта'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTableObjectType'
                ),
            ),
            array(
                'value'=>'(!empty($data->size) ? $data->size : "")',
                'header' => Yii::t('common', 'Размер'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTableSize'
                ),
            ),*/
            array(
                'header'=>'Действия',
                'htmlOptions' => array('nowrap'=>'nowrap'),
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'viewButtonUrl' => 'Yii::app()->urlManager->createUrl("images/view", array("id" => $data->id));',
                'updateButtonUrl'=> null,
                'deleteButtonUrl' => null,
                'buttons' => array(
                    'delete' => array('visible' => 'false')
                ),
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
            )
        ),
        'showTableOnEmpty' => false,
        'emptyText' => ''
    )
);
?>