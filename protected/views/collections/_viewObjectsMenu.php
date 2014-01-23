<?php
/* @var $model Collections */

if (!$model->temporary) {

    $items = array();
    $items[] = array(
        'label' => Yii::t('common', 'С отмеченными'),
        'itemOptions' => array('class' => 'nav-header')
    );
    $items[] = array(
        'label' => Yii::t('collections', 'Добавить во временную коллекцию'),
        'url' => '#',
        'itemOptions' => array('class' => 'small')
    );
    $items[] = array(
        'label' => Yii::t('common', 'Переместить'),
        'url' => '#',
        'itemOptions' => array('class' => 'small')
    );
    if (Yii::app()->user->checkAccess('oObjectDelete')) {
        $items[] = array(
            'label' => Yii::t('common', 'Удалить'),
            'url' => '#',
            'itemOptions' => array(
                'class' => 'small _deleteSelectedObjects',
                'data-dialog-title' => CHtml::encode(Yii::t('objects', 'Удалить выбранные объекты?')),
                'data-dialog-message' => CHtml::encode(Yii::t('objects', 'Выбранные объекты будут удалены, и их нельзя будет восстановить')),
            )
        );
    }

    $this->widget(
        'bootstrap.widgets.TbMenu',
        array(
            'type' => 'pills',
            'items' => $items
        )
    );

} else {

    $this->widget(
        'bootstrap.widgets.TbMenu',
        array(
            'type' => 'pills',
            'items' => array(
                array(
                    'label' => Yii::t('common', 'С отмеченными'),
                    'itemOptions' => array('class' => 'nav-header')
                ),
                array(
                    'label' => Yii::t('collections', 'Удалить из временной коллекции'),
                    'url' => '#',
                    'itemOptions' => array(
                        'class' => 'small _deleteSelectedObjectsFromTempCollection',
                        'data-dialog-title' => CHtml::encode(Yii::t('objects', 'Удалить выбранные объекты из временной коллекции?')),
                        'data-dialog-message' => CHtml::encode(Yii::t('objects', 'Выбранные объекты будут удалены из временной коллекции')),
                        'data-temp-collection-id' => $model->id
                    )
                ),
            )
        )
    );
}

echo CHtml::openTag('div', array(
    'class' => 'gapeSmall',
));
echo CHtml::closeTag('div');
?>

<?php

$classThumbnails = (empty($_GET['ov']) || $_GET['ov'] == 'th') ? 'selected' : '';
$classList = (!empty($_GET['ov']) && $_GET['ov'] == 'ls') ? 'selected' : '';
$classTable = (!empty($_GET['ov']) && $_GET['ov'] == 'tb') ? 'selected' : '';

$this->widget(
    'bootstrap.widgets.TbMenu',
    array(
        'type' => 'pills',
        'items' => array(
            array(
                'label' => Yii::t('common', 'Отображение'),
                'itemOptions' => array('class' => 'nav-header')
            ),
            array(
                'label' => Yii::t('common', 'Картинками'),
                'url' => $this->createUrl($model->temporary ? 'collections/viewTemp' : 'collections/view', array('id' => $model->id, 'ov' => 'th', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''), 'tb' => 'ob')),
                'itemOptions' => array('class' => 'small '.$classThumbnails)
            ),
            array(
                'label' => Yii::t('common', 'Списком'),
                'url' => $this->createUrl($model->temporary ? 'collections/viewTemp' : 'collections/view', array('id' => $model->id, 'ov' => 'ls', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''), 'tb' => 'ob')),
                'itemOptions' => array('class' => 'small '.$classList)
            ),
            array(
                'label' => Yii::t('common', 'Таблицей'),
                'url' => $this->createUrl($model->temporary ? 'collections/viewTemp' : 'collections/view', array('id' => $model->id, 'ov' => 'tb', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''), 'tb' => 'ob')),
                'itemOptions' => array('class' => 'small '.$classTable)
            ),
        )
    )
);

?>

<div class="gape"></div>