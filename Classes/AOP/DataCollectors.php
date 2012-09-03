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
     * @FLOW3\Before("method(TYPO3\FLOW3\Http\Response->__construct())")
     * @return void
     */
    public function catchResponses(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
        $response = $joinPoint->getProxy();
        \Debug\Toolbar\Service\DataStorage::add('Responses', $response);
    }

    /**
     *
     * @param \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint
     * @FLOW3\After("method(TYPO3\FLOW3\Security\Policy\PolicyService->getPrivilegesForJoinPoint(*))")
     * @return void
     */
    public function collectRoleVotes(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
        $role = $joinPoint->getMethodArgument('role');
        $privileges = $joinPoint->getResult();
        \Debug\Toolbar\Service\DataStorage::add('Security:RoleVotes', array(
            'role' => $role,
            'privileges' => $privileges
        ));
    }

    /**
     *
     * @FLOW3\After("method(TYPO3\FLOW3\Mvc\Routing\Route->matches(*))")
     * @param \TYPO3\FLOW3\Aop\JoinPointInterface $joinPoint The current join point
     * @return array Result of the target method
     */
    public function logRoutes(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
        $route = $joinPoint->getProxy();
        \Debug\Toolbar\Service\DataStorage::add('Route:Routes', $route->getUriPattern());
    }

    /**
     *
     * @FLOW3\Before("filter(Debug\Toolbar\AOP\PointcutSettingsClassFilter)")
     * @param \TYPO3\FLOW3\Aop\JoinPointInterface $joinPoint The current join point
     * @return array Result of the target method
     */
    public function profilerStart(\TYPO3\FLOW3\Aop\JoinPointInterface $joinPoint) {
        $run = \SandstormMedia\PhpProfiler\Profiler::getInstance()->getRun();
        $tag = ($joinPoint->getClassName() . '::') . $joinPoint->getMethodName();
        $run->startTimer($tag);
    }

    /**
     *
     * @FLOW3\After("filter(Debug\Toolbar\AOP\PointcutSettingsClassFilter)")
     * @param \TYPO3\FLOW3\Aop\JoinPointInterface $joinPoint The current join point
     * @return array Result of the target method
     */
    public function profilerStop(\TYPO3\FLOW3\Aop\JoinPointInterface $joinPoint) {
        $run = \SandstormMedia\PhpProfiler\Profiler::getInstance()->getRun();
        $tag = ($joinPoint->getClassName() . '::') . $joinPoint->getMethodName();
        $run->stopTimer($tag);
    }

    /**
     * Intercept the Response to attach the Toolbar
     *
     * @param \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint
     * @FLOW3\Before("method(TYPO3\FLOW3\Http\RequestHandler->handleRequest())")
     * @return void
     */
    public function setStartTime(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
        \Debug\Toolbar\Service\DataStorage::set('Runtime:Start', microtime());
    }

}

?>