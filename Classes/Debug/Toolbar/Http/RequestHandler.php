<?php
namespace Debug\Toolbar\Http;

/*                                                                        *
 * This script belongs to the FLOW3 package "Debug.Toolbar".              *
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
use TYPO3\Flow\Http\Request;
use TYPO3\Flow\Http\Response;
use TYPO3\Flow\Mvc\ActionRequest;

/**
 * A request handler which can handle HTTP requests.
 *
 * @Flow\Scope("singleton")
 * @Flow\Proxy("disable")
 */
class RequestHandler extends \TYPO3\Flow\Http\RequestHandler {

	/**
	 * Returns the priority - how eager the handler is to actually handle the
	 * request.
	 *
	 * @return integer The priority of the request handler.
	 * @api
	 */
	public function getPriority() {
		return 1000;
	}

	/**
	 * Handles a HTTP request
	 *
	 * @return void
	 */
	public function handleRequest() {
			// Create the request very early so the Resource Management has a chance to grab it:
		$this->request = Request::createFromEnvironment();
		$this->response = new Response();

		$this->boot();
		$this->resolveDependencies();
		$this->request->injectSettings($this->settings);

		$this->router->setRoutesConfiguration($this->routesConfiguration);
		$actionRequest = $this->router->route($this->request);
		$this->securityContext->setRequest($actionRequest);

		$this->dispatcher->dispatch($actionRequest, $this->response);

		$this->response->makeStandardsCompliant($this->request);
		$this->attachToolbar($actionRequest);
		$this->response->send();

		$this->bootstrap->shutdown('Runtime');
		$this->exit->__invoke();

		DataStorage::save();
	}

	/**
	 * @param ActionRequest $actionRequest
	 */
	protected function attachToolbar(ActionRequest $actionRequest) {
		if ($actionRequest->getControllerObjectName() === 'Debug\Toolbar\Controller\ProfileController') {
			return;
		}
		
		DataStorage::add('Request:Requests', $actionRequest);
		DataStorage::add('Request:Responses', $this->response);
		View::handleRedirects($this->request, $this->response);
		$this->emitAboutToRenderDebugToolbar();
		DataStorage::set('Modules', Collector::getModules());
		if ($actionRequest->getFormat() === 'html') {
			$content = View::attachToolbar($this->response->getContent());
			$this->response->setContent($content);
			$this->response->getHeaders()->set('Content-Length', strlen($content));
		}
	}

	/**
	 * Emits a signal before the toolbar gets rendered
	 *
	 * @return void
	 * @Flow\Signal
	 */
	protected function emitAboutToRenderDebugToolbar() {
		$this->bootstrap->getSignalSlotDispatcher()->dispatch(__CLASS__, 'aboutToRenderDebugToolbar', array());
	}
}

?>
