<?php
/* @var $this ImagesController */
/* @var $Image Images */
/* @var $photoUploadModel XUploadForm */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerPackage('imageForm');
Yii::app()->clientScript->registerPackage('uploadFiles');

$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'image-form', // этот айди также используется в форме подгрузки превью!!!
        'type' => 'horizontal',
        'inlineErrors' => true,
        'htmlOptions' => array(
            'class' => 'imageForm_form _imageForm_form',
            'enctype' => 'multipart/form-data'
        ),
    )
);

echo $form->select2Row($Image,'photo_type_id',
    array(
        'asDropDownList' => true,
        'data' => $Image->getArrayOfPhotoTypes(),
        'class' => 'input-xlarge _imageForm_hideErrorsChange',
    )
);

echo $form->textAreaRow($Image,'description',array(
    'class' => 'input-xxlarge _imageForm_hideErrorsKeypress',
    'rows' => 5
));

echo $form->checkBoxRow($Image,'has_preview', array('class' => '_imageForm_hideErrorsChange _hasPreviewCheckbox'));

echo $form->textFieldRow($Image,'width', array(
    'class' => 'input-small _imageForm_hideErrorsKeypress',
    'value' => $Image->width == '0' ? '' : null,
    'append' => Yii::t('common', 'px')
));

echo $form->textFieldRow($Image,'height', array(
    'class' => 'input-small _imageForm_hideErrorsKeypress',
    'value' => $Image->height == '0' ? '' : null,
    'append' => Yii::t('common', 'px')
));

echo $form->textFieldRow($Image,'width_cm', array(
    'class' => 'input-small _imageForm_hideErrorsKeypress',
    'value' => $Image->width_cm == '0.00' ? '' : MyOutputHelper::formatNumber($Image->width_cm),
    'append' => Yii::t('common', 'см')
));

echo $form->textFieldRow($Image,'height_cm', array(
    'class' => 'input-small _imageForm_hideErrorsKeypress',
    'value' => $Image->height_cm == '0.00' ? '' : MyOutputHelper::formatNumber($Image->height_cm),
    'append' => Yii::t('common', 'см')
));

echo $form->textFieldRow($Image,'dpi', array(
    'class' => 'input-small _imageForm_hideErrorsKeypress',
    'value' => $Image->dpi == '0' ? '' : null,
    //'append' => Yii::t('common', 'dpi')
));

echo $form->textFieldRow($Image,'original', array(
    'class' => 'input-xlarge _imageForm_hideErrorsKeypress'
));

echo $form->textFieldRow($Image,'source', array(
    'class' => 'input-xlarge _imageForm_hideErrorsKeypress'
));

echo $form->checkBoxRow($Image,'deepzoom', array('class' => '_imageForm_hideErrorsChange'));

echo $form->textFieldRow($Image,'request', array(
    'class' => 'input-large _imageForm_hideErrorsKeypress'
));

if ($Image->isNewRecord) {
    echo $form->textFieldRow($Image,'code', array(
        'class' => 'input-small _imageForm_hideErrorsKeypress'
    ));
}

echo $form->textFieldRow($Image,'sort', array(
    'class' => 'input-small _imageForm_hideErrorsKeypress',
    'value' => empty($Image->sort) ? '' : null
));

echo $form->datepickerRow($Image, 'date_photo', array(
        'value' => $Image->date_photo == '0000-00-00' ? CHtml::encode(Yii::app()->dateFormatter->formatDateTime(time(), 'medium', null)) : CHtml::encode(Yii::app()->dateFormatter->formatDateTime(strtotime($Image->date_photo), 'medium', null)),
        'prepend' => '<i class="icon-calendar"></i>',
        'options' => array(
            'format' => 'dd.mm.yyyy',
            'language' => 'ru',
            'autoclose' => true,
        ),
    )
);

$this->renderPartial('application.views.common._uploadPreviewForm', array(
    'model' => $Image,
    'photoUploadModel' => $photoUploadModel,
    'type' => 'image',
    'formId' => 'image-form'
));

echo CHtml::openTag('div', array(
    'class' => 'form-actions',
));

$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $Image->isNewRecord ? Yii::t('common', 'Создать') : Yii::t('common', 'Сохранить'),
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
            'class' => '_imageForm_resetButton formButton'
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

