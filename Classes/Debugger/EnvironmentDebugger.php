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
class EnvironmentDebugger extends AbstractDebugger {

    /**
     * @var \TYPO3\FLOW3\Configuration\ConfigurationManager
     * @FLOW3\Inject
     */
    protected $configurationManager;

    /**
     * @var \TYPO3\FLOW3\Object\ObjectManagerInterface
     * @author Marc Neuhaus <apocalip@gmail.com>
     * @FLOW3\Inject
     */
    protected $objectManager;

    /**
     * @FLOW3\Inject
     * @var \TYPO3\FLOW3\Session\SessionInterface
     */
    protected $session;

    /**
    * TODO: Document this Method! ( assignVariables )
    */
    public function assignVariables() {
        //    'symfony_version' => Kernel::VERSION,
        //    'name'            => $this->kernel->getName(),
        //    'php_version'     => PHP_VERSION,
        //    'xdebug_enabled'  => extension_loaded('xdebug'),
        //    'eaccel_enabled'  => extension_loaded('eaccelerator') && ini_get('eaccelerator.enable'),
        //    'apc_enabled'     => extension_loaded('apc') && ini_get('apc.enabled'),
        //    'xcache_enabled'  => extension_loaded('xcache') && ini_get('xcache.cacher'),
        $this->view->assign('php_version', PHP_VERSION);
        $this->view->assign('context', $this->objectManager->getContext());
        $this->view->assign('flow3_version', FLOW3_VERSION_BRANCH);
        $memoryUsage = (memory_get_peak_usage(true) / 1024) / 1024;
        $memoryUsage = number_format($memoryUsage, 1) . ' MB';
        $this->view->assign('memoryUsage', $memoryUsage);
        $configurations = array(

        );
        foreach ($configurations as $configurationName => $configurationConstant) {
            $configuration = $this->configurationManager->getConfiguration($configurationConstant);
            $configurations[$configurationName] = \Symfony\Component\Yaml\Yaml::dump($configuration, 10);
        }
        #$configurations["Constants"] = \Symfony\Component\Yaml\Yaml::dump(get_defined_constants(), 10);
        $this->view->assign('configurations', $configurations);
    }

    /**
    * TODO: Document this Method! ( collectBeforeToolbarRendering )
    */
    public function collectBeforeToolbarRendering() {

    }

}

?>