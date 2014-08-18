<?php
namespace Debug\Toolbar\Http;

/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Debug\Toolbar\Service\Collector;
use Debug\Toolbar\Service\DataStorage;
use Debug\Toolbar\Toolbar\View;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Http\Component\ComponentContext;
use TYPO3\Flow\Http\Component\ComponentInterface;
use TYPO3\Flow\Object\ObjectManagerInterface;

/**
 * HTTP component that makes sure that the current response is standards-compliant. It is usually the last component in the chain.
 */
class ToolbarComponent implements ComponentInterface {

	/**
	 * @Flow\Inject
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @var array
	 */
	protected $options;

	/**
	 * @param array $options
	 */
	public function __construct(array $options = array()) {
		$this->options = $options;
	}

	/**
	 * Just call makeStandardsCompliant on the Response for now
	 *
	 * @param ComponentContext $componentContext
	 * @return void
	 */
	public function handle(ComponentContext $componentContext) {
		$response = $componentContext->getHttpResponse();
		$actionRequest = $this->objectManager->get('TYPO3\Flow\Mvc\ActionRequest', $componentContext->getHttpRequest());
		$matchResults = $componentContext->getParameter('TYPO3\Flow\Mvc\Routing\RoutingComponent', 'matchResults');
		if ($matchResults !== NULL) {
			$actionRequest->setArguments($matchResults);
		}

		$this->attachToolbar($actionRequest, $response);
		DataStorage::save();
	}

	/**
	 * @param ActionRequest $actionRequest
	 */
	protected function attachToolbar($actionRequest, $response) {
		if ($actionRequest->getControllerObjectName() === 'Debug\Toolbar\Controller\ProfileController') {
			return;
		}

		DataStorage::add('Request:Requests', $actionRequest);
		DataStorage::add('Request:Responses', $response);
		View::handleRedirects($actionRequest, $response);
		$this->emitAboutToRenderDebugToolbar();
		DataStorage::set('Modules', Collector::getModules());
		if ($actionRequest->getFormat() === 'html' && stristr($response->getHeader('Content-Type'), 'text/html') !== FALSE) {
			$content = View::attachToolbar($response->getContent());
			$response->setContent($content);
			$response->getHeaders()->set('Content-Length', strlen($content));
		}
	}

	/**
	 * Emits a signal before the toolbar gets rendered
	 *
	 * @return void
	 * @Flow\Signal
	 */
	protected function emitAboutToRenderDebugToolbar() {
		$this->objectManager->get('TYPO3\Flow\SignalSlot\Dispatcher')->dispatch(__CLASS__, 'aboutToRenderDebugToolbar', array());
	}

}