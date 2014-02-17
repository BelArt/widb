<?php
/* @var $this ImagesController */
/* @var $Images Images */
/* @var $photoUploadModel XUploadForm */
?>
<?php $this->renderPartial('_formImage', array('Image'=>$Image, 'photoUploadModel' => $photoUploadModel)); ?>