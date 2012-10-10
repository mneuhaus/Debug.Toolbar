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
 * @Flow\Scope("singleton")
 */
class LoggingDebugger {

	public function preToolbarRendering() {
		$logDir = FLOW_PATH_DATA . 'Logs/';
		$logFiles = scandir($logDir);
		$logs = array();
		foreach ($logFiles as $logFile) {
			if (pathinfo($logFile, PATHINFO_EXTENSION) !== 'log') {
				continue;
			}
			$lines = file($logDir . '/' . $logFile);
			$lines = array_slice($lines, -200);
			foreach ($lines as $key => $line) {
				$loggingLevels = array(
					' DEBUG ' => ' <span class="muted">DEBUG</span> ',
					' INFO ' => ' <span class="text-info">INFO</span> ',
					' NOTICE ' => ' <span class="text-warning">NOTICE</span> ',
					' WARNING ' => ' <span class="text-warning">WARNING</span> ',
					' ERROR ' => ' <span class="text-error">ERROR</span> ',
					' CRITICAL ' => ' <span class="text-error">CRITICAL</span> ',
					' ALERT ' => ' <span class="text-error">ALERT</span> ',
					' EMERGENCY ' => ' <span class="text-error">EMERGENCY</span> '
				);
				$lines[$key] = str_replace(array_keys($loggingLevels), array_values($loggingLevels), $line);
			}
			$logs[$logFile] = $lines;
			unset($lines);
		}
		\Debug\Toolbar\Service\Collector::getModule('Logging')
			->getToolbar()
			->addIcon('list-alt')
			->addText('Logging')
			->getPopup()
			->setClass('fullscreen')
			->addPartial('Logging', array('logs' => $logs));
	}

}

?>