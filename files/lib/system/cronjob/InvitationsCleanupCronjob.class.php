<?php
// wcf imports
require_once(WCF_DIR.'lib/data/cronjobs/Cronjob.class.php');

/**
 * Deletes accepted invitations with no user associated.
 * 
 * @author 	Stefan Hahn
 * @copyright	2011-2012 Stefan Hahn
 * @license	Simplified BSD License License <http://projects.swallow-all-lies.com/licenses/simplified-bsd-license.txt>
 * @package	com.leon.wcf.form.user.invitation
 * @subpackage	system.cronjob
 * @category 	Community Framework
 */
class InvitationsCleanupCronjob implements Cronjob {
	/**
	 * @see Cronjob::execute()
	 */
	public function execute($data) {
		$sql = "DELETE		invitation.*
			FROM		wcf".WCF_N."_user_invitation invitation
			LEFT JOIN 	wcf".WCF_N."_user wcfUser
			ON		invitation.code = wcfUser.invitationCode
			WHERE		wcfUser.userID IS NULL
					AND invitation.isSealed = 1";
		WCF::getDB()->sendQuery($sql);
	}
}
