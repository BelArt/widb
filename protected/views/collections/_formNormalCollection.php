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
        'inlineErrors' => true,
        'htmlOptions' => array('class' => 'collectionForm_form _collectionForm_form'), // for inset effect
    )
);

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

echo $form->checkBoxRow($model,'has_preview', array('class' => '_collectionForm_hideErrorsChange'));

echo $form->select2Row($model,'parent_id',
    array(
        'asDropDownList' => true,
        'data' => $model->getArrayOfPossibleNormalParentCollections(),
        'class' => 'input-xxlarge _collectionForm_hideErrorsChange',
    )
);

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
        'label' => $model->isNewRecord ? 'Создать' : 'Сохранить',
        'htmlOptions' => array(
            'class' => 'formButton'
        )
    )
);

$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'buttonType' => 'reset',
        'label' => 'Сбросить',
        'htmlOptions' => array(
            'class' => '_collectionForm_resetButton formButton'
        )
    )
);

$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'buttonType' => 'link',
        'url' => Yii::app()->request->urlReferrer,
        'label' => 'Отмена',
        'htmlOptions' => array(
            'class' => 'formButton'
        )
    )
);

echo CHtml::closeTag('div');

$this->endWidget();
unset($form);

