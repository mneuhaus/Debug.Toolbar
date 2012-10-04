<?php
namespace Debug\Toolbar\Aop;

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
 *
 * @Flow\Proxy(false)
 * @Flow\Scope("singleton")
 */
class PointcutSettingsClassFilter implements \TYPO3\Flow\Aop\Pointcut\PointcutFilterInterface {

    /**
     * @var boolean
     */
    protected $cachedResult;

    /**
     * @var \TYPO3\Flow\Configuration\ConfigurationManager
     */
    protected $configurationManager;

    /**
     * The constructor - initializes the configuration filter with the path to a configuration option
     *
     * @param string $settingComparisonExpression Path (and optional condition) leading to the setting
     */
    public function __construct() {

    }

    /**
     * Returns runtime evaluations for the pointcut.
     *
     * @return array Runtime evaluations
     */
    public function getRuntimeEvaluationsDefinition() {
        return array(

        );
    }

    /**
     * Returns TRUE if this filter holds runtime evaluations for a previously matched pointcut
     *
     * @return boolean TRUE if this filter has runtime evaluations
     */
    public function hasRuntimeEvaluationsDefinition() {
        return FALSE;
    }

    /**
     * Injects the configuration manager
     *
     * @param \TYPO3\Flow\Configuration\ConfigurationManager $configurationManager
     * @return void
     */
    public function injectConfigurationManager(\TYPO3\Flow\Configuration\ConfigurationManager $configurationManager) {
        $this->configurationManager = $configurationManager;
        $this->matches = $this->configurationManager->getConfiguration(\TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'Debug.Profiling.Classes');
        foreach ($this->matches as $key => $value) {
            $this->matches[$key] = ('/^' . str_replace('\\', '\\\\', $value)) . '$/';
        }
    }

    /**
     * Checks if the specified configuration option is set to TRUE or FALSE, or if it matches the specified
     * condition
     *
     * @param string $className Name of the class to check against
     * @param string $methodName Name of the method - not used here
     * @param string $methodDeclaringClassName Name of the class the method was originally declared in - not used here
     * @param mixed $pointcutQueryIdentifier Some identifier for this query - must at least differ from a previous identifier. Used for circular reference detection.
     * @return boolean TRUE if the class matches, otherwise FALSE
     */
    public function matches($className, $methodName, $methodDeclaringClassName, $pointcutQueryIdentifier) {
        foreach ($this->matches as $regex) {
            $callback = (($className . '->') . $methodName) . '()';
            if (preg_match($regex, $callback)) {
                #var_dump($regex . " => " . $callback );
                return true;
            }
        }
        return false;
    }

    /**
     * This method is used to optimize the matching process.
     *
     * @param \TYPO3\Flow\Aop\Builder\ClassNameIndex $classNameIndex
     * @return \TYPO3\Flow\Aop\Builder\ClassNameIndex
     */
    public function reduceTargetClassNames(\TYPO3\Flow\Aop\Builder\ClassNameIndex $classNameIndex) {
        return $classNameIndex;
    }

}

?>