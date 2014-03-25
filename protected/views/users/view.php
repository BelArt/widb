<?php
Yii::app()->clientScript->registerPackage('usersView');

$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered',
    'dataProvider' => $UsersDataProvider,
    'template' => "{items}",
    'columns' => array(
        array(
            'name' => 'initials',
            'sortable' => false
        ),
        array(
            'name' => 'position',
            'sortable' => false
        ),
        array(
            'name' => 'email',
            'sortable' => false
        ),
        array(
            'value' => '$data->getRoleText()',
            'header' => Yii::t('admin', 'Роль'),
            'sortable' => false
        ),
        /*array(
            'value' => '!empty($data->sort) ? $data->sort : ""',
            'header' => Yii::t('common', 'Сортировка'),
            'sortable' => false
        ),*/
        array(
            'header'=>'',
            'htmlOptions' => array('nowrap'=>'nowrap'),
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'updateButtonUrl'=> 'Yii::app()->urlManager->createUrl("users/update", array("id" => $data->id));',
            'deleteButtonUrl' => 'Yii::app()->urlManager->createUrl("users/delete", array("id" => $data->id));',
            'buttons' => array(
                'view' => array('visible' => 'false'),
                'update' => array('visible' => Yii::app()->user->checkAccess('oUserEdit') ? 'true' : 'false'),
                /*'delete' => array(
                    'visible' => Yii::app()->user->checkAccess('oUserDelete') ? 'true' : 'false',
                    'options' => array(
                        'data-dialog-title' => CHtml::encode(Yii::t('admin', 'Удалить пользователя?')),
                        'data-dialog-message' => CHtml::encode(Yii::t('admin', 'Вы не сможете его восстановить')),
                        'class' => '_deleteUser'
                    ),
                )*/
                'delete' => array('visible' => 'false'),
            )
        )
    ),
    'showTableOnEmpty' => false,
    'emptyText' => ''
));