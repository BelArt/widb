<?php

/**
 * Хелпер для работы с файлами
 */
class MyFileHelper extends CFileHelper
{

    /**
     * Создает директорию.
     * Безопасный вариант mkdir. Поддерживает рекурсивное создание родительских директорий, если их нет.
     * @param string $dst директория, которую надо создать
     * @param integer $newDirMode права новой директории
     * @param bool $recursive создавать ли рекурсивно родительские директории, если их нет
     * @return bool создана ли директория
     */
    public static function createDirectory($dst, $newDirMode = 0777, $recursive = true)
    {
        $prevDir=dirname($dst);
        if($recursive && !is_dir($dst) && !is_dir($prevDir)) {
            self::createDirectory(dirname($dst), $newDirMode, true);
        }
        $res=mkdir($dst, $newDirMode);
        @chmod($dst,$newDirMode);
        return $res;
    }
} 