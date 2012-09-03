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
class RequestDebugger extends AbstractDebugger {

    /**
     * @var integer
     **/
    protected $priority = 10;

    /**
    * TODO: Document this Method! ( assignVariables )
    */
    public function assignVariables() {
        $tokens = array(

        );
        if ($this->has('RedirectedRequest')) {
            $tokens = explode(',', $this->get('RedirectedRequest'));
        }
        $tokens[] = \Debug\Toolbar\Service\DataStorage::get('Environment:Token');
        $this->view->assign('tokens', $tokens);
        $requests = array(

        );
        $steps = array(

        );
        foreach ($tokens as $key => $token) {
            $step = array(

            );
            $data = $this->debugger->getData($token);
            if (empty($data) || !isset($data['Request:ActionRequests'])) {
                continue;
            }
            $requests = array(

            );
            foreach ($data['Request:ActionRequests'] as $key => $value) {
                $requests[spl_object_hash($value)] = $value;
            }
            $this->view->assign('requests', $requests);
            $step['token'] = $token;
            $step['requests'] = $requests;
            foreach ($requests as $request) {
                if ($request->getParentRequest() instanceof \TYPO3\FLOW3\Http\Request) {
                    break;
                }
            }
            $this->view->assign('mainRequest', $request);
            $step['mainRequest'] = $request;
            if (isset($data['Request:Responses'])) {
                $response = current($data['Request:Responses']);
                $this->view->assign('response', $response);
                $step['response'] = $response;
                $step['responseCode'] = intval($response->getStatus());
                $step['responseColor'] = 'green';
                if ($step['responseCode'] >= 300) {
                    $step['responseColor'] = 'yellow';
                }
                if ($step['responseCode'] >= 400) {
                    $step['responseColor'] = 'red';
                }
            }
            if (isset($data['Route:Routes'])) {
                $this->view->assign('routes', $data['Route:Routes']);
            }
            $arrays = array(
            	'Get' => \Debug\Toolbar\Service\DataStorage::get('Request:Get'),
            	'Post' => \Debug\Toolbar\Service\DataStorage::get('Request:Post'),
            	'Cookie' => \Debug\Toolbar\Service\DataStorage::get('Request:Cookie'),
            	'Server' => \Debug\Toolbar\Service\DataStorage::get('Request:Server')
            );
            $this->view->assign('arrays', $arrays);
            $steps[] = $step;
        }
        $this->view->assign('steps', $steps);
    }

    /**
    * TODO: Document this Method! ( collectBeforeToolbarRendering )
    */
    public function collectBeforeToolbarRendering() {
        \Debug\Toolbar\Service\DataStorage::set('Request:Get', $_GET);
        \Debug\Toolbar\Service\DataStorage::set('Request:Post', $_POST);
        \Debug\Toolbar\Service\DataStorage::set('Request:Cookie', $_COOKIE);
        \Debug\Toolbar\Service\DataStorage::set('Request:Server', $_SERVER);
    }

    /**
    * TODO: Document this Method! ( collectRequests )
    */
    public function collectRequests($request) {
        $this->add('ActionRequests', $request);
    }

}

?>