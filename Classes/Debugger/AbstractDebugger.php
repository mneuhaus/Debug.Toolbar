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
abstract class AbstractDebugger implements DebuggerInterface {

    /**
     * @var \Debug\Toolbar\Service\Debugger
     * @author Marc Neuhaus <apocalip@gmail.com>
     * @FLOW3\Inject
     */
    protected $debugger;

    /**
     *
     * @var integer
     **/
    protected $priority = 0;

    /**
    * TODO: Document this Method!
    */
    public function __construct() {

    }

    /**
    * TODO: Document this Method!
    */
    public function __toString() {
        return $this->getName();
    }

    /**
    * TODO: Document this Method!
    */
    public function add($key, $value) {
        \Debug\Toolbar\Service\DataStorage::add(($this->getName() . ':') . $key, $value);
    }

    /**
    * TODO: Document this Method!
    */
    public function collectBeforeToolbarRendering() {

    }

    /**
    * TODO: Document this Method!
    */
    public function collectBoot() {

    }

    /**
    * TODO: Document this Method!
    */
    public function collectShutdown() {

    }

    /**
    * TODO: Document this Method!
    */
    public function get($key) {
        return \Debug\Toolbar\Service\DataStorage::get(($this->getName() . ':') . $key);
    }

    /**
    * TODO: Document this Method!
    */
    public function getName() {
        $class = get_class($this);
        preg_match('/(.+)\\\\Debugger\\\\(.+)Debugger/', $class, $match);
        return $match[2];
    }

    /**
    * TODO: Document this Method!
    */
    public function getPackage() {
        $class = get_class($this);
        preg_match('/(.+)\\\\Debugger\\\\(.+)Debugger/', $class, $match);
        return str_replace('\\', '.', $match[1]);
    }

    /**
    * TODO: Document this Method!
    */
    public function getPanel() {
        $this->resolveView();
        return $this->renderPanel();
    }

    /**
    * TODO: Document this Method!
    */
    public function getPriority() {
        return $this->priority;
    }

    /**
    * TODO: Document this Method!
    */
    public function getWidget() {
        $this->resolveView();
        return $this->renderWidget();
    }

    /**
    * TODO: Document this Method!
    */
    public function has($key) {
        return \Debug\Toolbar\Service\DataStorage::has(($this->getName() . ':') . $key);
    }

    /**
    * TODO: Document this Method!
    */
    public function render() {
        $this->resolveView();
        $this->assignVariables();
        return $this->view->render();
    }

    /**
    * TODO: Document this Method!
    */
    public function renderPanel() {
        $this->resolveView();
        $this->assignVariables();
        return $this->view->renderSection('Panel', null, true);
    }

    /**
    * TODO: Document this Method!
    */
    public function renderWidget() {
        $this->resolveView();
        $this->view->assign('token', \Debug\Toolbar\Service\DataStorage::get('Environment:Token'));
        $this->assignVariables();
        return $this->view->renderSection('Widget', null, true);
    }

    /**
    * TODO: Document this Method!
    */
    public function resolveView() {
        $this->view = new \TYPO3\Fluid\View\StandaloneView();
        $this->view->setFormat('html');
        $this->view->setPartialRootPath('resource://Debug.Toolbar/Private/Debugger/Partials');
        $this->view->setLayoutRootPath('resource://Debug.Toolbar/Private/Debugger/Layouts');
        $renderer = $this->getName();
        $package = $this->getPackage();
        $this->template = ($template = ((('resource://' . $package) . '/Private/Debugger/') . $renderer) . '.html');
        $this->view->setTemplatePathAndFilename($template);
    }

    /**
    * TODO: Document this Method!
    */
    public function set($key, $value) {
        \Debug\Toolbar\Service\DataStorage::set(($this->getName() . ':') . $key, $value);
    }

}

?>