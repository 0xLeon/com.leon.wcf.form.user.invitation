<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/user/invitation/Invitation.class.php');

/**
 * Represents a list of invitations.
 * 
 * @author		Stefan Hahn
 * @copyright	2011 Stefan Hahn
 * @license		Simplified BSD License License <http://projects.swallow-all-lies.com/licenses/simplified-bsd-license.txt>
 * @package		com.woltlab.wcf.form.user.invitation
 * @subpackage	data.user.invitation
 * @category 	Community Framework
 */
class InvitationList extends DatabaseObjectList {
	/**
	 * list of invitations
	 * 
	 * @var array<Invitation>
	 */
	public $invitations = array();
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_user_invitation invitation
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					invitation.*,
					sender.userID AS senderUserID,
					sender.username AS senderUsername,
					recipient.userID AS recipientUserID,
					recipient.username AS recipientUsername
			FROM		wcf".WCF_N."_user_invitation invitation
			LEFT JOIN	wcf".WCF_N."_user sender
			ON			(sender.userID = invitation.senderID)
			LEFT JOIN	wcf".WCF_N."_user recipient
			ON			(recipient.invitationCode = invitation.code)
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->invitations[] = new Invitation(null, $row);
		}
	}
	
	/**
	 * @see DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->invitations;
	}
}
