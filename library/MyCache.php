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

            $backendOptions = [
                'cache_dir' => ROOT_PATH . DIRECTORY_SEPARATOR . 'tmp/sys_cache/'
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