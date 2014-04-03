<?php
/* @var $this ObjectsController */
/* @var $model Objects */
/* @var $photoUploadModel XUploadForm */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerPackage('objectForm');
Yii::app()->clientScript->registerPackage('uploadFiles');

$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'object-form', // этот айди также используется в форме подгрузки превью!!!
        'type' => 'horizontal',
        'inlineErrors' => true,
        'htmlOptions' => array(
            'class' => 'objectForm_form _objectForm_form',
            'enctype' => 'multipart/form-data'
        ),
    )
);

echo $form->textFieldRow($model,'name', array(
    'class' => 'input-xxlarge _objectForm_hideErrorsKeypress',
));

echo $form->textAreaRow($model,'description',array(
    'class' => 'input-xxlarge _objectForm_hideErrorsKeypress',
    'rows' => 5
));

echo $form->select2Row($model,'author_id',
    array(
        'asDropDownList' => true,
        'data' => $model->getArrayOfPossibleAuthors(),
        'class' => 'input-xlarge _objectForm_hideErrorsChange',
    )
);

echo $form->textFieldRow($model,'author_text', array(
    'class' => 'input-xlarge _objectForm_hideErrorsKeypress',
));

echo $form->select2Row($model,'type_id',
    array(
        'asDropDownList' => true,
        'data' => $model->getArrayOfPossibleObjectTypes(),
        'class' => 'input-medium _objectForm_hideErrorsChange',
    )
);

echo $form->textFieldRow($model,'period', array(
    'class' => 'input-xlarge _objectForm_hideErrorsKeypress'
));

echo $form->textFieldRow($model,'inventory_number', array(
    'class' => 'input-small _objectForm_hideErrorsKeypress _translitSource'
));

if ($model->isNewRecord) {
    echo $form->textFieldRow($model,'code', array(
        'class' => 'input-small _objectForm_hideErrorsChange _translitDestination'
    ));
}

echo $form->textFieldRow($model,'width', array(
    'class' => 'input-small _objectForm_hideErrorsKeypress',
    'value' => $model->width == '0.00' ? '' : OutputHelper::formatNumber($model->width),
    'append' => Yii::t('common', 'см')
));

echo $form->textFieldRow($model,'height', array(
    'class' => 'input-small _objectForm_hideErrorsKeypress',
    'value' => $model->height == '0.00' ? '' : OutputHelper::formatNumber($model->height),
    'append' => Yii::t('common', 'см')
));

echo $form->textFieldRow($model,'depth', array(
    'class' => 'input-small _objectForm_hideErrorsKeypress',
    'value' => $model->depth == '0.00' ? '' : OutputHelper::formatNumber($model->depth),
    'append' => Yii::t('common', 'см')
));

echo $form->checkBoxRow($model,'has_preview', array('class' => '_objectForm_hideErrorsChange _hasPreviewCheckbox'));

echo $form->textFieldRow($model,'department', array(
    'class' => 'input-xxlarge _objectForm_hideErrorsKeypress'
));

echo $form->textFieldRow($model,'keeper', array(
    'class' => 'input-large _objectForm_hideErrorsKeypress'
));

echo $form->textFieldRow($model,'sort', array(
    'class' => 'input-small _objectForm_hideErrorsKeypress',
    'value' => empty($model->sort) ? '' : null
));

$this->renderPartial('application.views.common._uploadPreviewForm', array(
    'model' => $model,
    'photoUploadModel' => $photoUploadModel,
    'type' => 'object',
    'formId' => 'object-form'
));

echo CHtml::openTag('div', array(
    'class' => 'form-actions',
));

$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('common', 'Создать') : Yii::t('common', 'Сохранить'),
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
            'class' => '_objectForm_resetButton formButton'
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

/*






*/?>

<?php

$this->endWidget();
unset($form);

