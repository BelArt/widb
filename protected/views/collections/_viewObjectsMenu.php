<?php
/* @var $model Collections */
/* @var $tempCollectionsAllowedToUser array */
/* @var $collectionsToMoveTo array */

?>
<div class="childrenMenuLevel1Wrapper">
    <?php

    $classThumbnails = (empty($_GET['ov']) || $_GET['ov'] == 'th') ? 'selected' : '';
    $classList = (!empty($_GET['ov']) && $_GET['ov'] == 'ls') ? 'selected' : '';
    $classTable = (!empty($_GET['ov']) && $_GET['ov'] == 'tb') ? 'selected' : '';

    $this->widget('widgets.children_menu.ChildrenMenu', array(
        'menuName' => Yii::t('common', 'Отображение'),
        'menuItems' => array(
            array(
                'label' => Yii::t('common', 'Картинками'),
                'url' => $this->createUrl($model->temporary ? 'collections/viewTemp' : 'collections/view', array('id' => $model->id, 'ov' => 'th', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''), 'tb' => 'ob')),
                'tdOptions' => array('class' => 'childrenMenuItem '.$classThumbnails),
                'iconType' => 'thumbs',
            ),
            array(
                'label' => Yii::t('common', 'Списком'),
                'url' => $this->createUrl($model->temporary ? 'collections/viewTemp' : 'collections/view', array('id' => $model->id, 'ov' => 'ls', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''), 'tb' => 'ob')),
                'tdOptions' => array('class' => 'childrenMenuItem '.$classList),
                'iconType' => 'list',
            ),
            array(
                'label' => Yii::t('common', 'Таблицей'),
                'url' => $this->createUrl($model->temporary ? 'collections/viewTemp' : 'collections/view', array('id' => $model->id, 'ov' => 'tb', 'cv' => (!empty($_GET['cv']) ? $_GET['cv'] : ''), 'tb' => 'ob')),
                'tdOptions' => array('class' => 'childrenMenuItem '.$classTable),
                'iconType' => 'table',
            ),
        )
    ));

    ?>
</div>

<div class="childrenMenuLevel2Wrapper">
    <?php
    if (!$model->temporary) {

        $items = array();

        if (!empty($tempCollectionsAllowedToUser)) {
            $items[] = array(
                'label' => Yii::t('collections', 'Добавить во временную коллекцию'),
                'url' => '#',
                'itemOptions' => array(
                    'class' => '_addObjectsToTempCollection',
                    'data-dialog-title' => Yii::t('objects', 'Выберите временную коллекцию, в которую хотите добавить объекты'),
                    'data-dialog-message' => $this->renderPartial('_tempCollectionsSelect', array('tempCollections' => $tempCollectionsAllowedToUser), true),
                ),
                'tdOptions' => array('class' => 'childrenMenuItem'),
                'iconType' => 'add_to_temp',
            );
        }

        if (!empty($collectionsToMoveTo) && Yii::app()->user->checkAccess('oChangeObjectsCollection')) {
            $items[] = array(
                'label' => Yii::t('common', 'Переместить'),
                'url' => '#',
                'itemOptions' => array(
                    'class' => '_moveObjectsToOtherCollection',
                    'data-dialog-title' => Yii::t('objects', 'Выберите коллекцию, в которую хотите переместить объект/объекты'),
                    'data-dialog-message' => $this->renderPartial('_collectionsToMoveToSelect', array('collectionsToMoveTo' => $collectionsToMoveTo), true),
                ),
                'tdOptions' => array('class' => 'childrenMenuItem'),
                'iconType' => 'move',
            );
        }

        if (Yii::app()->user->checkAccess('oObjectDelete')) {
            $items[] = array(
                'label' => Yii::t('common', 'Удалить'),
                'url' => '#',
                'itemOptions' => array(
                    'class' => '_deleteSelectedObjects',
                    'data-dialog-title' => Yii::t('objects', 'Удалить выбранные объекты?'),
                    'data-dialog-message' => Yii::t('objects', 'Выбранные объекты будут удалены, и их нельзя будет восстановить'),
                ),
                'tdOptions' => array('class' => 'childrenMenuItem'),
                'iconType' => 'delete',
            );
        }

        $this->widget('widgets.children_menu.ChildrenMenu', array(
            'menuName' => Yii::t('common', 'С отмеченными'),
            'menuItems' => $items
        ));

    } else {

        $this->widget('widgets.children_menu.ChildrenMenu', array(
            'menuName' => Yii::t('common', 'С отмеченными'),
            'menuItems' => array(
                array(
                    'label' => Yii::t('collections', 'Удалить из временной коллекции'),
                    'url' => '#',
                    'itemOptions' => array(
                        'class' => '_deleteSelectedObjectsFromTempCollection',
                        'data-dialog-title' => Yii::t('objects', 'Удалить выбранные объекты из временной коллекции?'),
                        'data-dialog-message' => Yii::t('objects', 'Выбранные объекты будут удалены из временной коллекции'),
                        'data-temp-collection-id' => $model->id
                    ),
                    'tdOptions' => array('class' => 'childrenMenuItem'),
                    'iconType' => 'delete_from_temp',
                ),
            )
        ));
    }

    /*echo CHtml::openTag('div', array(
        'class' => 'gapeSmall',
    ));
    echo CHtml::closeTag('div');*/
    ?>
</div>

<div class="childrenMenuLevel3Wrapper">
    <?php
    $this->widget('widgets.per_page_toggle.PerPageToggle', array(
        'varName' => 'opp'
    ));
    ?>
</div>

<div class="clear"></div>

<div class="gapeSmall"></div>