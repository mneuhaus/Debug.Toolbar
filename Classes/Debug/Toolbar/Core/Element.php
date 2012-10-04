<?php
namespace Debug\Toolbar\Core;

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
 *
 */
class Element {
    protected $type;

    protected $data;

    public function __construct($type, $data) {
        $this->type = $type;
        $this->data = $data;
    }

    public function getType() {
    	return $this->type;
    }

    public function getData() {
    	return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }
}

?>