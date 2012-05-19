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
class RuntimeDataRenderer extends AbstractDataRenderer {
    public function render() {
		$this->view->setTemplatePathAndFilename("resource://Debug.Toolbar/Private/Data/Runtime.html");
		$runtime = microtime() - \Debug\Toolbar\Service\DataStorage::get("Runtime:Start");
		$this->view->assign("runtime", $runtime);
		return $this->view->render();
    }
}

?>
