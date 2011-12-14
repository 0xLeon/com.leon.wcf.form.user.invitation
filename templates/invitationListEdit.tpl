{include file="documentHeader"}
<head>
	<title>{lang}wcf.user.invitation.title{/lang} - {lang}wcf.user.usercp{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{include file='header' sandbox=false}

<div id="main">
	
	{capture append=userMessages}
		{if $errorField}
			<p class="error">{lang}wcf.global.form.error{/lang}</p>
		{/if}
		
		{if $success|isset}
			<p class="success">
				{if $success == 'cancel'}{lang}wcf.user.invitation.cancel.success{/lang}{/if}
				{if $success == 'add'}
					{if $invitedFriends|count == 1}
						{lang}wcf.user.invitation.offer.success.single{/lang}
					{else}
						{lang}wcf.user.invitation.offer.success.multiple{/lang}
					{/if}
				{/if}
			</p>
		{/if}
	{/capture}
	
	{assign var='unansweredInvitationsAmount' value=$unansweredInvitationList|count}
	{assign var='invitedMembersAmount' value=$invitedMembers|count}
	{assign var='invitationAmount' value=$unansweredInvitationsAmount + $invitedMembersAmount}
	{assign var='maxInvitations' value=$this->user->getPermission('user.invitation.maxInvitations')|intval}
	{assign var='invitationsLeft' value=$maxInvitations - $invitationAmount}
	
	{include file="userCPHeader"}
	
	<form method="post" action="index.php?form=InvitationListEdit">
		<div class="border tabMenuContent">
			<div class="container-1">
				<h3 class="subHeadline">{lang}wcf.user.invitation.title{/lang}</h3>
				
				{if ($maxInvitations > 0 && $invitationsLeft > 0) || ($maxInvitations === 0)}
					<div class="formElement{if $errorField == 'emails'} formError{/if}">
						<div class="formFieldLabel">
							<label for="emails">{lang}wcf.user.invitation.email{/lang}</label>
						</div>
							
						<div class="formField">
							<input type="text" class="inputText" name="emails" value="{$emails}" id="emails" />
							{if $errorField == 'emails'}
								<div class="innerError">
									{if $errorType|is_array}
										{foreach from=$errorType item=error}
											<p>
												{if $error.type == 'notValid'}{lang}wcf.user.error.email.notValid{/lang}{/if}
												{if $error.type == 'alreadyUsed'}{lang email=$error.email}wcf.user.invitation.error.email.alreadyUsed{/lang}{/if}
												{if $error.type == 'invitationLimitExceeded'}{lang}wcf.user.invitation.error.email.invitationLimitExceeded{/lang}{/if}
											</p>
										{/foreach}
									{else}
										{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
									{/if}
								</div>
							{/if}
						</div>
						<div class="formFieldDesc">
							<p>{lang max=$maxInvitations left=$invitationsLeft}wcf.user.invitation.email.description{/lang}</p>
						</div>
					</div>
					
					{if $additionalFields|isset}{@$additionalFields}{/if}
				{/if}
				
				{if $unansweredInvitationList|count}
					<fieldset>
						<legend>{lang}wcf.user.invitation.unansweredInvitationList{/lang}</legend>
						
						<ul class="memberList">
							{foreach from=$unansweredInvitationList item=$invitation}
								<li class="deletable">
									<span class="memberName"><span>{$invitation}</span></span>
									<a href="index.php?form=InvitationListEdit&amp;cancel={@$invitation->invitationID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.user.invitation.cancel{/lang}" class="memberRemove deleteButton"><img src="{icon}deleteS.png{/icon}" alt="" longdesc="{lang}wcf.user.invitation.cancel.sure{/lang}" /></a>
								</li>
							{/foreach}
						</ul>
					</fieldset>
				{/if}
				
				{if $invitedMembers|count}
					<fieldset>
						<legend>{lang}wcf.user.invitation.invitedMembers{/lang}</legend>
						
						<ul class="memberList">
							{foreach from=$invitedMembers item=$member}
								<li>
									{if $member->isOnline()}
										<img src="{icon}onlineS.png{/icon}" alt="" title="{lang username=$member}wcf.user.online{/lang}" class="memberListStatusIcon" />
									{else}
										<img src="{icon}offlineS.png{/icon}" alt="" title="{lang username=$member}wcf.user.offline{/lang}" class="memberListStatusIcon" />
									{/if}
									<a href="index.php?page=User&amp;userID={@$member->userID}{@SID_ARG_2ND}" title="{lang username=$member}wcf.user.viewProfile{/lang}" class="memberName"><span>{$member}</span></a>
									
								</li>
							{/foreach}
						</ul>
					</fieldset>
				{/if}
				
				{@SID_INPUT_TAG}
				</div>
		</div>
		{if ($maxInvitations > 0 && $invitationsLeft > 0) || ($maxInvitations === 0)}
			<div class="formSubmit">
				<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
				<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
				{@SECURITY_TOKEN_INPUT_TAG}
			</div>
		{/if}
	</form>
</div>

{include file='footer' sandbox=false}
</body>
</html>
