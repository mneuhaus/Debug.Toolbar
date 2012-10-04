<?php
namespace Debug\Toolbar\ViewHelpers;

/*                                                                        *
 * This script belongs to the Flow package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 *
 * @api
 */
class IsArrayViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

    /**
     * renders <f:then> child if $condition is true, otherwise renders <f:else> child.
     *
     * @param mixed $condition View helper condition
     * @return string the rendered string
     * @api
     */
    public function render($condition) {
        if (is_array($condition)) {
            return $this->renderThenChild();
        } else {
            return $this->renderElseChild();
        }
    }

}

?>