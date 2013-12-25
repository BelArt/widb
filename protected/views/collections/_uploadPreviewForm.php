<div class="control-group">
    <label class="control-label"><?= $model->getAttributeLabel('preview') ?></label>
    <div class="controls">

        <?php if(!$model->isNewRecord && $model->has_preview && $model->reallyHasPreview()): ?>
            <div class="_collectionForm_previewBlock">

                <div class="collectionForm_preview">
                    <a class="thumbnail">
                        <img src="<?= $model->ThumbnailMedium ?>" />
                    </a>
                </div>

                <div class="collectionForm_deletePreviewButtonBlock">
                    <?php
                    $this->widget(
                        'bootstrap.widgets.TbButton',
                        array(
                            'label' => Yii::t('common', 'Удалить'),
                            'size' => 'small',
                            'type' => 'danger',
                            'htmlOptions' => array(
                                'class' => '_collectionForm_deletePreviewButton',
                                'data-type' => 'collection',
                                'data-id' => $model->id
                            )
                            //'icon' => 'remove white'
                        )
                    );
                    ?>
                </div>

            </div>
        <?php endif; ?>

        <div class="_collectionForm_xuploadBlock collectionForm_xuploadBlock" style="<?= ($model->has_preview && $model->reallyHasPreview() ? 'display:none;' : '') ?>">
            <?php
            $this->widget(
                'xupload.XUpload',
                array(
                    'url' => Yii::app( )->createUrl("site/upload"),
                    'model' => $photoUploadModel,
                    //We set this for the widget to be able to target our own form
                    'htmlOptions' => array('id'=>'collections-form'),
                    'attribute' => 'file',
                    'multiple' => false,
                    'showForm' => false,
                    'formView' => 'formCustom'
                )
            );
            ?>
        </div>

    </div>
</div>