			<fieldset>
				<legend><label for="invitationCode">{lang}wcf.user.register.invitation{/lang}</label></legend>
				<div class="formElement{if $errorType.invitationCode|isset} formError{/if}">
					<div class="formFieldLabel">
						<label for="invitationCode">{lang}wcf.user.register.invitation.code{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="invitationCode" value="{$invitationCode}" id="invitationCode" />
						
						{if $errorType.invitationCode|isset}
							<p class="innerError">
								{if $errorType.invitationCode == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								{if $errorType.invitationCode == 'false'}{lang}wcf.user.register.invitation.error.code.notValid{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc">
						<p>
							{lang}wcf.user.register.invitation.code.description{/lang}
							{if REGISTER_INVITATION_NECESSARY}
								{lang}wcf.user.register.invitation.code.necessary{/lang}
							{else}
								{lang}wcf.user.register.invitation.code.optional{/lang}
							{/if}
						</p>
					</div>
				</div>
			</fieldset>
