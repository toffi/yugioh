<div class="formElement{if $errorType.groupTime|isset} formError{/if}" id="groupTimeDiv">
	<div class="formFieldLabel">
		<label for="groupTime">{lang}wcf.acp.group.groupTime{/lang}</label>
	</div>
	<div class="formField">
		<input type="text" class="inputText" id="groupTime" name="groupTime" value="{$groupTime}" />
		{if $errorType.groupTime|isset}
			<p class="innerError">
				{if $errorType.groupTime == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
			</p>
		{/if}
	</div>
	<div class="formFieldDesc" id="groupTimeHelpMessage">
		<p>{lang}wcf.acp.group.groupTime.description{/lang}</p>
	</div>
</div>