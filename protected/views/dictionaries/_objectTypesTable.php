<?php
/* @var $this DictionariesController */
/* @var $ObjectTypesDataProvider CActiveDataProvider */
?>

<?php
$this->widget(
    'bootstrap.widgets.TbGridView',
    array(
        'type' => 'striped bordered',
        'dataProvider' => $ObjectTypesDataProvider,
        'template' => '{items}'.PHP_EOL.'{pager}',
        'columns' => array(
            array(
                'name' => 'name',
                'sortable' => false
            ),
            array(
                'name' => 'description',
                'sortable' => false
            ),
            array(
                'value' => '!empty($data->sort) ? $data->sort : ""',
                'header' => Yii::t('common', 'Сортировка'),
                'sortable' => false
            ),
            array(
                'header'=>'',
                'htmlOptions' => array(
                    'nowrap' => 'nowrap',
                    'class' => 'valignedMiddle hidden-print'
                ),
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter hidden-print'
                ),
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'updateButtonUrl'=> 'Yii::app()->urlManager->createUrl("dictionaries/update", array("id" => $data->id, "type" => "object_types"));',
                'deleteButtonUrl' => 'Yii::app()->urlManager->createUrl("dictionaries/delete", array("id" => $data->id, "type" => "object_types"));',
                'buttons' => array(
                    'view' => array('visible' => 'false'),
                    'update' => array('visible' => Yii::app()->user->checkAccess('oDictionaryRecordEdit') ? 'true' : 'false'),
                    'delete' => array(
                        'visible' => Yii::app()->user->checkAccess('oDictionaryRecordDelete') ? 'true' : 'false',
                        'options' => array(
                            'data-dialog-title' => CHtml::encode(Yii::t('admin', 'Удалить запись справочника?')),
                            'data-dialog-message' => CHtml::encode(Yii::t('admin', 'Вы не сможете ее восстановить')),
                            'class' => '_deleteDictionaryRecord'
                        ),
                    )
                )
            )
        ),
        'showTableOnEmpty' => false,
        'emptyText' => ''
    )
);
?>