<?php
/* @var $this DictionariesController */
/* @var $Model ObjectTypes */

Yii::app()->clientScript->registerPackage('dictionaryForm');

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'dictionary-form',
    'type' => 'horizontal',
    'inlineErrors' => true,
    'htmlOptions' => array(
        'class' => 'dictionaryForm_form _dictionaryForm_form',
        //'enctype' => 'multipart/form-data'
    ),
));

echo $form->textFieldRow($Model, 'name', array(
    'class' => 'input-xlarge _dictionaryForm_hideErrorsKeypress',
));

echo $form->textAreaRow($Model, 'description', array(
    'class' => 'input-xxlarge _dictionaryForm_hideErrorsKeypress',
));

echo $form->textFieldRow($Model, 'sort', array(
    'class' => 'input-small _dictionaryForm_hideErrorsKeypress',
    'value' => $Model->sort == '0' ? '' : null
));

echo CHtml::openTag('div', array(
    'class' => 'form-actions',
));

$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $Model->isNewRecord ? Yii::t('common', 'Создать') : Yii::t('common', 'Сохранить'),
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
            'class' => '_dictionaryForm_resetButton formButton'
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

