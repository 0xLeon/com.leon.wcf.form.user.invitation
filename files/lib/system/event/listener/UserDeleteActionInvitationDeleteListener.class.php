<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/invitation/InvitationEditor.class.php');

/**
 * Deletes an invitation when an user is deleted.
 * 
 * @author	Stefan Hahn
 * @copyright	2011 Stefan Hahn
 * @license	Simplified BSD License License <http://projects.swallow-all-lies.com/licenses/simplified-bsd-license.txt>
 * @package	com.leon.wcf.form.user.invitation
 * @subpackage	system.event.listener
 * @category 	Community Framework
 */
class UserDeleteActionInvitationDeleteListener implements EventListener {
	protected static $invitations = array();
	
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if ($eventName === 'execute') {
			foreach ($eventObj->userIDs as $userID) {
				$user = new User($userID);
				self::$invitations[] = new InvitationEditor(null, null, $user->invitationCode);
			}
			
			if ($eventObj->userID !== 0) {
				$user = new User($eventObj->userID);
				self::$invitations[] = new InvitationEditor(null, null, $user->invitationCode);
			}
		}
		else if ($eventName === 'executed') {
			foreach (self::$invitations as $invitation) {
				if ($invitation->invitationID) {
					$invitation->delete();
				}
			}
		}
	}
}
