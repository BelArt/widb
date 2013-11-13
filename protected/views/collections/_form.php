<?php
/* @var $this CollectionsController */
/* @var $model Collections */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerPackage('collectionForm');

$isTempCollection = $model->temporary;

$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'collections-form',
        'type' => 'horizontal',
        'inlineErrors' => true,
        'htmlOptions' => array('class' => 'well'), // for inset effect
    )
);

echo $form->checkBoxRow($model,'temporary', array(
    'class' => '_collectionForm_tempCollectionCheckbox',
));

echo $form->textFieldRow($model,'name', array(
    'class' => 'input-xxlarge _collectionForm_hideErrorsKeypress'
));

echo $form->textAreaRow($model,'description',array(
    'class' => 'input-xxlarge _collectionForm_hideErrorsKeypress',
    'rows' => 5
));

echo $form->textFieldRow($model,'code', array(
    'class' => 'input-xlarge _collectionForm_hideErrorsKeypress'
));

echo $form->checkBoxRow($model,'has_preview');

echo CHtml::openTag('div', array(
    'class' => '_collectionForm_parentCollectionBlock',
    'style' => ' '.($isTempCollection ? 'display:none;' : '')
));
echo $form->select2Row($model,'parent_id',
    array(
        'asDropDownList' => true,
        'data' => $model->getArrayOfPossibleParentCollections(),
        'class' => 'input-xxlarge _collectionForm_parentCollectionSelect',
        'disabled' => $isTempCollection,
    )
);
echo CHtml::closeTag('div');

echo CHtml::openTag('div', array(
    'class' => '_collectionForm_tempCollectionPublicBlock',
    'style' => ' '.($isTempCollection ? '' : 'display:none;')
));
echo $form->checkBoxRow($model,'temporary_public', array(
    'disabled' => !$isTempCollection,
    'class' => '_collectionForm_tempCollectionPublicCheckbox',
));
echo CHtml::closeTag('div');

echo $form->textFieldRow($model,'sort', array(
    'class' => 'input-small _collectionForm_hideErrorsKeypress',
    'value' => empty($model->sort) ? '' : null
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

