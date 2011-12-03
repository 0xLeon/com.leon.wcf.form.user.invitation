<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractSecureForm.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
require_once(WCF_DIR.'lib/data/user/invitation/InvitationEditor.class.php');
require_once(WCF_DIR.'lib/data/user/invitation/InvitationList.class.php');
require_once(WCF_DIR.'lib/data/mail/Mail.class.php');
require_once(WCF_DIR.'lib/page/util/menu/UserCPMenu.class.php');
require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');

/**
 * Shows the white list edit form.
 * 
 * @author		Stefan Hahn
 * @copyright	2011 Stefan Hahn
 * @license		Simplified BSD License License <http://projects.swallow-all-lies.com/licenses/simplified-bsd-license.txt>
 * @package		com.leon.wcf.form.user.invitation
 * @subpackage	form
 * @category 	Community Framework
 */
class InvitationListEditForm extends AbstractSecureForm {
	public $templateName = 'invitationListEdit';
	public $neededPermissions = 'user.invitation.canInvite';
	
	public $emails = '';
	public $invitedMails = array();
	
	/**
	 * unanswered invitation list object
	 * 
	 * @var	InvitationList
	 */
	public $unansweredInvitationList = null;
	
	/**
	 * accepted invitation list object
	 * 
	 * @var	array<UserProfile>
	 */
	public $invitedMembers = array();
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_GET['cancel'])) {
			$invitation = new InvitationEditor(intval($_GET['cancel']));
			if (!$invitation->invitationID) {
				throw new IllegalLinkException();
			}
			
			if ($invitation->senderID !== WCF::getUser()->userID) {
				throw new PermissionDeniedException();
			}
			
			if ($invitation->isSealed) {
				throw new NamedUserException(WCF::getLanguage()->get('wcf.user.invitation.error.sealed'));
			}
			
			$invitation->delete();
			
			WCF::getTPL()->assign(array(
				'success' => 'cancel',
				'invitation' => $invitation
			));
		}
		else if (isset($_GET['add'])) {
			$this->emails = $_GET['add'];
			$this->submit();
		}
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['emails'])) $this->emails = StringUtil::trim($_POST['emails']);
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		if (count($this->invitedMails) === 0) {
			if (empty($this->emails)) {
				throw new UserInputException('emails');
			}
			
			$this->validateEmails();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		$invitedFriends = array();
		foreach ($this->invitedMails as $email) {
			$invitation = InvitationEditor::create($email);
			
			$mail = new Mail(array($email => $email),
				WCF::getLanguage()->get('wcf.user.register.invitation.mail.subject', array(
					'PAGE_TITLE' => WCF::getLanguage()->get(PAGE_TITLE)
				)),
				WCF::getLanguage()->get('wcf.user.register.invitation.mail', array(
					'PAGE_TITLE' => WCF::getLanguage()->get(PAGE_TITLE),
					'PAGE_URL' => PAGE_URL,
					'MAIL_ADMIN_ADDRESS' => MAIL_ADMIN_ADDRESS,
					'$username' => $invitation->senderUsername,
					'$invitationCode' => $invitation->code,
					'$email' => $invitation->email,
					'$emailUrlEncoded' => urlencode($invitation->email)
				))
			);
			$mail->send();
			
			$invitedFriends[] = $invitation;
		}
		
		$this->saved();
		
		$this->emails = '';
		
		WCF::getTPL()->assign(array(
			'success' => 'add',
			'invitedFriends' => $invitedFriends
		));
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->unansweredInvitationList = new InvitationList();
		$this->unansweredInvitationList->sqlLimit = 0;
		$this->unansweredInvitationList->sqlConditions .= 'sender.userID = '.WCF::getUser()->userID.' AND invitation.isSealed = 0';
		$this->unansweredInvitationList->sqlOrderBy .= 'invitation.email ASC';
		$this->unansweredInvitationList->readObjects();
		
		$acceptedInvitationList = new InvitationList();
		$acceptedInvitationList->sqlLimit = 0;
		$acceptedInvitationList->sqlConditions .= 'sender.userID = '.WCF::getUser()->userID.' AND invitation.isSealed = 1';
		$acceptedInvitationList->sqlOrderBy .= 'recipient.username ASC';
		$acceptedInvitationList->readObjects();
		
		foreach ($acceptedInvitationList->getObjects() as $acceptedInvitation) {
			$this->invitedMembers[] = new UserProfile($acceptedInvitation->recipientUserID);
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'emails' => $this->emails,
			'unansweredInvitationList' => $this->unansweredInvitationList->getObjects(),
			'invitedMembers' => $this->invitedMembers
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		if (!WCF::getUser()->userID) {
			throw new PermissionDeniedException();
		}
		
		if (!MODULE_INVITATION) {
			throw new IllegalLinkException();
		}
		
		WCF::getUser()->checkPermission($this->neededPermissions);
		
		UserCPMenu::getInstance()->setActiveMenuItem('wcf.user.usercp.menu.link.management.invitations');
		
		parent::show();
	}
	
	protected function validateEmails() {
		$emailArray = explode(',', $this->emails);
		$error = array();
		
		foreach ($emailArray as $email) {
			$email = StringUtil::trim($email);
			if (empty($email)) continue;
			
			try {
				$this->validateEmail($email);
				
				$this->invitedMails[] = $email;
			}
			catch (UserInputException $e) {
				$error[] = array('type' => $e->getType(), 'email' => $email);
			}
		}
		
		if (count($error)) {
			throw new UserInputException('emails', $error);
		}
	}
	
	protected function validateEmail($email) {
		if (!UserRegistrationUtil::isValidEmail($email)) {
			throw new UserInputException('email', 'notValid');
		}
		
		if (!UserUtil::isAvailableEmail($email)) {
			throw new UserInputException('email', 'alreadyUsed');
		}
	}
}
