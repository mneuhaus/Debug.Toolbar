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
class RequestDebugger {

    /**
    * TODO: Document this Method! ( assignVariables )
    */
    public function preToolbarRendering() {
        $tokens = array();
        if (\Debug\Toolbar\Service\DataStorage::has('Request:RedirectedRequest')) {
            $tokens = explode(',', \Debug\Toolbar\Service\DataStorage::get('Request:RedirectedRequest'));
        }
        $tokens[] = \Debug\Toolbar\Service\DataStorage::get('Environment:Token');
        #$this->view->assign('tokens', $tokens);
        \Debug\Toolbar\Service\DataStorage::save();
        $requests = array();
        $steps = array();
        $routes = array();
        foreach ($tokens as $key => $token) {
            $step = array();
            $data = \Debug\Toolbar\Service\DataStorage::getData($token);
            if (empty($data) || !isset($data['Request:ActionRequests'])) {
                continue;
            }
            $requests = array(

            );
            foreach ($data['Request:ActionRequests'] as $key => $value) {
                $requests[spl_object_hash($value)] = $value;
            }
            #$this->view->assign('requests', $requests);
            $step['token'] = $token;
            $step['requests'] = $requests;
            foreach ($requests as $request) {
                if ($request->getParentRequest() instanceof \TYPO3\FLOW3\Http\Request) {
                    break;
                }
            }

            #$this->view->assign('mainRequest', $request);
            $step['mainRequest'] = $request;
            if (isset($data['Request:Responses'])) {
                $response = current($data['Request:Responses']);
                #$this->view->assign('response', $response);
                $step['response'] = $response;
                $step['responseCode'] = intval($response->getStatus());
                $step['responseColor'] = 'badge-success';
                if ($step['responseCode'] >= 300) {
                    $step['responseColor'] = 'badge-warning';
                }
                if ($step['responseCode'] >= 400) {
                    $step['responseColor'] = 'badge-important';
                }
            }
            if (isset($data['Route:Routes'])) {
                $routes = $data['Route:Routes'];
            }
            $arrays = array(
            	'Get' => $_GET,
            	'Server' => $_SERVER
            );

            if ($_POST) {
                $arrays['Post'] = $_POST;
            }

            if ($_COOKIE) {
                $arrays['Cookie'] = $_COOKIE;
            }

            $steps[] = $step;
        }
        foreach ($steps as $key => $step) {
            $partial = "Request\Request";
            if (count($steps) != $key + 1) {
                $partial = 'Request\RequestPrevious';
            }

            \Debug\Toolbar\Service\Collector::getModule("Request" . $key)
                ->setPriority(100 - $key)
                ->getToolbar()
                    ->addPartial($partial, array(
                        'request' => $step
                    ))
                ->getPopup()
                    ->addPartial('Request\Arguments', array('requests' => $step['requests']))
                ->getPanel()
                    ->addPartial('Request\Details', array(
                        'requests' => $step['requests'],
                        'arrays' => $arrays,
                        'routes' => $routes
                    ));
        }
    }

    /**
    * TODO: Document this Method! ( collectRequests )
    */
    public function collectRequests($request) {
        \Debug\Toolbar\Service\DataStorage::add('Request:ActionRequests', $request);
    }

}

?>