<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents an invitation.
 *
 * @author	Stefan Hahn
 * @copyright	2011-2012 Stefan Hahn
 * @license	Simplified BSD License License <http://projects.swallow-all-lies.com/licenses/simplified-bsd-license.txt>
 * @package	com.leon.wcf.form.user.invitation
 * @subpackage	data.user.invitation
 * @category 	Community Framework
 */
class Invitation extends DatabaseObject {
	/**
	 * Creates a new Invitation object.
	 * 
	 * @param	integer		$invitationID
	 * @param	array		$row
	 */
	public function __construct($invitationID, $row = null, $code = null) {
		$sqlCondition = '';
		
		if ($invitationID !== null) {
			$sqlCondition = "invitation.invitationID = ".intval($invitationID);
		}
		else if ($code !== null) {
			$sqlCondition = "invitation.code = ".intval($code);
		}
		
		if (!empty($sqlCondition)) {
			$sql = "SELECT	
						invitation.*,
						sender.userID AS senderUserID,
						sender.username AS senderUsername,
						recipient.userID AS recipientUserID,
						recipient.username AS recipientUsername
				FROM		wcf".WCF_N."_user_invitation invitation
				LEFT JOIN	wcf".WCF_N."_user sender
				ON		(sender.userID = invitation.senderID)
				LEFT JOIN	wcf".WCF_N."_user recipient
				ON		(recipient.invitationCode = invitation.code)
				WHERE 	".$sqlCondition;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		
		parent::__construct($row);
	}
	
	public function __toString() {
		return $this->email;
	}
	
	/**
	 * Returns wether or not this invitation is valid.
	 * 
	 * @return	boolean
	 */
	public static function isValid($email, $code) {
		$sql = "SELECT	COUNT(*) AS count
			FROM 	wcf".WCF_N."_user_invitation
			WHERE	email = '".escapeString(StringUtil::toLowerCase($email))."'
				AND code = ".intval($code)."
				AND isSealed = 0";
		$row = WCF::getDB()->getFirstRow($sql);
		
		return (intval($row['count']) === 1);
	}
}
