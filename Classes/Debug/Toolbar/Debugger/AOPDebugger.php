<?php
namespace Debug\Toolbar\Debugger;

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
 * @Flow\Scope("singleton")
 */
class AOPDebugger {

    /**
     * @var \TYPO3\Flow\Reflection\ReflectionService
     * @author Marc Neuhaus <apocalip@gmail.com>
     * @Flow\Inject
     */
    protected $reflectionService;

    /**
     * TODO: Document this Method!
     */
    public function cleanupComment($comment) {
        $comment = preg_replace('/^[^A-Za-z0-9@]*/m', '', $comment);
        $comment = preg_replace('/@.+\n/', '', $comment);
        $comment = trim($comment);
        return $comment;
    }

    public function preToolbarRendering() {
        $rawAdvices = (array) \Debug\Toolbar\Service\DataStorage::get('AOP:Advices');
        $advices = array();
        foreach ($rawAdvices as $key => $value) {
            if (stristr($value['adviceClass'], 'Debug\\Toolbar') && true) {

            } else {
                $key = implode('.', $value);
                if (isset($advices[$key])) {
                    $advices[$key]['counter']++;
                } else {
                    $advices[$key] = $value;
                    $advices[$key]['counter'] = 1;
                    $reflectionClass = new \ReflectionClass($value['adviceClass']);
                    $advices[$key]['classComment'] = $this->cleanupComment($reflectionClass->getDocComment());
                    $advices[$key]['methodComment'] = $this->cleanupComment($reflectionClass->getMethod($value['adviceMethodName'])->getDocComment());
                }
            }
        }

        $table = '';
        foreach ($advices as $advice) {
            $title = '<b>' . $advice['adviceClass'] . '->' . $advice['adviceClass'] . '</b> <br /> <small>Called ' . $advice['counter'] . ' times</small>';
            $content = $advice['joinPointClass'] . '->' . $advice['joinPointMethodName'];
            $table.= '<table class="table table-striped table-bordered small-break signals">';
            $table.= '<tr><th>' . $title . '</th></tr>';
            $table.= '<tr><td class="indent-left">' . $content . '</td></tr>';
            $table.= '</table>';
        }

        \Debug\Toolbar\Service\Collector::getModule("AOP")
            ->getToolbar()
                ->addText('AOP')
                ->addBadge(count($advices))
            ->getPopup()
                ->addHtml($table)
            ->getPanel()
                ->addHtml($table);
    }

    /**
     * TODO: Document this Method!
     */
    public function collectAdvices($adviceObject, $methodName, $joinPoint) {
        \Debug\Toolbar\Service\DataStorage::add("AOP:Advices", array(
            'adviceClass' => get_class($adviceObject),
            'adviceMethodName' => $methodName,
            'joinPointClass' => $joinPoint->getClassName(),
            'joinPointMethodName' => $joinPoint->getMethodName()
        ));
    }

}

?>