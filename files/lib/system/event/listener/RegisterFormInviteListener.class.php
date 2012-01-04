<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/invitation/InvitationEditor.class.php');

/**
 * Handles the invitation system during user registration.
 * 
 * @author		Stefan Hahn
 * @copyright	2011 Stefan Hahn
 * @license		Simplified BSD License License <http://projects.swallow-all-lies.com/licenses/simplified-bsd-license.txt>
 * @package		com.leon.wcf.form.user.invitation
 * @subpackage	system.event.listener
 * @category 	Community Framework
 */
class RegisterFormInviteListener implements EventListener {
	protected static $invitation = null;
	
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_INVITATION) {
			if ($eventName === 'readParameters') {
				if (isset($_GET['invitationCode']) && !empty($_GET['invitationCode'])) {
					$eventObj->additionalFields['invitationCode'] = intval($_GET['invitationCode']);
					self::$invitation = new InvitationEditor(null, null, $eventObj->additionalFields['invitationCode']);
				}
				else {
					$eventObj->additionalFields['invitationCode'] = '';
				}
				
				if (isset($_GET['email']) && !empty($_GET['email'])) $eventObj->email = $_GET['email'];
			}
			else if ($eventName === 'readFormParameters') {
				if (isset($_POST['invitationCode']) && !empty($_POST['invitationCode'])) {
					$eventObj->additionalFields['invitationCode'] = intval($_POST['invitationCode']);
					self::$invitation = new InvitationEditor(null, null, $eventObj->additionalFields['invitationCode']);
				}
			}
			else if ($eventName === 'assignVariables') {
				WCF::getTPL()->assign('invitationCode', $eventObj->additionalFields['invitationCode']);
			}
			else if ($eventName === 'show') {
				WCF::getTPL()->append('additionalFields', WCF::getTPL()->fetch('registerInviteField'));
			}
			else if ($eventName === 'validate') {
				try {
					if (REGISTER_INVITATION_NECESSARY && empty($eventObj->additionalFields['invitationCode'])) {
						throw new UserInputException('invitationCode');
					}
					
					if (!empty($eventObj->additionalFields['invitationCode']) && !Invitation::isValid($eventObj->email, $eventObj->additionalFields['invitationCode'])) {
						throw new UserInputException('invitationCode', 'false');
					}
				}
				catch (UserInputException $e) {
					$eventObj->errorType[$e->getField()] = $e->getType();
				}
			}
			else if ($eventName === 'saved') {
				self::$invitation->seal();
			}
		}
	}
}
