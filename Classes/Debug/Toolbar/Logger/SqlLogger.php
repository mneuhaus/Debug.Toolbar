<?php
namespace Debug\Toolbar\Logger;

/*                                                                        *
 * This script belongs to the FLOW3 package "Debug.Toolbar".              *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */
use Debug\Toolbar\Service\DataStorage;

/**
 * A SQL logger that logs to a Flow logger.
 *
 */
class SqlLogger extends \TYPO3\Flow\Persistence\Doctrine\Logging\SqlLogger {

	/**
	 * @var float
	 */
	protected $start;

	/**
	 * Logs a SQL statement to the system logger (DEBUG priority).
	 *
	 * @param string $sql The SQL to be executed
	 * @param array $params The SQL parameters
	 * @param array $types The SQL parameter types.
	 * @return void
	 */
	public function startQuery($sql, array $params = NULL, array $types = NULL) {
		parent::startQuery($sql, $params, $types);
		$this->start = microtime(TRUE);
		DataStorage::add('SqlLogger:Queries', $sql);
		DataStorage::add('SqlLogger:Params', $params);
		DataStorage::add('SqlLogger:Types', $types);
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		foreach ($backtrace as $key => $value) {
			if ($key == 0) {
				continue;
			}
			if (!isset($value['class'])) {
				continue;
			}
			if (stristr($value['class'], 'Doctrine\\')) {
				continue;
			}
			if (stristr($value['class'], 'TYPO3\\Flow')) {
				continue;
			}
			DataStorage::add('SqlLogger:Origins', $value);
		}
	}

	/**
	 * @return void
	 */
	public function stopQuery() {
		$time = microtime(TRUE) - $this->start;
		DataStorage::add('SqlLogger:Times', $time);
	}

}

?>