<?php

Yii::app()->clientScript->registerPackage('userForm');

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'user-form',
    'type' => 'horizontal',
    'inlineErrors' => true,
    'htmlOptions' => array(
        'class' => 'userForm_form _userForm_form',
        //'enctype' => 'multipart/form-data'
    ),
));

echo $form->textFieldRow($User, 'surname', array(
    'class' => 'input-xlarge _userForm_hideErrorsKeypress',
));

echo $form->textFieldRow($User, 'name', array(
    'class' => 'input-xlarge _userForm_hideErrorsKeypress',
));

echo $form->textFieldRow($User, 'middlename', array(
    'class' => 'input-xlarge _userForm_hideErrorsKeypress',
));

echo $form->textFieldRow($User, 'initials', array(
    'class' => 'input-xlarge _userForm_hideErrorsKeypress',
));

echo $form->textFieldRow($User, 'position', array(
    'class' => 'input-large _userForm_hideErrorsKeypress',
));

echo $form->textFieldRow($User, 'email', array(
    'class' => 'input-large _userForm_hideErrorsKeypress',
));

echo $form->select2Row($User, 'role', array(
    'asDropDownList' => true,
    'data' => $User->getArrayOfPossibleRoles(),
    'class' => 'input-large _userForm_hideErrorsChange',
));

echo $form->textFieldRow($User, 'sort', array(
    'class' => 'input-small _userForm_hideErrorsKeypress',
    'value' => $User->sort == '0' ? '' : null
));

echo CHtml::openTag('div', array(
    'class' => 'form-actions',
));

$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $User->isNewRecord ? Yii::t('common', 'Создать') : Yii::t('common', 'Сохранить'),
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
            'class' => '_userForm_resetButton formButton'
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

