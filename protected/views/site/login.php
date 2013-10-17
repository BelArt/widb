<?php
Yii::app()->clientScript->registerPackage('loginForm');

/** @var TbActiveForm $form */
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'LoginForm',
        'htmlOptions' => array(
            'class' => 'well', // for inset effect
            'style' => 'width: 300px; margin: 0 auto;'
        ),
        //'enableClientValidation'=>true,
        //'clientOptions'=>array('validateOnSubmit' => true),
    )
);

echo $form->textFieldRow($model, 'username', array(
    'class' => 'span3',
    'onkeypress' => 'hideErrors($(this))'
));
echo $form->passwordFieldRow($model, 'password', array(
    'class' => 'span3',
    'onkeypress' => 'hideErrors($(this))'
));
echo $form->checkboxRow($model, 'rememberMe');

$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'buttonType' => 'submit',
        'label' => 'Войти'
    )
);

$this->endWidget();
unset($form);
?>
