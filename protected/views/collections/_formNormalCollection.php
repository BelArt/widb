<?php
/* @var $this CollectionsController */
/* @var $model Collections */
/* @var $photoUploadModel XUploadForm */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerPackage('collectionForm');
Yii::app()->clientScript->registerPackage('uploadFiles');

$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'collections-form', // этот айди также используется в форме подгрузки превью!!!
        'type' => 'horizontal',
        'inlineErrors' => true,
        'htmlOptions' => array(
            'class' => 'collectionForm_form _collectionForm_form',
            'enctype' => 'multipart/form-data'
        ),
    )
);

echo $form->textFieldRow($model,'name', array(
    'class' => 'input-xxlarge _collectionForm_hideErrorsKeypress'
));

echo $form->textAreaRow($model,'description',array(
    'class' => 'input-xxlarge _collectionForm_hideErrorsKeypress',
    'rows' => 5
));

if ($model->isNewRecord) {
    echo $form->textFieldRow($model,'code', array(
        'class' => 'input-xlarge _collectionForm_hideErrorsKeypress'
    ));
}


echo $form->checkBoxRow($model,'has_preview', array('class' => '_collectionForm_hideErrorsChange _hasPreviewCheckbox'));

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

?>

<?php $this->renderPartial('application.views.common._uploadPreviewForm', array(
    'model' => $model,
    'photoUploadModel' => $photoUploadModel,
    'type' => 'collection',
    'formId' => 'collections-form'
)); ?>

<?php

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

