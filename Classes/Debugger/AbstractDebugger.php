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
	public function __construct() {
		$this->view = new \TYPO3\Fluid\View\StandaloneView();

		$this->view->setPartialRootPath("resource://Debug.Toolbar/Private/Debugger/Partials");
		$this->view->setLayoutRootPath("resource://Debug.Toolbar/Private/Debugger/Layouts");
		
		$renderer = $this->getName();
		$package = $this->getPackage();
		$this->template = $template = "resource://".$package."/Private/Debugger/".$renderer.".html";
		$this->view->setTemplatePathAndFilename($template);
	}
    
    public function render() {
		$this->assignVariables();
		return $this->view->render();
    }

    public function renderWidget() {
    	$this->assignVariables();
		return $this->view->renderSection("Widget", null, true);	
    }

    public function getWidget() {
    	return $this->renderWidget();
    }

    public function renderPanel() {
    	$this->assignVariables();
		return $this->view->renderSection("Panel", null, true);	
    }

    public function getPanel() {
    	return $this->renderPanel();
    }

    public function __toString() {
    	return $this->getName();
    }

    public function getName() {
    	$class = get_class($this);
		preg_match("/(.+)\\\\Debugger\\\\(.+)Debugger/", $class, $match);
		return $match[2];
    }

    public function getPackage() {
    	$class = get_class($this);
		preg_match("/(.+)\\\\Debugger\\\\(.+)Debugger/", $class, $match);
		return str_replace("\\", ".", $match[1]);
    }

    public function collectBoot() {
    	
    }

    public function collectBeforeToolbarRendering() {
    	
    }

    public function collectShutdown() {
    	
    }
}

?>
