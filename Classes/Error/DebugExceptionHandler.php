<?php
namespace Debug\Toolbar\Error;

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
 * A basic but solid exception handler which catches everything which
 * falls through the other exception handlers and provides useful debugging
 * information.
 *
 * @FLOW3\Scope("singleton")
 */
class DebugExceptionHandler extends \TYPO3\FLOW3\Error\DebugExceptionHandler {

    /**
     * Formats and echoes the exception as XHTML.
     *
     * @param \Exception $exception The exception object
     * @return void
     */
    protected function echoExceptionWeb(\Exception $exception) {
        if (!headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }
        $exceptionHeader = '';
        while (true) {
            $pathPosition = strpos($exception->getFile(), 'Packages/');
            $filePathAndName = $pathPosition !== FALSE ? substr($exception->getFile(), $pathPosition) : $exception->getFile();
            $exceptionCodeNumber = $exception->getCode() > 0 ? ('#' . $exception->getCode()) . ': ' : '';
            $moreInformationLink = $exceptionCodeNumber != '' ? ('(<a href="http://typo3.org/go/exception/' . $exception->getCode()) . '">More information</a>)' : '';
            $createIssueLink = $this->getCreateIssueLink($exception);
            $exceptionHeader .= (((((((((('
				<strong style="color: #BE0027;">' . $exceptionCodeNumber) . htmlspecialchars($exception->getMessage())) . '</strong> ') . $moreInformationLink) . '<br />
				<br />
				<span class="ExceptionProperty">') . get_class($exception)) . '</span> thrown in file<br />
				<span class="ExceptionProperty">') . $filePathAndName) . '</span> in line
				<span class="ExceptionProperty">') . $exception->getLine()) . '</span>.<br />';
            if ($exception instanceof \TYPO3\FLOW3\Exception) {
                $exceptionHeader .= ('<span class="ExceptionProperty">Reference code: ' . $exception->getReferenceCode()) . '</span><br />';
            }
            if ($exception->getPrevious() === NULL) {
                $exceptionHeader .= ('<br /><a href="' . $createIssueLink) . '">Go to the FORGE issue tracker and report the issue</a> - <strong>if you think it is a bug!</strong><br />';
                break;
            } else {
                $exceptionHeader .= '<br /><div style="width: 100%; background-color: #515151; color: white; padding: 2px; margin: 0 0 6px 0;">Nested Exception</div>';
                $exception = $exception->getPrevious();
            }
        }
        $backtraceCode = \TYPO3\FLOW3\Error\Debugger::getBacktraceCode($exception->getTrace());
        echo ((('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"
				"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
			<head>
				<title>FLOW3 Exception</title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			</head>
			<style>
				.ExceptionProperty {
					color: #101010;
				}
				pre {
					margin: 0;
					font-size: 11px;
					color: #515151;
					background-color: #D0D0D0;
					padding-left: 30px;
				}
			</style>
			<div style="
					position: absolute;
					left: 10px;
					background-color: #B9B9B9;
					outline: 1px solid #515151;
					color: #515151;
					font-family: Arial, Helvetica, sans-serif;
					font-size: 12px;
					margin: 10px;
					padding: 0;
				">
				<div style="width: 100%; background-color: #515151; color: white; padding: 2px; margin: 0 0 6px 0;">Uncaught Exception in FLOW3</div>
				<div style="width: 100%; padding: 2px; margin: 0 0 6px 0;">
					' . $exceptionHeader) . '
					<br />
					') . $backtraceCode) . '
				</div>
			</div>
		';
        $response = new \TYPO3\FLOW3\Http\Response();
        $response->setStatus(400);
        \Debug\Toolbar\Service\DataStorage::add('Request:Responses', $response);
        $toolbar = new \Debug\Toolbar\Toolbar\View();
        echo $toolbar->render();
    }

}

?>