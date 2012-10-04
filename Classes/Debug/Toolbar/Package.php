<?php
namespace Debug\Toolbar;

use TYPO3\FLOW3\Package\Package as BasePackage;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Package base class of the Debug.Toolbar package.
 *
 * @FLOW3\Scope("singleton")
 */
class Package extends BasePackage {
	public function boot(\TYPO3\FLOW3\Core\Bootstrap $bootstrap) {
    	$bootstrap->registerRequestHandler(new \Debug\Toolbar\Http\RequestHandler($bootstrap));

    	if (!file_exists(FLOW3_PATH_DATA . 'Logs/Debug')) {
    		mkdir(FLOW3_PATH_DATA . 'Logs/Debug');
    	}

    	\Debug\Toolbar\Service\DataStorage::init();

    	$dispatcher = $bootstrap->getSignalSlotDispatcher();
        \Debug\Toolbar\Service\Collector::setDispatcher($dispatcher);

        $dispatcher->connect(
                'Debug\Toolbar\Http\RequestHandler', 'aboutToRenderDebugToolbar',
                'Debug\Toolbar\Debugger\RequestDebugger', 'preToolbarRendering'
        );

        $dispatcher->connect(
                'Debug\Toolbar\Http\RequestHandler', 'aboutToRenderDebugToolbar',
                'Debug\Toolbar\Debugger\AOPDebugger', 'preToolbarRendering'
        );

        $dispatcher->connect(
                'Debug\Toolbar\Http\RequestHandler', 'aboutToRenderDebugToolbar',
                'Debug\Toolbar\Debugger\EnvironmentDebugger', 'preToolbarRendering'
        );

        $dispatcher->connect(
                'Debug\Toolbar\Http\RequestHandler', 'aboutToRenderDebugToolbar',
                'Debug\Toolbar\Debugger\SecurityDebugger', 'preToolbarRendering'
        );

        $dispatcher->connect(
                'Debug\Toolbar\Http\RequestHandler', 'aboutToRenderDebugToolbar',
                'Debug\Toolbar\Debugger\SignalDebugger', 'preToolbarRendering'
        );

        $dispatcher->connect(
                'Debug\Toolbar\Http\RequestHandler', 'aboutToRenderDebugToolbar',
                'Debug\Toolbar\Debugger\SqlDebugger', 'preToolbarRendering'
        );


        $dispatcher->connect(
                'TYPO3\FLOW3\Http\Response', 'postProcessResponseContent',
                'Debug\Toolbar\Toolbar\View', 'receivePostProcessResponseContent'
        );

        $dispatcher->connect(
                'TYPO3\FLOW3\Mvc\ActionRequest', 'requestDispatched',
                'Debug\Toolbar\Debugger\RequestDebugger', 'collectRequests'
        );

        $dispatcher->connect(
                'TYPO3\FLOW3\Aop\Advice\AbstractAdvice', 'adviceInvoked',
                'Debug\Toolbar\Debugger\AOPDebugger', 'collectAdvices'
        );
	}
}
?>