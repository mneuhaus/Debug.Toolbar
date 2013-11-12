<?php
namespace Debug\Toolbar\Debugger;

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
class ViewsDebugger {

	/**
	 * TODO: Document this Method! ( assignVariables )
	 */
	public function preToolbarRendering() {
		$views = \Debug\Toolbar\Service\DataStorage::get('Views');

		\Debug\Toolbar\Service\Collector::getModule('Views')
			->setPriority(50)
			->getToolbar()
			->addText('Views')
			->getPopup()
			->addPartial('View', array('views' => $views));
	}
}

?>