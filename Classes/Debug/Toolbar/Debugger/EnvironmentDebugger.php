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
class EnvironmentDebugger {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 * @author Marc Neuhaus <apocalip@gmail.com>
	 * @Flow\Inject
	 */
	protected $objectManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Session\SessionInterface
	 */
	protected $session;

	/**
	 * TODO: Document this Method! ( assignVariables )
	 */
	public function preToolbarRendering() {

		$memoryUsage = (memory_get_peak_usage(TRUE) / 1024) / 1024;
		$memoryUsage = number_format($memoryUsage, 1) . ' MB';

		\Debug\Toolbar\Service\Collector::getModule('Environment')
			->getToolbar()
				// ->addHtml('<img height="20" alt="Memory Usage" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAAcBAMAAABITyhxAAAAJ1BMVEXNzc3///////////////////////8/Pz////////////+NjY0/Pz9lMO+OAAAADHRSTlMAABAgMDhAWXCvv9e8JUuyAAAAQ0lEQVQI12MQBAMBBmLpMwoMDAw6BxjOOABpHyCdAKRzsNDp5eXl1KBh5oHBAYY9YHoDQ+cqIFjZwGCaBgSpBrjcCwCZgkUHKKvX+wAAAABJRU5ErkJggg==">')
			->addIcon('info-sign')
			->addText($memoryUsage)
			->getPopup()
			->addTable(array(
			'PHP Version' => PHP_VERSION,
			'Flow Context' => $this->objectManager->getContext(),
			'Flow Version' => FLOW_VERSION_BRANCH
		))
			->getPanel()
			->addTable(array(
			'PHP Version' => PHP_VERSION,
			'Flow Context' => $this->objectManager->getContext(),
			'Flow Version' => FLOW_VERSION_BRANCH
		));
		return;
		#$configurations = array();
		#foreach ($configurations as $configurationName => $configurationConstant) {
		#    $configuration = $this->configurationManager->getConfiguration($configurationConstant);
		#    $configurations[$configurationName] = \Symfony\Component\Yaml\Yaml::dump($configuration, 10);
		#}
		#$configurations["Constants"] = \Symfony\Component\Yaml\Yaml::dump(get_defined_constants(), 10);
		#$this->view->assign('configurations', $configurations);
	}

}

?>