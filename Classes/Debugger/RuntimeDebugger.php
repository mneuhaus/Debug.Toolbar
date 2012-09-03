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
class RuntimeDebugger extends AbstractDebugger {

    /**
    * TODO: Document this Method! ( assignVariables )
    */
    public function assignVariables() {
        $runtime = \Debug\Toolbar\Service\DataStorage::get('Profiling:StopTime') - \Debug\Toolbar\Service\DataStorage::get('Profiling:StartTime');
        $this->view->assign('runtime', number_format($runtime * 1000, 2));
        $durations = array(

        );
        $references = array(

        );
        if (is_array(\Debug\Toolbar\Service\DataStorage::get('Profiling:Durations'))) {
            $timers = \Debug\Toolbar\Service\DataStorage::get('Profiling:Durations');
            foreach ($timers as $key => $item) {
                if (stristr($item['name'], 'Boostrap Sequence:')) {
                    continue;
                }
                $durations[$item['name']] = $item;
            }
        }
        #arsort($durations);
        $this->view->assign('durations', $durations);
        $this->view->assign('data', json_encode(array(
            'name' => 'durations',
            'children' => $durations
        )));
    }

    /**
    * TODO: Document this Method! ( collectBeforeToolbarRendering )
    */
    public function collectBeforeToolbarRendering() {
        $this->collectShutdown();
        \Debug\Toolbar\Service\DataStorage::set('Profiling:StopTime', microtime(true));
    }

    /**
    * TODO: Document this Method! ( collectShutdown )
    */
    public function collectShutdown() {
        $run = \SandstormMedia\PhpProfiler\Profiler::getInstance()->getRun();
        if ($run instanceof \SandstormMedia\PhpProfiler\Domain\Model\ProfilingRun) {
            \Debug\Toolbar\Service\DataStorage::set('Profiling:Durations', $run->getTimersAsDuration(true));
            \Debug\Toolbar\Service\DataStorage::set('Profiling:StartTime', $run->getStartTime()->getTimestamp());
        }
    }

}

?>