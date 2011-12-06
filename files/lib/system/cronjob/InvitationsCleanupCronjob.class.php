<?php
// wcf imports
require_once(WCF_DIR.'lib/data/cronjobs/Cronjob.class.php');
require_once(WCF_DIR.'lib/data/user/invitation/InvitationList.class.php');

/**
 * Does a cleanup of the saved user profile visitors.
 * 
 * @author 	Stefan Hahn
 * @copyright	2011 Stefan Hahn
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
		$sql = "DELETE FROM	wcf".WCF_N."_user_invitation invitation
				WHERE	invitation.code NOT IN (
						SELECT	invitationCode
						FROM	wcf".WCF_N."_user
						WHERE	invitationCode > 0
					)
					AND invitation.isSealed = 1";
		WCF::getDB()->registerShutdownUpdate($sql);
	}
}