<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/user/invitation/InvitationEditor.class.php');

/**
 * Deletes an invitation.
 * 
 * @author 		Stefan Hahn
 * @copyright	2011 Stefan Hahn
 * @license		Simplified BSD License License <http://projects.swallow-all-lies.com/licenses/simplified-bsd-license.txt>
 * @package		com.woltlab.wcf.form.user.invitation
 * @subpackage	acp.action
 * @category 	Community Framework
 */
class InvitationDeleteAction extends AbstractAction {
	/**
	 * invitation id
	 *
	 * @var integer
	 */
	public $invitationID = 0;
	
	/**
	 * invitation editor object
	 *
	 * @var InvitationEditor
	 */
	public $invitation = null;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['invitationID'])) $this->invitationID = intval($_REQUEST['invitationID']);
		$this->invitation = new InvitationEditor($this->invitationID);
		if (!$this->invitation->invitationID || $this->invitation->isSealed) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// check permission
		WCF::getUser()->checkPermission('admin.invitation.canDeleteInvitation');
				
		// delete invitation
		$this->invitation->delete();
		
		$this->executed();
		
		// forward to list page
		HeaderUtil::redirect('index.php?page=InvitationList&deletedInvitationID='.$this->invitationID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
