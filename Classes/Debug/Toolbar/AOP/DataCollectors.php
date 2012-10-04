<?php
namespace Debug\Toolbar\AOP;

/*                                                                        *
 * This script belongs to the Flow framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Aspect
 */
class DataCollectors {

    /**
     * Intercept the Response to attach the Toolbar
     *
     * @param \TYPO3\Flow\AOP\JoinPointInterface $joinPoint
     * @Flow\Before("method(TYPO3\Flow\Http\Response->__construct())")
     * @return void
     */
    public function catchResponses(\TYPO3\Flow\AOP\JoinPointInterface $joinPoint) {
        $response = $joinPoint->getProxy();
        \Debug\Toolbar\Service\DataStorage::add('Responses', $response);
    }

    /**
     *
     * @param \TYPO3\Flow\AOP\JoinPointInterface $joinPoint
     * @Flow\After("method(TYPO3\Flow\Security\Policy\PolicyService->getPrivilegesForJoinPoint(*))")
     * @return void
     */
    public function collectRoleVotes(\TYPO3\Flow\AOP\JoinPointInterface $joinPoint) {
        $role = $joinPoint->getMethodArgument('role');
        $privileges = $joinPoint->getResult();
        \Debug\Toolbar\Service\DataStorage::add('Security:RoleVotes', array(
            'role' => $role,
            'privileges' => $privileges
        ));
    }

    /**
     *
     * @Flow\After("method(TYPO3\Flow\Mvc\Routing\Route->matches(*))")
     * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint The current join point
     * @return array Result of the target method
     */
    public function logRoutes(\TYPO3\Flow\AOP\JoinPointInterface $joinPoint) {
        $route = $joinPoint->getProxy();
        \Debug\Toolbar\Service\DataStorage::add('Route:Routes', $route->getUriPattern());
    }

    /**
     *
     * @Flow\Before("filter(Debug\Toolbar\AOP\PointcutSettingsClassFilter)")
     * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint The current join point
     * @return array Result of the target method
     */
    public function profilerStart(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
        $run = \SandstormMedia\PhpProfiler\Profiler::getInstance()->getRun();
        $tag = ($joinPoint->getClassName() . '::') . $joinPoint->getMethodName();
        $run->startTimer($tag);
    }

    /**
     *
     * @Flow\After("filter(Debug\Toolbar\AOP\PointcutSettingsClassFilter)")
     * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint The current join point
     * @return array Result of the target method
     */
    public function profilerStop(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
        $run = \SandstormMedia\PhpProfiler\Profiler::getInstance()->getRun();
        $tag = ($joinPoint->getClassName() . '::') . $joinPoint->getMethodName();
        $run->stopTimer($tag);
    }

    /**
     * @param \TYPO3\Flow\AOP\JoinPointInterface $joinPoint
     * @Flow\Before("method(TYPO3\Flow\Http\RequestHandler->handleRequest())")
     * @return void
     */
    public function setStartTime(\TYPO3\Flow\AOP\JoinPointInterface $joinPoint) {
        \Debug\Toolbar\Service\DataStorage::set('Runtime:Start', microtime());
    }
}

?>