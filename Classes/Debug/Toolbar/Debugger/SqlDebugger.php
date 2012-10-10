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
class SqlDebugger {

	/**
	 * TODO: Document this Method!
	 */
	public function preToolbarRendering() {
		$times = \Debug\Toolbar\Service\DataStorage::get('SqlLogger:Times');
		if (!is_array($times)) {
			$times = array();
		}

		$queries = \Debug\Toolbar\Service\DataStorage::get('SqlLogger:Queries');
		$origins = \Debug\Toolbar\Service\DataStorage::get('SqlLogger:Origins');
		$params = \Debug\Toolbar\Service\DataStorage::get('SqlLogger:Params');
		$types = \Debug\Toolbar\Service\DataStorage::get('SqlLogger:Types');
		$merged = array();
		if (is_array($queries)) {
			foreach ($queries as $key => $value) {
				$origin = $origins[$key];
				if (isset($origin['line'])) {
					$origin = (((('Called from: ' . $origin['class']) . $origin['type']) . $origin['function']) . ' on line ') . $origin['line'];
				} else {
					$origin = '';
				}
				$merged[$key] = array(
					'query' => $value,
					'time' => number_format($times[$key] * 1000, 2),
					'origin' => $origin,
					'params' => $params[$key],
					'types' => $types[$key]
				);
			}
		}
		$merged = $this->formatQueries($merged);

		\Debug\Toolbar\Service\Collector::getModule('Sql')
			->getToolbar()
			->addIcon('hdd')
			->addBadge(count($queries))
			->getPopup()
			->addPartial('Sql/Statistic', array(
			'time' => array_sum($times),
			'queries' => $merged,
			'queriesCount' => count($queries)
		))
			->getPanel()
			->addPartial('Sql/Queries', array(
			'time' => array_sum($times),
			'queries' => $merged,
			'queriesCount' => count($queries)
		));
	}

	/**
	 * TODO: Document this Method!
	 */
	public function formatQueries($merged) {
		if (empty($merged)) {
			return $merged;
		}
		$keywords = array(
			'SELECT' => '<b>SELECT</b>',
			'FROM' => chr(10) . '<b>FROM</b>',
			'WHERE' => chr(10) . '<b>WHERE</b>',
			'LEFT JOIN' => chr(10) . '<b>LEFT JOIN</b>',
			'RIGHT JOIN' => chr(10) . '<b>RIGHT JOIN</b>',
			'LIMIT' => chr(10) . '<b>LIMIT</b>',
			'AS' => '<b>AS</b>',
			'DISTINCT' => '<b>DISTINCT</b>'
		);
		foreach ($merged as $key => $value) {
			$parts = explode('?', $value['query']);
			$newQuery = '';
			foreach ($parts as $position => $part) {
				$newQuery .= $part;
				if (isset($value['types'][$position])) {
					switch ($value['types'][$position]) {
						case 'string':
							$newQuery .= ('"<b>' . $value['params'][$position]) . '</b>"';
							break;
						default:
							break;
					}
				}
			}
			$newQuery = str_replace(array_keys($keywords), array_values($keywords), $newQuery);
			$merged[$key]['query'] = $newQuery;
		}
		return $merged;
	}

}

?>