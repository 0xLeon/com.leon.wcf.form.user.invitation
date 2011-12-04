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
		$abandonedInvitations = new InvitationList();
		$abandonedInvitations->sqlLimit = 0;
		$abandonedInvitations->sqlConditions .= 'invitation.isSealed = 1';
		$abandonedInvitations->sqlOrderBy .= 'invitation.invitationID ASC';
		$abandonedInvitations->readObjects();
		
		$invitationIDs = array();
		foreach ($abandonedInvitations->getObjects() as $abandonedInvitation) {
			if ($abandonedInvitation->recipientUserID === null) {
				$invitationIDs[] = $abandonedInvitation->invitationID;
			}
		}
		
		$sql = "DELETE FROM	wcf".WCF_N."_user_invitation
			WHERE		invitationID IN (".implode(',', $invitationIDs).")";
		WCF::getDB()->registerShutdownUpdate($sql);
	}
}