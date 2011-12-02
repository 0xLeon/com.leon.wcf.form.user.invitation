{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/groupAddL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.invitation.view{/lang}</h2>
	</div>
</div>

{if $deletedInvitationID}
	<p class="success">{lang}wcf.acp.invitation.delete.success{/lang}</p>	
{/if}

<div class="contentHeader">
	{pages print=true assign=pagesLinks link="index.php?page=InvitationList&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}
</div>

{if $invitations|count}
	<div class="border titleBarPanel">
		<div class="containerHead"><h3>{lang}wcf.acp.invitation.view.count{/lang}</h3></div>
	</div>
	<div class="border borderMarginRemove">
		<table class="tableList">
			<thead>
				<tr class="tableHead">
					<th class="columnInvitationID" colspan="2"><div><span class="emptyHead">{lang}wcf.acp.invitation.invitationID{/lang}</span></div></th>
					<th class="columnInvitationDate{if $sortField == 'invitation.time'} active{/if}"><div><a href="index.php?page=InvitationList&amp;pageNo={@$pageNo}&amp;sortField=time&amp;sortOrder={if $sortField == 'invitation.time' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.invitation.time{/lang}{if $sortField == 'invitation.time'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnInvitationSender{if $sortField == 'sender.username'} active{/if}"><div><a href="index.php?page=InvitationList&amp;pageNo={@$pageNo}&amp;sortField=sender&amp;sortOrder={if $sortField == 'sender.username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.invitation.sender{/lang}{if $sortField == 'sender.username'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnInvitationRecipient{if $sortField == 'recipient.username'} active{/if}"><div><a href="index.php?page=InvitationList&amp;pageNo={@$pageNo}&amp;sortField=recipient&amp;sortOrder={if $sortField == 'recipient.username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.invitation.recipient{/lang}{if $sortField == 'recipient.username'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnInvitationEmail{if $sortField == 'invitation.email'} active{/if}"><div><a href="index.php?page=InvitationList&amp;pageNo={@$pageNo}&amp;sortField=email&amp;sortOrder={if $sortField == 'invitation.email' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.invitation.email{/lang}{if $sortField == 'invitation.email'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnInvitationCode{if $sortField == 'invitation.code'} active{/if}"><div><a href="index.php?page=InvitationList&amp;pageNo={@$pageNo}&amp;sortField=code&amp;sortOrder={if $sortField == 'invitation.code' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.invitation.code{/lang}{if $sortField == 'invitation.code'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					
					{if $additionalColumns|isset}{@$additionalColumns}{/if}
				</tr>
			</thead>
			<tbody>
			{foreach from=$invitations item=$invitation}
				<tr class="{cycle values="container-1,container-2"}">
					<td class="columnIcon">
						{if $this->user->getPermission('admin.invitation.canDeleteInvitation') && !$invitation->isSealed}
							<a onclick="return confirm('{lang}wcf.acp.invitation.delete.sure{/lang}')" href="index.php?action=InvitationDelete&amp;invitationID={@$invitation->invitationID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" title="{lang}wcf.acp.invitation.delete{/lang}" /></a>
						{else}
							<img src="{@RELATIVE_WCF_DIR}icon/deleteDisabledS.png" alt="" title="{lang}wcf.acp.invitation.delete{/lang}" />
						{/if}
						
						{if $invitation->additionalButtons|isset}{@$invitation->additionalButtons}{/if}
					</td>
					<td class="columnInvitationID columnID">{@$invitation->invitationID}</td>
					<td class="columnInvitationDate columnText">{$invitation->time|date:"%e. %B %Y, %H:%M"}</td>
					<td class="columnInvitationSender columnText">{$invitation->senderUsername}</td>
					<td class="columnInvitationRecipient columnText">{$invitation->recipientUsername}</td>
					<td class="columnInvitationEmail columnText">{$invitation->email}</td>
					<td class="columnInvitationCode columnNumbers">{$invitation->code}</td>
					
					{if $invitation->additionalColumns|isset}{@$invitation.additionalColumns}{/if}
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
	
	
	<div class="contentFooter">
		{@$pagesLinks}
	</div>
{else}
	<div class="border content">
		<div class="container-1">
			<p>{lang}wcf.acp.invitation.count.noEntries{/lang}</p>
		</div>
	</div>
{/if}

{include file='footer'}
