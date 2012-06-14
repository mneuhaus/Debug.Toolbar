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
		
		if(!class_exists("SandstormMedia\Plumber\Package")){
			$profiler = \SandstormMedia\PhpProfiler\Profiler::getInstance();
			if($profiler->getRun() instanceof \SandstormMedia\PhpProfiler\Domain\Model\EmptyProfilingRun)
				$profiler->start();
		}

		#foreach ($bootstrap->getObjectManager()->get("Debug\Toolbar\Service\Debugger")->getDebuggers() as $debugger) {
		#	$debugger->collectBoot();
		#}

		$dispatcher = $bootstrap->getSignalSlotDispatcher();
		$dispatcher->connect('TYPO3\FLOW3\Core\Bootstrap', 'bootstrapShuttingDown', function($runLevel) use($bootstrap) {
			if($runLevel == "Runtime"){
				\Debug\Toolbar\Service\DataStorage::save();
				foreach ($bootstrap->getObjectManager()->get("Debug\Toolbar\Service\Debugger")->getDebuggers() as $debugger) {
					$debugger->collectShutdown();
				}
			}
		});

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