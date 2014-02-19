<?php

return array(
    /*
     * Операции
     */
    // Пользователи
    'oUsersView' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Просмотр списка всех Пользователей',
        'bizRule' => null,
        'data' => null
    ),
    'oUserView' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Просмотр Пользователя',
        'bizRule' => null,
        'data' => null
    ),
    'oUserCreate' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Создание Пользователя',
        'bizRule' => null,
        'data' => null
    ),
    'oUserEdit' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Редактирование Пользователя',
        'bizRule' => null,
        'data' => null
    ),
    'oUserDelete' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Удаление Пользователя',
        'bizRule' => null,
        'data' => null
    ),
    // Справочники
    'oDictionariesView' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Просмотр всех Справочников',
        'bizRule' => null,
        'data' => null
    ),
    'oDictionaryRecordCreate' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Создание записи в Справочнике',
        'bizRule' => null,
        'data' => null
    ),
    'oDictionaryRecordEdit' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Редактирование записи в Справочнике',
        'bizRule' => null,
        'data' => null
    ),
    'oDictionaryRecordDelete' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Удаление записи в Справочнике',
        'bizRule' => null,
        'data' => null
    ),
    // Изображения
    'oImagesView' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Просмотр списка всех Изображений',
        'bizRule' => null,
        'data' => null
    ),
    'oImageView' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Просмотр Изображения',
        'bizRule' => null,
        'data' => null
    ),
    'oImageCreate' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Создание Изображения',
        'bizRule' => null,
        'data' => null
    ),
    'oImageEdit' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Редактирование Изображения',
        'bizRule' => null,
        'data' => null
    ),
    'oImageDelete' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Удаление Изображения',
        'bizRule' => null,
        'data' => null
    ),
    // Объекты
    'oObjectsView' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Просмотр списка всех Объектов',
        'bizRule' => null,
        'data' => null
    ),
    'oObjectView' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Просмотр Объекта',
        'bizRule' => null,
        'data' => null
    ),
    'oObjectCreate' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Создание Объекта',
        'bizRule' => null,
        'data' => null
    ),
    'oObjectEdit' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Редактирование Объекта',
        'bizRule' => null,
        'data' => null
    ),
    'oObjectDelete' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Удаление Объекта',
        'bizRule' => null,
        'data' => null
    ),
    // Коллекции
    'oCollectionsView' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Просмотр списка всех Коллекций',
        'bizRule' => null,
        'data' => null
    ),
    'oCollectionView' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Просмотр Коллекции',
        'bizRule' => null,
        'data' => null
    ),
    'oCollectionCreate' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Создание Коллекции',
        'bizRule' => null,
        'data' => null
    ),
    'oCollectionEdit' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Редактирование Коллекции',
        'bizRule' => null,
        'data' => null
    ),
    'oCollectionDelete' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Удаление Коллекции',
        'bizRule' => null,
        'data' => null
    ),
    // Временные коллекции
    'oTempCollectionView' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Просмотр Временной коллекции',
        'bizRule' => null,
        'data' => null
    ),
    'oTempCollectionCreate' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Создание Временной коллекции',
        'bizRule' => null,
        'data' => null
    ),
    'oTempCollectionEdit' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Редактирование Временной коллекции',
        'bizRule' => null,
        'data' => null
    ),
    'oTempCollectionDelete' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Удаление Временной коллекции',
        'bizRule' => null,
        'data' => null
    ),
    'oObjectToTempCollectionAdd_Collection' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Добавление Объекта во Временную коллекцию - для Временной коллекции',
        'bizRule' => null,
        'data' => null
    ),
    'oObjectToTempCollectionAdd_Object' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Добавление Объекта во Временную коллекцию - для Коллекции Объекта',
        'bizRule' => null,
        'data' => null
    ),
    'oObjectFromTempCollectionDelete_Collection' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Удаление Объекта из Временной коллекции - для Временной коллекции',
        'bizRule' => null,
        'data' => null
    ),
    'oObjectFromTempCollectionDelete_Object' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Удаление Объекта из Временной коллекции - для Коллекции Объекта',
        'bizRule' => null,
        'data' => null
    ),
    'oChangeObjectsCollection' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => 'Перемещение Объекта из одной Коллекции в другую',
        'bizRule' => null,
        'data' => null
    ),
    /*
     * Таски
     */
    'tCollectionIsAllowed' => array(
        'type' => CAuthItem::TYPE_TASK,
        'description' => 'Доступная Коллекция',
        'bizRule' => 'return $params["Collection"]->isAllowedToUser(Yii::app()->user->id);',
        'data' => null,
        'children' => array(
            'tTempCollectionIsOwn',
            'oTempCollectionView',
            'oCollectionView',
            'oObjectView',
            'oImageView',
            'oObjectFromTempCollectionDelete_Object',
            'oObjectToTempCollectionAdd_Object'
        ),
    ),
    'tTempCollectionIsOwn' => array(
        'type' => CAuthItem::TYPE_TASK,
        'description' => 'Своя Временная коллекция',
        'bizRule' => 'return $params["Collection"]->temporary && (Yii::app()->user->id == $params["Collection"]->user_create);',
        'data' => null,
        'children' => array(
            'oTempCollectionEdit',
            'oTempCollectionDelete',
            'oObjectFromTempCollectionDelete_Collection',
            'oObjectToTempCollectionAdd_Collection'
        ),
    ),
    /*
     * Роли
     */
    'administrator' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Администратор',
        'bizRule' => null,
        'data' => null,
        'children' => array(
            'oUsersView',
            'oUserView',
            'oUserCreate',
            'oUserEdit',
            'oUserDelete',
            'contentManager',
        ),
    ),
    'contentManager' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Контент-менеджер',
        'bizRule' => null,
        'data' => null,
        'children' => array(
            // Справочники
            'oDictionariesView',
            'oDictionaryRecordCreate',
            'oDictionaryRecordEdit',
            'oDictionaryRecordDelete',
            // Изображения
            'oImagesView',
            'oImageView',
            'oImageCreate',
            'oImageEdit',
            'oImageDelete',
            // Объекты
            'oObjectsView',
            'oObjectView',
            'oObjectCreate',
            'oObjectEdit',
            'oObjectDelete',
            'oChangeObjectsCollection',
            // Коллекции
            'oCollectionsView',
            'oCollectionView',
            'oCollectionCreate',
            'oCollectionEdit',
            'oCollectionDelete',
            // Временные коллекции
            'oTempCollectionView',
            'oTempCollectionCreate',
            'oTempCollectionEdit',
            'oTempCollectionDelete',
            'oObjectFromTempCollectionDelete_Collection',
            'oObjectFromTempCollectionDelete_Object',
            'oObjectToTempCollectionAdd_Collection',
            'oObjectToTempCollectionAdd_Object'
        ),
    ),
    'user' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Пользователь',
        'bizRule' => null,
        'data' => null,
        'children' => array(
            'tCollectionIsAllowed',
            'oTempCollectionCreate',
            'oCollectionsView',
            'oObjectsView',
            'oImagesView',
        ),
    ),
);