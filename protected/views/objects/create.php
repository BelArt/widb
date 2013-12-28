<?php
/* @var $this ObjectsController */
/* @var $model Objects */
/* @var $photoUploadModel XUploadForm */
/* @var $view string имя шаблолна для рендеринга */
?>
<?php $this->renderPartial($view, array('model'=>$model, 'photoUploadModel' => $photoUploadModel)); ?>