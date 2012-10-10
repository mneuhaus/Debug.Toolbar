<?php
namespace Debug\Toolbar\Debugger;

/*                                                                        *
 * This script belongs to the FLOW3 package "Debug.Toolbar".              *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 */
class SecurityDebugger {

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $context;

	/**
	 * TODO: Document this Method! ( assignVariables )
	 */
	public function preToolbarRendering() {
		$account = $this->context->getAccount();

		$votes = array();
		$privilege = array(
			'PRIVILEGE_ABSTAIN',
			'PRIVILEGE_GRANT',
			'PRIVILEGE_DENY'
		);
		$roleVotes = \Debug\Toolbar\Service\DataStorage::get('Security:RoleVotes');
		if (is_array($roleVotes)) {
			foreach ($roleVotes as $key => $value) {
				$vote = array(
					'role' => (string)$value['role']
				);
				$vote['privilege'] = '';
				if (is_array($value['privileges'])) {
					foreach ($value['privileges'] as $k => $p) {
						$vote['privilege'] = $privilege[$p];
					}
				}
				$votes[($value['role'] . ':') . $vote['privilege']] = $vote;
			}
		} else {
			$roles = $this->context->getRoles();
			foreach ($roles as $key => $value) {
				$vote = array(
					'role' => (string)$value
				);
				$votes[] = $vote;
			}
		}

		\Debug\Toolbar\Service\Collector::getModule('Security')
			->getToolbar()
			->addIcon('user')
				// ->addHtml('<img width="24" height="28" alt="Security" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAcCAYAAAB75n/uAAAC70lEQVR42u2V3UtTYRzHu+mFwCwK+gO6CEryPlg7yiYx50vDqUwjFIZDSYUk2ZTmCysHvg9ZVggOQZiRScsR4VwXTjEwdKZWk8o6gd5UOt0mbev7g/PAkLONIOkiBx+25/v89vuc85zn2Q5Fo9F95UDwnwhS5HK5TyqVRv8m1JN6k+AiC+fn54cwbgFNIrTQ/J9IqDcJJDGBHsgDgYBSq9W6ysvLPf39/SSUUU7zsQ1yc3MjmN90OBzfRkZG1umzQqGIxPSTkIBjgdDkaGNjoza2kcFgUCE/QvMsq6io2PV6vQu1tbV8Xl7etkql2qqvr/+MbDE/Pz8s9OP2Cjhwwmw29+4R3Kec1WZnZ4fn5uamc3Jyttra2qbH8ero6JgdHh5+CvFHq9X6JZHgzODgoCVW0NPTY0N+ltU2Nzdv4GqXsYSrPp+vDw80aLFYxru6uhyQ/rDb7a8TCVJDodB1jUazTVlxcXGQ5/mbyE+z2u7u7veY38BVT3Z2djopm5qa6isrK/tQWVn5qb29fSGR4DC4PDAwMEsZHuArjGnyGKutq6v7ajQaF6urq9/MzMz0QuSemJiwQDwGkR0POhhXgILjNTU1TaWlpTxlOp1uyWQyaUjMajMzM8Nut/tJQUHBOpZppbCwkM/KytrBznuL9xDVxBMo8KXHYnu6qKjIivmrbIy67x6Px4Yd58W672ApfzY0NCyNjo7OZmRkiAv8fr+O47iwmABXtoXaG3uykF6vX7bZbF6cgZWqqiqezYkKcNtmjO+CF2AyhufgjsvlMiU7vXEF+4C4ALf9CwdrlVAqlcFkTdRqdQSHLUDgBEeSCrArAsiGwENs0XfJBE6ncxm1D8Aj/B6tigkkJSUlmxSwLYhMDeRsyyUCd+lHrWxtbe2aTCbbZTn1ZD92F0Cr8GBfgnsgDZwDt8EzMBmHMXBLqD0PDMAh9Gql3iRIESQSIAXp4CRIBZeEjIvDFZAm1J4C6UK9ROiZcvCn/+8FvwHtDdJEaRY+oQAAAABJRU5ErkJggg==">')
			->addText(is_object($account) ? $account->getAccountIdentifier() : 'Guest')
			->getPopup()
			->addPartial('Security', array(
			'roles' => $this->context->getRoles(),
			'account' => $this->context->getaccount(),
			'votes' => $votes
		))
			->getPanel()
			->addPartial('Security', array(
			'roles' => $this->context->getRoles(),
			'account' => $this->context->getaccount(),
			'votes' => $votes
		));
	}

}

?>