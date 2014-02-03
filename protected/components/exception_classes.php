<?php
/**
 * Кастомные исключения проекта
 */

// Исключение контекста ActiveRecord
class ActiveRecordException extends CustomException {}

// Исключение контекста Collections
class CollectionsException extends ActiveRecordException {}

// Исключение контекста Objects
class ObjectsException extends ActiveRecordException {}

// Исключение контекста Images
class ImagesException extends ActiveRecordException {}

// Исключение контекста DeleteHelper
class DeleteHelperException extends CustomException {}

// Исключение контекста PreviewHelper
class PreviewHelperException extends CustomException {}

// Исключение контекста Controller
class ControllerException extends CustomException {}

// Исключение контекста ObjectsController
class ObjectsControllerException extends ControllerException {}