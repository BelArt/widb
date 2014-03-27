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

echo CHtml::tag('hr');

echo CHtml::openTag('div', array('class' => 'control-group'));
echo CHtml::label(Yii::t('admin', 'Новый пароль'), 'Users_newPassword', array('class' => 'control-label'));
echo CHtml::openTag('div', array('class' => 'controls'));
echo CHtml::passwordField('Users[newPassword]', $User->newPassword, array('class' => '_passwordField'));
echo CHtml::closeTag('div');
echo CHtml::closeTag('div');
echo CHtml::openTag('div', array('class' => 'control-group'));
echo CHtml::label(Yii::t('admin', 'Повторите новый пароль'), 'Users_repeatNewPassword', array('class' => 'control-label'));
echo CHtml::openTag('div', array('class' => 'controls'));
echo CHtml::passwordField('Users[repeatNewPassword]', $User->repeatNewPassword, array('class' => '_repeatPasswordField'));
echo CHtml::openTag('span', array('class' => '_passwordErrorMessage passwordErrorMessage', 'style' => $User->hasErrors('password') ? '' : 'display:none;'));
echo CHtml::encode(Yii::t('admin', 'Пароли не совпадают!'));
echo CHtml::closeTag('span');
echo CHtml::closeTag('div');
echo CHtml::closeTag('div');

// выбор доступных коллекций делаем только для обычных пользователей - для КМ и админов нет смысла делать, им доступно все
// также выбор делаем при создании пользователя
if ($User->isNewRecord || $User->role == 'user') {

    echo CHtml::tag('hr');

    echo CHtml::openTag('div', array('class' => 'control-group'));
    echo CHtml::label(Yii::t('admin', 'Коллекции, доступные пользователю'), 'Users_allowedCollections', array('class' => 'control-label'));
    echo CHtml::openTag('div', array('class' => 'controls'));
    $this->widget('bootstrap.widgets.TbSelect2', array(
        'asDropDownList' => true,
        'name' => 'Users[allowedCollections]',
        'data' => Collections::getAllCollectionsArrayForFormSelect(),
        'htmlOptions' => array(
            'multiple' => 'multiple',
            'id' => 'Users_allowedCollections'
        ),
        'value' => $User->getIdsOfAllowedCollectionsForFormSelect()
    ));
    echo CHtml::closeTag('div');
    echo CHtml::closeTag('div');
}




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

