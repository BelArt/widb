<?php
/* @var $this DictionariesController */
/* @var $AuthorsDataProvider CActiveDataProvider */
/* @var $ObjectTypesDataProvider CActiveDataProvider */
/* @var $PhotoTypesDataProvider CActiveDataProvider */

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
        array(
            'label' => Yii::t('admin', 'Типы объектов'),
            'content' => $this->renderPartial('_objectTypesTable', array(
                    'ObjectTypesDataProvider' => $ObjectTypesDataProvider
                ), true),
        ),
        array(
            'label' => Yii::t('admin', 'Типы съемки'),
            'content' => $this->renderPartial('_photoTypesTable', array(
                    'PhotoTypesDataProvider' => $PhotoTypesDataProvider
                ), true),
        ),
    ),
));
?>



