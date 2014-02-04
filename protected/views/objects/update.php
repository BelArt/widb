<?php
/* @var $this ObjectsController */
/* @var $Object Objects */
/* @var $photoUploadModel XUploadForm */
/* @var $view string имя шаблолна для рендеринга */
?>

<?php $this->renderPartial($view, array('model' => $Object, 'photoUploadModel' => $photoUploadModel)); ?>