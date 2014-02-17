<?php
/* @var $this DictionariesController */
/* @var $AuthorsDataProvider CActiveDataProvider */

Yii::app()->clientScript->registerPackage('dictionariesView');
?>

<?php
$this->widget('bootstrap.widgets.TbTabs', array(
    'type' => 'tabs', // 'tabs' or 'pills'
    //'placement' => 'below',
    'tabs' => array(
        array(
            'label' => Yii::t('admin', 'Авторы'),
            'content' => $this->renderPartial('_authorsTable', array(
                    'AuthorsDataProvider' => $AuthorsDataProvider
                ), true),
            'active' => true
        ),
    ),
));
?>



