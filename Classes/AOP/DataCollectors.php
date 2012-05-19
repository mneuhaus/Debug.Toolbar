<?php
namespace Debug\Toolbar\AOP;

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
 * @FLOW3\Aspect
 */
class DataCollectors {
    /**
     * Intercept the Response to attach the Toolbar
     *
     * @param \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint
     * @FLOW3\Before("method(TYPO3\FLOW3\Http\RequestHandler->handleRequest())")
     * @return void
     */
    public function setStartTime(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
        \Debug\Toolbar\Service\DataStorage::set("Runtime:Start", microtime());
    }
    
    /**
     * Intercept the Response to attach the Toolbar
     *
     * @param \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint
     * @FLOW3\Before("method(TYPO3\FLOW3\Mvc\Dispatcher->dispatch(*))")
     * @return void
     */
    public function catchActionRequest(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
        $request = $joinPoint->getMethodArgument("request");
        if(is_object($request))
            \Debug\Toolbar\Service\DataStorage::set("Request:ActionRequest", $request);
        
        $response = $joinPoint->getMethodArgument("response");
            if(is_object($response))
                \Debug\Toolbar\Service\DataStorage::set("Request:ActionResponse", $response);
    }
}

?>
