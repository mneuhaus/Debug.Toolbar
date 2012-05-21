<?php
namespace Debug\Toolbar\Toolbar;

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
 * @FLOW3\Scope("singleton")
 */
class View {
	/**
	 * @var \Debug\Toolbar\Service\Debugger
	 * @author Marc Neuhaus <apocalip@gmail.com>
	 * @FLOW3\Inject
	 */
	protected $debugger;

	/**
	 * @var \TYPO3\Fluid\View\StandaloneView
	 */
	protected $view;

	public function __construct() {
		$this->view = new \TYPO3\Fluid\View\StandaloneView();
		$this->view->setTemplatePathAndFilename("resource://Debug.Toolbar/Private/Toolbar.html");
	}
    
    public function render() {
		$debuggers = $this->debugger->getDebuggers();
		foreach($debuggers as $debugger) {
			$debugger->collectBeforeToolbarRendering();
		}
		$this->view->assign("dataRenderers", $debuggers);

		return $this->view->render();
    }
}

?>
