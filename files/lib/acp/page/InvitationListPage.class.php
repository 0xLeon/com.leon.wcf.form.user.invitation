<?php
// wcf imports
require_once(WCF_DIR.'lib/page/SortablePage.class.php');
require_once(WCF_DIR.'lib/data/user/invitation/InvitationList.class.php');

/**
 * Shows a list of all invitations
 * 
 * @author 	Stefan Hahn
 * @copyright	2011-2012 Stefan Hahn
 * @license	Simplified BSD License License <http://projects.swallow-all-lies.com/licenses/simplified-bsd-license.txt>
 * @package	com.woltlab.wcf.form.user.invitation
 * @subpackage	acp.page
 * @category 	Community Framework
 */
class InvitationListPage extends SortablePage {
	public $templateName = 'invitationList';
	public $defaultSortField = 'invitation.time';
	public $defaultSortOrder = 'DESC';
	public $deletedInvitationID = 0;
	
	/**
	 * invitation list object
	 * 
	 * @var	InvitationList
	 */
	public $invitationList = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['deletedInvitationID'])) $this->deletedInvitationID = intval($_REQUEST['deletedInvitationID']);
		$this->invitationList = new InvitationList();
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->invitationList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->invitationList->sqlLimit = $this->itemsPerPage;
		$this->invitationList->sqlOrderBy = $this->sortField." ".$this->sortOrder;
		$this->invitationList->readObjects();
	}
	
	/**
	 * @see SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();
		
		switch ($this->sortField) {
			case 'invitationID':
			case 'time':
			case 'email':
			case 'code':
				$this->sortField = 'invitation.'.$this->sortField;
				break;
			case 'sender':
				$this->sortField = 'sender.username';
				break;
			case 'recipient':
				$this->sortField = 'recipient.username';
				break;
			default:
				$this->sortField = $this->defaultSortField;
		}
	}
	
	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->invitationList->countObjects();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'invitations' => $this->invitationList->getObjects(),
			'deletedInvitationID' => $this->deletedInvitationID
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		if (!MODULE_INVITATION) {
			throw new IllegalLinkException();
		}
		
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.user.invitation.view');
		
		WCF::getUser()->checkPermission(array('admin.invitation.canViewInvitationsList', 'admin.invitation.canDeleteInvitation'));
		
		parent::show();
	}
}
