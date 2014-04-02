<?php
namespace Debug\Toolbar;

use TYPO3\Flow\Package\Package as BasePackage;
use TYPO3\Flow\Annotations as Flow;

/**
 * Package base class of the Debug.Toolbar package.
 *
 * @Flow\Scope("singleton")
 */
class Package extends BasePackage {
	public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		if (!$bootstrap->getContext()->isDevelopment()) {
			return;
		}

		$bootstrap->registerRequestHandler(new \Debug\Toolbar\Http\RequestHandler($bootstrap));

		if (!file_exists(FLOW_PATH_DATA . 'Logs/Debug')) {
			\TYPO3\Flow\Utility\Files::createDirectoryRecursively(FLOW_PATH_DATA . 'Logs/Debug');
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
			'Debug\Toolbar\Debugger\ViewsDebugger', 'preToolbarRendering'
		);

		$dispatcher->connect(
			'Debug\Toolbar\Http\RequestHandler', 'aboutToRenderDebugToolbar',
			'Debug\Toolbar\Debugger\AopDebugger', 'preToolbarRendering'
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

		// $dispatcher->connect(
		//         'Debug\Toolbar\Http\RequestHandler', 'aboutToRenderDebugToolbar',
		//         'Debug\Toolbar\Debugger\DumpDebugger', 'preToolbarRendering'
		// );

		$dispatcher->connect(
			'Debug\Toolbar\Http\RequestHandler', 'aboutToRenderDebugToolbar',
			'Debug\Toolbar\Debugger\LoggingDebugger', 'preToolbarRendering'
		);


		$dispatcher->connect(
			'TYPO3\Flow\Http\Response', 'postProcessResponseContent',
			'Debug\Toolbar\Toolbar\View', 'receivePostProcessResponseContent'
		);

		$dispatcher->connect(
			'TYPO3\Flow\Mvc\ActionRequest', 'requestDispatched',
			'Debug\Toolbar\Debugger\RequestDebugger', 'collectRequests'
		);

		$dispatcher->connect(
			'TYPO3\Flow\Aop\Advice\AbstractAdvice', 'adviceInvoked',
			'Debug\Toolbar\Debugger\AopDebugger', 'collectAdvices'
		);
	}
}

?>