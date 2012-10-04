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
class SignalDebugger {

    /**
     * @var \TYPO3\FLOW3\Object\ObjectManagerInterface
     * @FLOW3\Inject
     */
    protected $objectManager;

    /**
    * TODO: Document this Method!
    */
    public function preToolbarRendering() {
        $dispatcher = $this->objectManager->get('TYPO3\\FLOW3\\SignalSlot\\Dispatcher');
        if(method_exists($dispatcher, "getSignals")){
            $classes = $this->objectManager->get('TYPO3\\FLOW3\\SignalSlot\\Dispatcher')->getSignals();
            \Debug\Toolbar\Service\Collector::getModule("Signals")
                ->getToolbar()
                    ->addText('Signals')
                    ->addBadge(count($classes))
                ->getPopup()
                    ->addPartial('Signals', array(
                        'classes' => $classes
                    ))
                ->getPanel()
                    ->addPartial('Signals', array(
                        'classes' => $classes
                    ));
        }
    }

}

?>