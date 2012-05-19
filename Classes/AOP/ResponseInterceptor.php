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
class ResponseInterceptor {
	/**
	 * @var \Debug\Toolbar\Toolbar\View
	 * @FLOW3\Inject
	 */
	protected $toolbar;	

    /**
     * Intercept the Response to attach the Toolbar
     *
     * @param \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint
     * @FLOW3\Before("method(TYPO3\FLOW3\Http\Response->getContent())")
     * @return void
     */
    public function interceptSend(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
        if(is_null($joinPoint->getProxy()->getParentResponse())){
        	$content = $joinPoint->getProxy()->getContent();
        	$toolbar = $this->toolbar->render();
        	$content = str_replace("</body>", "\t".$toolbar."\n\t</body>", $content);
        	$joinPoint->getProxy()->setContent($content);
        }
    }
    
}

?>
