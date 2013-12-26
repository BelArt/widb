<?php
/* @var $this CollectionsController */
/* @var $model Collections */
/* @var $photoUploadModel XUploadForm */
/* @var $view string имя шаблолна для рендеринга */
?>

<?php $this->renderPartial($view, array('model'=>$model, 'photoUploadModel' => $photoUploadModel)); ?>