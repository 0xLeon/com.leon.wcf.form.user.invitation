<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/invitation/Invitation.class.php');

/**
 * Provides functions to create and edit the data of an user invitation.
 *
 * @author	Stefan Hahn
 * @copyright	2011 Stefan Hahn
 * @license	Simplified BSD License License <http://projects.swallow-all-lies.com/licenses/simplified-bsd-license.txt>
 * @package	com.leon.wcf.form.user.invitation
 * @subpackage	data.user.invitation
 * @category 	Community Framework
 */
class InvitationEditor extends Invitation {
	/**
	 * Creates a new invitation
	 * 
	 * @return 	Invitation
	 */
	public static function create($email) {
		$sql = "INSERT
			INTO	wcf".WCF_N."_user_invitation
				(time, senderID, email, code)
			VALUES	(".TIME_NOW.", ".WCF::getUser()->userID.", '".escapeString(StringUtil::toLowerCase($email))."', ".UserRegistrationUtil::getActivationCode().")";
		WCF::getDB()->sendQuery($sql);
		
		return new Invitation(WCF::getDB()->getInsertID());
	}
	
	/**
	 * Saves this invitation from further changes.
	 */
	public function seal() {
		$sql = "UPDATE	wcf".WCF_N."_user_invitation
			SET	isSealed = 1
			WHERE	invitationID = ".$this->invitationID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this invitation.
	 * 
	 * @return	boolean
	 */
	public function delete() {
		$sql = "DELETE
			FROM	wcf".WCF_N."_user_invitation
			WHERE	invitationID = ".$this->invitationID;
		WCF::getDB()->sendQuery($sql);
		
		$sql = "UPDATE	wcf".WCF_N."_user
			SET	invitationCode = 0
			WHERE	invitationCode = ".$this->code;
		WCF::getDB()->sendQuery($sql);
		
		return true;
	}
}
