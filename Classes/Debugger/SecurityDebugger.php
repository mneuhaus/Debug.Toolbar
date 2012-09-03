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
class SecurityDebugger extends AbstractDebugger {

    /**
     * @var \TYPO3\FLOW3\Security\Context
     * @FLOW3\Inject
     */
    protected $context;

    /**
     * @var integer
     **/
    protected $priority = 0;

    /**
    * TODO: Document this Method!
    */
    public function assignVariables() {
        $this->view->assign('roles', $this->get('Roles'));
        $this->view->assign('tokens', $this->get('Tokens'));
        $this->view->assign('account', $this->get('Account'));
        $votes = array(

        );
        $privilege = array(
            'PRIVILEGE_ABSTAIN',
            'PRIVILEGE_GRANT',
            'PRIVILEGE_DENY'
        );
        $roleVotes = \Debug\Toolbar\Service\DataStorage::get('Security:RoleVotes');
        if (is_array($roleVotes)) {
            foreach ($roleVotes as $key => $value) {
                $vote = array(
                    'role' => (string) $value['role']
                );
                $vote['privilege'] = '';
                if(is_array($value['privileges'])){
                    foreach ($value['privileges'] as $k => $p) {
                        $vote['privilege'] = $privilege[$p];
                    }
                }
                $votes[($value['role'] . ':') . $vote['privilege']] = $vote;
            }
        } else {
            $roles = $this->get('Roles');
            foreach ($roles as $key => $value) {
                $vote = array(
                    'role' => (string) $value
                );
                $votes[] = $vote;
            }
        }
        $this->view->assign('votes', $votes);
    }

    /**
    * TODO: Document this Method!
    */
    public function collectBeforeToolbarRendering() {
        $this->set('Roles', $this->context->getRoles());
        $this->set('Account', $this->context->getAccount());
    }

}

?>