<?php

/**
 * 文件系统处理助手
 */
class FileSystemHelper {

    /**
     * 遍历指定目录及子目录下的文件，返回所有与匹配模式符合的文件名
     *
     * @param string $dir
     * @param string $pattern
     *
     * @return array
     */
    public static function recursionGlob($dir, $pattern) {
        $dir = rtrim($dir, '/\\') . DS;
        $files = array();

        // 遍历目录，删除所有文件和子目录
        $dh = opendir($dir);
        if (!$dh)
            return $files;

        $items = (array) glob($dir . $pattern);
        foreach ($items as $item) {
            if (is_file($item))
                $files[] = $item;
        }

        while (($file = readdir($dh))) {
            if ($file == '.' || $file == '..')
                continue;

            $path = $dir . $file;
            if (is_dir($path)) {
                $files = array_merge($files, self::recursionGlob($path, $pattern));
            }
        }
        closedir($dh);

        return $files;
    }

    /**
     * 创建一个目录树，失败抛出异常
     *
     * 用法：
     * @code php
     * Helper_Filesys::mkdirs('/top/second/3rd');
     * @endcode
     *
     * @param string $dir 要创建的目录
     * @param int $mode 新建目录的权限
     *
     * @throw Q_CreateDirFailedException
     */
    public static function makeDirs($dir, $mode = 0777) {
        if (!is_dir($dir)) {
            @mkdir($dir, $mode, true);
        }
        return true;
    }

    public static function removeDirs($dir, $self = true) {
        $dir = realpath($dir);
        if ($dir == '' || $dir == '/' || (strlen($dir) == 3 && substr($dir, 1) == ':\\')) {
            // 禁止删除根目录
            return true;
        } else {
            // 遍历目录，删除所有文件和子目录
            if (false !== ($dh = opendir($dir))) {
                while (false !== ($file = readdir($dh))) {
                    if ($file == '.' || $file == '..') {
                        continue;
                    }

                    $path = $dir . DIRECTORY_SEPARATOR . $file;
                    if (is_dir($path)) {
                        self::removeDirs($path);
                    } else {
                        unlink($path);
                    }
                }
                closedir($dh);
                if ($self) {
                    @rmdir($dir);
                }
            }
        }
    }

    /**
     * 将指定的内容写入文件中
     * @param string $filename
     * @param string $writeText
     * @param string $openMod
     * @return boolean
     */
    public static function writeFile($filename, $writeText, $openMod = 'w') {
        if (is_dir(dirname($filename))) {
            if ($fp = fopen($filename, $openMod)) {
                flock($fp, 2);
                fwrite($fp, $writeText);
                fclose($fp);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function exists($filePath) {
        return (file_exists($filePath) && is_file($filePath));
    }

    public static function lastAccess($filePath) {
        if (self::exists($filePath)) {
            return fileatime($filePath);
        }
        return false;
    }

    public static function lastChange($filePath) {
        if (self::exists($filePath)) {
            return filemtime($filePath);
        }
        return false;
    }

    /**
     * 获取目录占用空间
     * @param string $directory // 要获取的目录名称
     * @param boolean $fromCache // 是否从 cache 中读取（提升性能）
     * @return integer
     */
    public static function directoryUsage($directory, $fromCache = true) {
        $cache = Yii::app()->cache->get($directory);
        if ($cache !== false && $fromCache) {
            return $cache;
        } else {
            $directorySize = self::_directoryUsageStatistics($directory);
            Yii::app()->cache->set($directory, $directorySize, Option::TIMESTAMP_ONE_DAY);
            return $directorySize;
        }
    }

    /**
     * 目录占用空间统计
     * @param string $directory
     * @return integer
     */
    private static function _directoryUsageStatistics($directory) {
        $directorySize = 0;
        if ($dh = @opendir($directory)) {
            while (($fileName = readdir($dh))) {
                if ($fileName != "." && $fileName != "..") {
                    if (is_file($directory . "/" . $fileName))
                        $directorySize += filesize($directory . "/" . $fileName);
                    if (is_dir($directory . "/" . $fileName))
                        $directorySize += self::_directoryUsageStatistics($directory . "/" . $fileName);
                }
            }
        }
        @closedir($dh);
        return $directorySize;
    }

}