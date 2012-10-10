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
 *
 * @Flow\Scope("singleton")
 */
class Collector {
	protected static $modules = array();

	/**
	 * @var \TYPO3\Flow\Mvc\Dispatcher
	 */
	protected static $dispatcher;

	static public function getModule($name) {
		if (!isset(self::$modules[$name])) {
			self::$modules[$name] = new \Debug\Toolbar\Core\Module($name);
		}
		return self::$modules[$name];
	}

	static public function setModules($modules) {
		self::$modules = $modules;
	}

	static public function getModules() {
		usort(self::$modules, function ($a, $b) {
			return $a->getPriority() > $b->getPriority() ? -1 : 1;
		});
		return self::$modules;
	}

	static public function setDispatcher($dispatcher) {
		self::$dispatcher = $dispatcher;
	}

	static public function getDispatcher() {
		return self::$dispatcher;
	}

}

?>