<?php
/* @var $this DictionariesController */
/* @var $AuthorsDataProvider CActiveDataProvider */
?>

<?php
$this->widget(
    'bootstrap.widgets.TbGridView',
    array(
        'type' => 'striped bordered',
        'dataProvider' => $AuthorsDataProvider,
        'template' => "{items}",
        'columns' => array(
            array(
                'name' => 'surname',
                'sortable' => false
            ),
            array(
                'name' => 'name',
                'sortable' => false
            ),
            array(
                'name' => 'middlename',
                'sortable' => false
            ),
            array(
                'name' => 'initials',
                'sortable' => false
            ),
            array(
                'value' => '!empty($data->sort) ? $data->sort : ""',
                'header' => Yii::t('common', 'Сортировка'),
                'sortable' => false
            ),
            array(
                'header'=>'Действия',
                'htmlOptions' => array('nowrap'=>'nowrap'),
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'updateButtonUrl'=> 'Yii::app()->urlManager->createUrl("dictionaries/update", array("id" => $data->id, "type" => "authors"));',
                'deleteButtonUrl' => 'Yii::app()->urlManager->createUrl("dictionaries/delete", array("id" => $data->id, "type" => "authors"));',
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