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
class AOPDebugger extends AbstractDebugger {
	/**
	 * @var \TYPO3\FLOW3\Reflection\ReflectionService
	 * @author Marc Neuhaus <apocalip@gmail.com>
	 * @FLOW3\Inject
	 */
	protected $reflectionService;

    public function assignVariables() {
        $rawAdvices = (array) \Debug\Toolbar\Service\DataStorage::get("AOP:Advices");
        $advices = array();
        foreach ($rawAdvices as $key => $value) {
            if(stristr($value["adviceClass"], "Debug\Toolbar") && true){
            }else{
                $key = implode(".", $value);
                if(isset($advices[$key])){
                    $advices[$key]["counter"]++;
                }else{
                    $advices[$key] = $value;
                    $advices[$key]["counter"] = 1;
                    $reflectionClass = new \ReflectionClass($value["adviceClass"]);
                    $advices[$key]["classComment"] = $this->cleanupComment($reflectionClass->getDocComment());
                    $advices[$key]["methodComment"] = $this->cleanupComment($reflectionClass->getMethod($value["adviceMethodName"])->getDocComment());
                }
            }

        }
        $this->view->assign("advices", $advices);
    }

    public function cleanupComment($comment) {
    	$comment = preg_replace("/^[^A-Za-z0-9@]*/m", "", $comment);
    	$comment = preg_replace("/@.+\n/", "", $comment);
    	$comment = trim($comment);
    	return $comment;
    }

    public function collectAdvices($adviceObject, $methodName, $joinPoint) {
        $this->add("Advices", array(
            "adviceClass" => get_class($adviceObject),
            "adviceMethodName" => $methodName,
            "joinPointClass" => $joinPoint->getClassName(),
            "joinPointMethodName" => $joinPoint->getMethodName()
        ));
    }
}

?>