<?php
/**
 * Created by PhpStorm.
 * User: padavan
 * Date: 21.05.17
 * Time: 16:09
 */

class MyCache
{
    static $_instance = null;

    /**
     * Return instance of Zend_Cache_Core
     *
     * @return Zend_Cache_Core
     */
    static function getInstance()
    {
        if (self::$_instance === null) {
            $frontend = 'Core';
            $frontendOptions = [
                'lifetime' => 3600,
                'automatic_serialization' => true,
            ];
            $backend = 'File';

            $cacheDir = ROOT_PATH . DIRECTORY_SEPARATOR . 'tmp/sys_cache/';

            if (!is_dir($cacheDir))
            {
                $oldMask = @umask(0);
                mkdir($cacheDir, 0750, true);
                chmod($cacheDir, 0750);
                umask($oldMask);
            }

            $backendOptions = [
                'cache_dir' => $cacheDir
            ];

            self::$_instance = Zend_Cache::factory(
                $frontend,
                $backend,
                $frontendOptions,
                $backendOptions
            );
        }
        return self::$_instance;
    }
}