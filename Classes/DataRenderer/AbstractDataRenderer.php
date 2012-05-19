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
abstract class AbstractDataRenderer implements DataRendererInterface {
	public function __construct() {
		$this->view = new \TYPO3\Fluid\View\StandaloneView();

		// if (isset($this->options['partialRootPath'])) {
		// 	$standaloneView->setPartialRootPath($this->options['partialRootPath']);
		// }

		// if (isset($this->options['layoutRootPath'])) {
		// 	$standaloneView->setLayoutRootPath($this->options['layoutRootPath']);
		// }

		// if (isset($this->options['variables'])) {
		// 	$standaloneView->assignMultiple($this->options['variables']);
		// }
		
		$class = get_class($this);
		preg_match("/(.+)\\\\DataRenderer\\\\(.+)DataRenderer/", $class, $match);
		$package = str_replace("\\", ".", $match[1]);
		$renderer = $match[2];
		$template = "resource://".$package."/Private/Data/".$renderer.".html";
		$this->view->setTemplatePathAndFilename($template);
	}
    
    public function render() {
		return $this->view->render();
    }

    public function __toString() {
    	return $this->render();
    }
}

?>
