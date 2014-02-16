<?php
/* @var $this ImagesController */
/* @var $Image Images */
/* @var $photoUploadModel XUploadForm */
?>
<?php $this->renderPartial('_formImage', array('Image'=>$Image, 'photoUploadModel' => $photoUploadModel)); ?>