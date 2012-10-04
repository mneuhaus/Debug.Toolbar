<?php
namespace Debug\Toolbar\Service;

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 */
class DataStorage {

    /**
    * TODO: Document this Property!
    */
    protected static $container = array(

    );

    /**
    * TODO: Document this Property!
    */
    protected static $frozen = false;

    /**
    * TODO: Document this Method! ( add )
    */
    static function add($name, $key = null, $value = null) {
        if (self::$frozen) {
            return;
        }
        if (isset(self::$container[$name]) && !is_array(self::$container[$name])) {
            self::$container[$name] = array(

            );
        }
        if ($value === null) {
            self::$container[$name][] = $key;
        } else {
            self::$container[$name][$key] = $value;
        }
    }

    /**
    * TODO: Document this Method! ( freeze )
    */
    static function freeze() {
        self::$frozen = true;
    }

    /**
    * TODO: Document this Method! ( get )
    */
    static function get($name, $key = null) {
        if ((!is_null($key) && isset(self::$container[$name])) && is_array(self::$container[$name])) {
            return isset(self::$container[$name][$key]) ? self::$container[$name][$key] : null;
        } else {
            return isset(self::$container[$name]) ? self::$container[$name] : null;
        }
    }

    /**
    * TODO: Document this Method! ( has )
    */
    static function has($name) {
        return array_key_exists($name, self::$container);
    }

    /**
    * TODO: Document this Method! ( init )
    */
    static function init() {
        if (self::$frozen) {
            return;
        }
        self::set('Environment:Token', uniqid());
    }

    /**
    * TODO: Document this Method! ( load )
    */
    static function load($token) {
        $filename = ((FLOW3_PATH_DATA . '/Logs/Debug/') . $token) . '.debug';
        $data = file_get_contents($filename);
        self::$container = @unserialize($data);
        \Debug\Toolbar\Service\Collector::setModules(self::get('Modules'));
    }

    /**
    * TODO: Document this Method! ( remove )
    */
    static function remove($name) {
        if (self::$frozen) {
            return;
        }
        unset(self::$container[$name]);
    }

    /**
    * TODO: Document this Method! ( save )
    */
    static function save() {
        if (self::$frozen) {
            return;
        }
        $token = self::$container['Environment:Token'];
        $filename = ((FLOW3_PATH_DATA . '/Logs/Debug/') . $token) . '.debug';
        file_put_contents($filename, serialize(self::$container));
    }

    /**
    * TODO: Document this Method! ( set )
    */
    static function set($name, $mixed) {
        if (self::$frozen) {
            return;
        }
        self::$container[$name] = $mixed;
    }

    /**
    * TODO: Document this Method! ( getData )
    */
    static function getData($token) {
        $filename = ((FLOW3_PATH_DATA . '/Logs/Debug/') . $token) . '.debug';
        if (file_exists($filename)) {
            $data = file_get_contents($filename);
            return unserialize($data);
        }
        return array();
    }

}

?>