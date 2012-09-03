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
     * @var \Debug\Toolbar\Service\Debugger
     * @author Marc Neuhaus <apocalip@gmail.com>
     * @FLOW3\Inject
     */
    protected $debugger;

    /**
     * @var \TYPO3\Fluid\View\StandaloneView
     */
    protected $view;

    /**
     * TODO: Document this Method! ( __construct )
     */
    public function __construct() {
        $this->view = new \TYPO3\Fluid\View\StandaloneView();
        $this->view->setTemplatePathAndFilename('resource://Debug.Toolbar/Private/Toolbar.html');
        $this->view->setFormat('html');
    }

    /**
     * TODO: Document this Method! ( attachToolbar )
     */
    public static function attachToolbar($content) {
        $toolbar = new \Debug\Toolbar\Toolbar\View();
        $content = str_replace('</body>', (('   ' . $toolbar->render()) . PHP_EOL) . '</body>', $content);
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
        $debuggers = $this->debugger->getDebuggers();
        foreach ($debuggers as $debugger) {
            $debugger->collectBeforeToolbarRendering();
        }
        \Debug\Toolbar\Service\DataStorage::save();
        $this->view->assign('dataRenderers', $debuggers);
        return $this->view->render();
    }

}

?>