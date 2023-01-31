<?php

namespace WP_Smart_Image_Resize\Utilities;

class File
{

    public static function mb_pathinfo($path, $options = null)
    {
        $locale = setlocale(LC_ALL, 0);

        setlocale(LC_ALL, 'en_US.UTF-8');

        if (is_null($options)) {
            $info = pathinfo($path);
        } else {
            $info = pathinfo($path, $options);
        }

        setlocale(LC_ALL, $locale);

        return $info;
    }

    public static function rrmdir($directory)
    {
        foreach (new \DirectoryIterator($directory) as $f) {
            if ($f->isDot()) {
                continue;
            }

            if ($f->isFile()) {
                unlink($f->getPathname());
            } else {
                if ($f->isDir()) {
                    static::rrmdir($f->getPathname());
                }
            }
        }
        rmdir($directory);
    }
}
