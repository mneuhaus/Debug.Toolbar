<?php
namespace Debug\Toolbar\DataRenderer;

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
class MemoryUsageDataRenderer extends AbstractDataRenderer {
    public function render() {
    	#{{ '%.1f'|format(collector.memory / 1024 / 1024) }}
    	$memoryUsage = memory_get_peak_usage(true) / 1024 / 1024;
    	$memoryUsage = number_format( $memoryUsage, 1 ) . " MB";

		$this->view->assign("memoryUsage", $memoryUsage);
		return $this->view->render();
    }
}

?>
