<form method="post" action="index.php?form=CliqueApplication&amp;cliqueID={@$cliqueID}">
	<div class="message content messageMinimized" id="hiddendiv" style="display: {if $applicationOpen == 1}block{else}none{/if};">
		<div class="messageInner container-1">
			<div class="formElement">
				<div class="formFieldLabel">
					<label for="applicationMessage">{lang}wcf.clique.detail.application{/lang}</label>
				</div>
				<div class="formField">
					<textarea name="applicationMessage" id="applicationMessage" rows="10" cols="40"></textarea>
				</div>
			</div>
			<div class="formSubmit" id="quickReplyButtons-{@$cliqueID}">
				<input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
				<input type="reset" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
				{@SID_INPUT_TAG}
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">
	function toggledisplay(id){
		if (document.getElementById) {
			var mydiv = document.getElementById(id);
			mydiv.style.display = (mydiv.style.display=='block'?'none':'block');
		}
	}
</script>