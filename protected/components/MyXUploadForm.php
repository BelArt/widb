<?php
/**
 * Расширение с целью переопределить правила валидации
 */

Yii::import( "xupload.models.XUploadForm" );

class MyXUploadForm extends XUploadForm
{
    public function rules()
    {
        return array(
            array('file', 'file', 'types' => 'jpg,jpeg,gif,png', 'maxSize' => 1024 * 1024 * 4),
        );
    }
}
