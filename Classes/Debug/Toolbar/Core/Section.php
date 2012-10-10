<?php
namespace Debug\Toolbar\Core;

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
 */
class Section {
	protected $name;

	protected $class = '';

	protected $elements = array();

	protected $types = array();

	public function __construct($name) {
		$this->name = $name;
	}

	public function addElement($element) {
		$this->elements[] = $element;
	}

	public function getElements() {
		return $this->elements;
	}

	public function getElement($type, $defaults = array()) {
		if (!isset($this->types[$type])) {
			$this->types[$type] = new Element($type, $defaults);
		}
		return $this->types[$type];
	}

	/**
	 * @param string $class
	 */
	public function setClass($class) {
		$this->class = $class;
	}

	/**
	 * @return string
	 */
	public function getClass() {
		return $this->class;
	}
}

?>