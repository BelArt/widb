<?php
/**
 * Кастомные исключения проекта
 */

// Исключение контекста ActiveRecord
class ActiveRecordException extends CustomException {}

// Исключение контекста Collections
class CollectionsException extends ActiveRecordException {}

// Исключение контекста DeleteHelper
class DeleteHelperException extends CustomException {}

// Исключение контекста PreviewHelper
class PreviewHelperException extends CustomException {}