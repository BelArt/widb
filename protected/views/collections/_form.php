<?php
/* @var $this CollectionsController */
/* @var $model Collections */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerPackage('collectionForm');

$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'collections-form',
        'type' => 'horizontal',
        'htmlOptions' => array('class' => 'well'), // for inset effect
    )
);

echo $form->textFieldRow($model,'name', array(
    'class' => 'input-xxlarge'
));

echo $form->textAreaRow($model,'description',array(
    'class' => 'input-xxlarge',
    'rows' => 5
));

echo $form->textFieldRow($model,'code', array(
    'class' => 'input-xlarge'
));

echo $form->checkBoxRow($model,'has_preview');

echo $form->checkBoxRow($model,'temporary', array(
    'class' => '_collectionForm_tempCollectionCheckbox',
));

echo CHtml::openTag('div', array(
    'class' => '_collectionForm_tempCollectionPublicBlock',
    'style' => 'display:none;'
));
echo $form->checkBoxRow($model,'public', array(
    'disabled' => true,
    'class' => '_collectionForm_tempCollectionPublicCheckbox',
));
echo CHtml::closeTag('div');

echo $form->textFieldRow($model,'sort', array(
    'class' => 'input-small'
));

echo CHtml::openTag('div', array(
    'class' => 'form-actions',
));
$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? 'Создать' : 'Сохранить'
    )
);
$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'buttonType' => 'reset',
        'label' => 'Сбросить',
        'htmlOptions' => array(
            'class' => 'collectionForm_resetButton _collectionForm_resetButton'
        )
    )
);
echo CHtml::closeTag('div');

$this->endWidget();
unset($form);

