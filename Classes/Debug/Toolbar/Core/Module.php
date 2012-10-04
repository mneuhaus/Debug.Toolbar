<?php
namespace Debug\Toolbar\Core;

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
 *
 */
class Module {
    protected $name;

    protected $priority = 0;

    protected $sections = array();

    protected $action = 'index';
    protected $controller = 'Profile';
    protected $package = 'Debug.Toolbar';
    protected $arguments = array();

    protected $token;

    public function __construct($name) {
        $this->name = $name;
        $this->sections = array(
            'toolbar' => new Section('toolbar'),
            'popup' => new Section('popup'),
            'panel' => new Section('panel')
        );
        $this->arguments = array(
            'token' => \Debug\Toolbar\Service\DataStorage::get('Environment:Token'),
            'module' => $name
        );
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    public function setPriority($priority) {
        $this->priority = $priority;
        return $this;
    }

    public function getPriority() {
        return $this->priority;
    }

    /**
     * @param string $action
     */
    public function setAction($action) {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * @param string $controller
     */
    public function setController($controller) {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * @param string $package
     */
    public function setPackage($package) {
        $this->package = $package;
    }

    /**
     * @return string
     */
    public function getPackage() {
        return $this->package;
    }

    /**
     * @param array $arguments
     */
    public function setArguments($arguments) {
        $this->arguments = $arguments;
    }

    /**
     * @return array
     */
    public function getArguments() {
        return $this->arguments;
    }

    public function getToolbar() {
        $this->context = $this->sections['toolbar'];
        return $this;
    }

    public function getPopup() {
        $this->context = $this->sections['popup'];
        return $this;
    }

    public function getPanel() {
        $this->context = $this->sections['panel'];
        return $this;
    }

    public function getSections() {
        return $this->sections;
    }

    public function addBadge($content, $class = 'inverse') {
        $class = strlen($class) > 0 ? "badge-" . $class : '';
        $this->context->addElement(new Element('Badge', array(
            'content' => $content,
            'class' => $class
        )));

        return $this;
    }

    public function addLabel($content, $class = 'inverse') {
        $class = strlen($class) > 0 ? "label-" . $class : '';
        $this->context->addElement(new Element('Label', array(
            'content' => $content,
            'class' => $class
        )));

        return $this;
    }

    public function addIcon($icon) {
        $this->context->addElement(new Element('Icon', array(
            'class' => $icon
        )));

        return $this;
    }

    public function addHtml($content) {
        $this->context->addElement(new Element('Html', array(
            'content' => $content
        )));

        return $this;
    }

    public function addText($text) {
        $this->context->addElement(new Element('Text', array(
            'text' => $text
        )));

        return $this;
    }

    public function addTable($array, $title = null, $classes = null) {
        $this->context->addElement(new Element('Table', array(
            'array' => $array,
            'title' => $title,
            'classes' => $classes
        )));

        return $this;
    }

    public function addList($array, $title = null) {
        $this->context->addElement(new Element('List', array(
            'array' => $array,
            'title' => $title
        )));

        return $this;
    }

    public function addPartial($partial, $arguments) {
        $this->context->addElement(new Element($partial, $arguments));

        return $this;
    }
}

?>