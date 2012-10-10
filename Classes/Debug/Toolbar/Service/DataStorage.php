<?php
namespace Debug\Toolbar\Service;

/*                                                                        *
 * This script belongs to the FLOW3 package "Debug.Toolbar".              *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 */
class DataStorage {

	/**
	 * TODO: Document this Property!
	 */
	protected static $container = array();

	/**
	 * TODO: Document this Property!
	 */
	protected static $frozen = FALSE;

	/**
	 * TODO: Document this Method! ( add )
	 */
	static public function add($name, $key = NULL, $value = NULL) {
		if (self::$frozen) {
			return;
		}
		if (isset(self::$container[$name]) && !is_array(self::$container[$name])) {
			self::$container[$name] = array();
		}
		if ($value === NULL) {
			self::$container[$name][] = $key;
		} else {
			self::$container[$name][$key] = $value;
		}
	}

	/**
	 * TODO: Document this Method! ( freeze )
	 */
	static public function freeze() {
		self::$frozen = TRUE;
	}

	/**
	 * TODO: Document this Method! ( get )
	 */
	static public function get($name, $key = NULL) {
		if ((!is_null($key) && isset(self::$container[$name])) && is_array(self::$container[$name])) {
			return isset(self::$container[$name][$key]) ? self::$container[$name][$key] : NULL;
		} else {
			return isset(self::$container[$name]) ? self::$container[$name] : NULL;
		}
	}

	/**
	 * TODO: Document this Method! ( has )
	 */
	static public function has($name) {
		return array_key_exists($name, self::$container);
	}

	/**
	 * TODO: Document this Method! ( init )
	 */
	static public function init() {
		if (self::$frozen) {
			return;
		}
		self::set('Environment:Token', uniqid());
	}

	/**
	 * TODO: Document this Method! ( load )
	 */
	static public function load($token) {
		$filename = ((FLOW_PATH_DATA . '/Logs/Debug/') . $token) . '.debug';
		$data = file_get_contents($filename);
		self::$container = @unserialize($data);
		\Debug\Toolbar\Service\Collector::setModules(self::get('Modules'));
	}

	/**
	 * TODO: Document this Method! ( remove )
	 */
	static public function remove($name) {
		if (self::$frozen) {
			return;
		}
		unset(self::$container[$name]);
	}

	/**
	 * TODO: Document this Method! ( save )
	 */
	static public function save() {
		if (self::$frozen) {
			return;
		}
		$token = self::$container['Environment:Token'];
		$filename = ((FLOW_PATH_DATA . '/Logs/Debug/') . $token) . '.debug';
		$data = self::sanitizeData(self::$container);
		file_put_contents($filename, serialize($data));
	}

	static public function sanitizeData($data) {
		switch (TRUE) {
			case is_array($data):
				foreach ($data as $key => $value) {
					$data[$key] = self::sanitizeData($value);
				}
				break;
			case is_object($data) && get_class($data) === 'Closure':
				$data = "[Closure]";
				break;
		}
		return $data;
	}

	/**
	 * TODO: Document this Method! ( set )
	 */
	static public function set($name, $mixed) {
		if (self::$frozen) {
			return;
		}
		self::$container[$name] = $mixed;
	}

	/**
	 * TODO: Document this Method! ( getData )
	 */
	static public function getData($token) {
		$filename = ((FLOW_PATH_DATA . '/Logs/Debug/') . $token) . '.debug';
		if (file_exists($filename)) {
			$data = file_get_contents($filename);
			return unserialize($data);
		}
		return array();
	}

}

?>