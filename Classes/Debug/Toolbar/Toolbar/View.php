<?php
namespace Debug\Toolbar\Toolbar;

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
 *
 * @FLOW3\Scope("singleton")
 */
class View {

    /**
     * @var \TYPO3\Fluid\View\StandaloneView
     */
    protected $view;

    /**
     * TODO: Document this Method! ( __construct )
     */
    public function __construct() {
        $this->view = new \TYPO3\Fluid\View\StandaloneView();
        $this->view->setTemplatePathAndFilename('resource://Debug.Toolbar/Private/Templates/Toolbar.html');
        $this->view->setPartialRootPath('resource://Debug.Toolbar/Private/Partials/');
        $this->view->setFormat('html');
    }

    /**
     * TODO: Document this Method! ( attachToolbar )
     */
    public static function attachToolbar($content) {
        $toolbar = new \Debug\Toolbar\Toolbar\View();
        if (stristr($content ,"</body>")) {
            $content = str_replace('</body>', (('   ' . $toolbar->render()) . PHP_EOL) . '</body>', $content);
        } else {
            $content.= $toolbar->render();
        }
        return $content;
    }

    /**
     * TODO: Document this Method! ( handleRedirects )
     */
    public static function handleRedirects($request, $response) {
        $previousTokens = array(

        );
        if ($request->hasArgument('__previousDebugToken')) {
            $previousTokens = explode(',', $request->getArgument('__previousDebugToken'));
            \Debug\Toolbar\Service\DataStorage::set('Request:RedirectedRequest', implode(',', $previousTokens));
        }
        if (intval($response->getStatus()) == 303) {
            $previousTokens[] = \Debug\Toolbar\Service\DataStorage::get('Environment:Token');
            $location = $response->getHeaders()->get('Location');
            $location .= stristr($location, '?') ? '&' : '?';
            $location .= '__previousDebugToken=' . implode(',', $previousTokens);
            $response->getHeaders()->set('Location', $location);
            $response->setContent(('<html><head><meta http-equiv="refresh" content="0;url=' . $location) . '"/></head></html>');
        }
    }

    /**
     * TODO: Document this Method! ( render )
     */
    public function render() {
        \Debug\Toolbar\Service\DataStorage::save();
        $this->view->assign('modules', \Debug\Toolbar\Service\Collector::getModules());
        return $this->view->render();
    }

}

?>