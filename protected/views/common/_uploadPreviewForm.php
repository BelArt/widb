<div class="control-group">
    <label class="control-label"><?= $model->getAttributeLabel('preview') ?></label>
    <div class="controls">

        <?php if(!$model->isNewRecord && $model->has_preview && $model->reallyHasPreview()): ?>
            <div class="_uploadFiles_previewBlock">

                <div class="uploadFiles_preview">
                    <a class="thumbnail">
                        <img src="<?= $model->ThumbnailMedium ?>" />
                    </a>
                </div>

                <div>
                    <?php
                    $this->widget(
                        'bootstrap.widgets.TbButton',
                        array(
                            'label' => Yii::t('common', 'Удалить'),
                            'size' => 'small',
                            'type' => 'danger',
                            'htmlOptions' => array(
                                'class' => '_uploadFiles_deletePreviewButton',
                                'data-type' => $type,
                                'data-id' => $model->id
                            )
                            //'icon' => 'remove white'
                        )
                    );
                    ?>
                </div>

            </div>
        <?php endif; ?>

        <div class="_uploadFiles_xuploadBlock" style="<?= ($model->has_preview && $model->reallyHasPreview() ? 'display:none;' : '') ?>">
            <?php
            $this->widget(
                'xupload.XUpload',
                array(
                    'url' => Yii::app( )->createUrl("site/upload"),
                    'model' => $photoUploadModel,
                    //We set this for the widget to be able to target our own form
                    'htmlOptions' => array('id' => $formId),
                    'attribute' => 'file',
                    'multiple' => false,
                    'showForm' => false,
                    'formView' => 'formCustom',
                )
            );
            ?>
        </div>

    </div>
</div>