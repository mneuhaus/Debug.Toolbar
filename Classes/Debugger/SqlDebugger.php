<?php
namespace Debug\Toolbar\Debugger;

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 */
class SqlDebugger extends AbstractDebugger {
    public function assignVariables() {
		$times = \Debug\Toolbar\Service\DataStorage::get("SqlLogger:Times");
		if(!is_array($times))
			$times = array();
		$this->view->assign("time", array_sum($times));

		$queries = \Debug\Toolbar\Service\DataStorage::get("SqlLogger:Queries");
		$origins = \Debug\Toolbar\Service\DataStorage::get("SqlLogger:Origins");
		$params = \Debug\Toolbar\Service\DataStorage::get("SqlLogger:Params");
		$types = \Debug\Toolbar\Service\DataStorage::get("SqlLogger:Types");

		$merged = array();
        if(is_array($queries)){
    		foreach ($queries as $key => $value) {
    			$origin = $origins[$key];
                if(isset($origin["line"]))
                    $origin = "Called from: " . $origin["class"] . $origin["type"] . $origin["function"] . " on line " . $origin["line"];
                else
                    $origin = "";

    			$merged[$key] = array(
    				"query" => $value,
    				"time" => number_format(($times[$key] * 1000), 2),
    				"origin" => $origin,
    				"params" => $params[$key],
    				"types" => $types[$key],
    			);
    		}
        }
		$merged = $this->formatQueries($merged);
		$this->view->assign("queries", $merged);
		$this->view->assign("queriesCount", count($queries));		
    }

    public function formatQueries($merged) {
    	$keywords = array(
    		"SELECT" 		=> "<b>SELECT</b>",
    		"FROM" 			=> "\n<b>FROM</b>",
    		"WHERE" 		=> "\n<b>WHERE</b>",
    		"LEFT JOIN" 	=> "\n<b>LEFT JOIN</b>",
    		"RIGHT JOIN" 	=> "\n<b>RIGHT JOIN</b>",
    		"LIMIT" 		=> "\n<b>LIMIT</b>",
    		"AS" 			=> "<b>AS</b>",
    		"DISTINCT" 		=> "<b>DISTINCT</b>"
    	);
    	foreach ($merged as $key => $value) {
    		$parts = explode("?", $value["query"]);
    		$newQuery = "";
    		foreach ($parts as $position => $part) {
    			$newQuery.= $part;
    			if(isset($value["params"][$position])) {
                    switch ($value["types"][$position]) {
                        case 'string':
                            $newQuery.= '"<b>'.$value["params"][$position].'</b>"';
                            break;
                        
                        default:
                            break;
                    }
    			}
    		}
    		$newQuery = str_replace(array_keys($keywords), array_values($keywords), $newQuery);
    		$merged[$key]["query"] = $newQuery;
    	}
    	return $merged;
    }
}

?>
