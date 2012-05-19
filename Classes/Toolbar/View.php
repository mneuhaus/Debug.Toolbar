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
	 * @var \TYPO3\FLOW3\Reflection\ReflectionService
	 * @author Marc Neuhaus <apocalip@gmail.com>
	 * @FLOW3\Inject
	 */
	protected $reflectionService;

	/**
	 * @var \TYPO3\Fluid\View\StandaloneView
	 */
	protected $view;

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
	}
    
    public function render() {
		$this->view->setTemplatePathAndFilename("resource://Debug.Toolbar/Private/Toolbar.html");
		$dataRenderers = array();
		foreach($this->reflectionService->getAllImplementationClassNamesForInterface('Debug\Toolbar\DataRenderer\DataRendererInterface') as $dataRendererClass) {
			$dataRenderer = new $dataRendererClass();
			$dataRenderers[] = $dataRenderer->render();
		}
		$this->view->assign("dataRenderers", $dataRenderers);
		return $this->view->render();
    }
}

?>
