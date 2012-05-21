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
class RequestDebugger extends AbstractDebugger {
    public function assignVariables() {
		$requests = \Debug\Toolbar\Service\DataStorage::get("Request:ActionRequests");
		$this->view->assign("requests", $requests);
		foreach ($requests as $request) {
			if($request->getParentRequest() instanceof \TYPO3\FLOW3\Http\Request)
				break;
		}
		$this->view->assign("mainRequest", $request);

		$response = current(\Debug\Toolbar\Service\DataStorage::get("Responses"));
		$this->view->assign("response", $response);		

		$this->view->assign("routes", \Debug\Toolbar\Service\DataStorage::get("Route:Routes"));
		
		$arrays = array(
			"Get" => \Debug\Toolbar\Service\DataStorage::get("Request:Get"),
			"Post" => \Debug\Toolbar\Service\DataStorage::get("Request:Post"),
			"Cookie" => \Debug\Toolbar\Service\DataStorage::get("Request:Cookie"),
			"Server" => \Debug\Toolbar\Service\DataStorage::get("Request:Server"),
		#	"Session" => \Debug\Toolbar\Service\DataStorage::get("Request:Session"),
		);
		$this->view->assign("arrays", $arrays);
    }

    public function collectBeforeToolbarRendering() {
    	\Debug\Toolbar\Service\DataStorage::set("Request:Get", $_GET);
    	\Debug\Toolbar\Service\DataStorage::set("Request:Post", $_POST);
    	\Debug\Toolbar\Service\DataStorage::set("Request:Cookie", $_COOKIE);
    	\Debug\Toolbar\Service\DataStorage::set("Request:Server", $_SERVER);
    	#\Debug\Toolbar\Service\DataStorage::set("Request:Session", $_SESSION);
    }
}

?>
