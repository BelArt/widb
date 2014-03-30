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

    /**
     * Рекурсивное удаление пустых поддиректорий директории
     * @param string $directory - путь к директории, у которой надо удалить пустые поддиректории
     */
    public static function removeEmptySubdirectories($directory)
    {
        self::removeEmptySubdirectoriesAndDirectoryItself($directory);
        if (!is_dir($directory)) {
            self::createDirectory($directory);
        }

    }

    /**
     * Рекурсивное удаление пустых поддиректорий директории, включая ее саму
     * @param string $directory - путь к директории, у которой надо удалить пустые поддиректории
     */
    private static function removeEmptySubdirectoriesAndDirectoryItself($directory)
    {
        $items=glob($directory.DIRECTORY_SEPARATOR.'{,.}*',GLOB_MARK | GLOB_BRACE);
        foreach($items as $item)
        {
            if(basename($item)=='.' || basename($item)=='..')
                continue;
            if(substr($item,-1)==DIRECTORY_SEPARATOR) {
                self::removeEmptySubdirectoriesAndDirectoryItself($item);
            }

        }
        if(is_dir($directory) && count(scandir($directory)) <= 2) {
            rmdir($directory);
        }
    }
} 