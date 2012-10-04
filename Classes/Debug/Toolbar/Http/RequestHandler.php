<?php
namespace Debug\Toolbar\Http;

/*                                                                        *
 * This script belongs to the Flow framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Core\Bootstrap;
use TYPO3\Flow\Core\RequestHandlerInterface;
use TYPO3\Flow\Configuration\ConfigurationManager;
use TYPO3\Flow\Security\Exception\AccessDeniedException;

/**
 * A request handler which can handle HTTP requests.
 *
 * @Flow\Scope("singleton")
 * @Flow\Proxy("disable")
 */
class RequestHandler extends \TYPO3\Flow\Http\RequestHandler {

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Session\SessionInterface
     */
    protected $session;

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
        $this->request = \TYPO3\Flow\Http\Request::createFromEnvironment();
        $this->response = new \TYPO3\Flow\Http\Response();
        $this->boot();
        $this->resolveDependencies();
        $this->request->injectSettings($this->settings);
        $this->router->setRoutesConfiguration($this->routesConfiguration);
        $actionRequest = $this->router->route($this->request);
        $this->securityContext->setRequest($actionRequest);
        $this->dispatcher->dispatch($actionRequest, $this->response);
        $this->response->makeStandardsCompliant($this->request);
        \Debug\Toolbar\Service\DataStorage::add('Request:Requests', $actionRequest);
        \Debug\Toolbar\Service\DataStorage::add('Request:Responses', $this->response);
        \Debug\Toolbar\Toolbar\View::handleRedirects($this->request, $this->response);
        $this->emitAboutToRenderDebugToolbar();
        \Debug\Toolbar\Service\DataStorage::set('Modules', \Debug\Toolbar\Service\Collector::getModules());
        echo \Debug\Toolbar\Toolbar\View::attachToolbar($this->response->getContent());
        $this->bootstrap->shutdown('Runtime');
        $this->exit->__invoke();
        \Debug\Toolbar\Service\DataStorage::save();
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