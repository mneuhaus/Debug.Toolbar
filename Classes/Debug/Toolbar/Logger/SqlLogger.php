<?php
namespace Debug\Toolbar\Logger;

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * A SQL logger that logs to a FLOW3 logger.
 *
 */
class SqlLogger implements \Doctrine\DBAL\Logging\SQLLogger {

    /**
     * Logs a SQL statement to the system logger (DEBUG priority).
     *
     * @param string $sql The SQL to be executed
     * @param array $params The SQL parameters
     * @param array $types The SQL parameter types.
     * @return void
     */
    public function startQuery($sql, array $params = NULL, array $types = NULL) {
        $this->start = microtime();
        \Debug\Toolbar\Service\DataStorage::add('SqlLogger:Queries', $sql);
        \Debug\Toolbar\Service\DataStorage::add('SqlLogger:Params', $params);
        \Debug\Toolbar\Service\DataStorage::add('SqlLogger:Types', $types);
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
            if (stristr($value['class'], 'TYPO3\\FLOW3')) {
                continue;
            }
            \Debug\Toolbar\Service\DataStorage::add('SqlLogger:Origins', $value);
        }
    }

    /**
     * @return void
     */
    public function stopQuery() {
        $time = microtime() - $this->start;
        \Debug\Toolbar\Service\DataStorage::add('SqlLogger:Times', $time);
    }

}

?>