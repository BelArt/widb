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
                //'viewButtonUrl' => 'Yii::app()->urlManager->createUrl("objects/view", array("id" => $data->id));',
                'updateButtonUrl'=> 'Yii::app()->urlManager->createUrl("dictionaries/update", array("id" => $data->id, "type" => "authors"));',
                'deleteButtonUrl' => null,
                'buttons' => array(
                    'view' => array('visible' => 'false')
                )
            )
        ),
        'showTableOnEmpty' => false,
        'emptyText' => ''
    )
);
?>