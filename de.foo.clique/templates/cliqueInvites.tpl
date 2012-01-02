<div class="info deletable" id="cliqueInvite">
	<a href="index.php?action=CliqueMessageDisable&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" class="close deleteButton"><img src="wcf/icon/closeS.png" alt="" title="{lang}wcf.clique.invite.cancel{/lang}" longdesc="" /></a>

        {* Einladungen *}
    {if $invites|count >= 1}
    	<p>{lang}wcf.clique.invite.text.{if $invites|count == 1}singulary{else}plural{/if}{/lang}:</p>
    	<ul class="itemList">
    		{foreach from=$invites item=invites}
    			<li class="deletable">
    				<div class="buttons">
    					<a href="index.php?action=CliqueInvite&amp;accept=1&amp;cliqueID={$invites.cliqueID}&amp;t={@SECURITY_TOKEN}" class="deleteButton" title="{lang}wcf.clique.invite.accept{/lang}"><img src="{icon}checkS.png{/icon}" alt="{lang}wcf.clique.invite.accept{/lang}" longdesc=""" /></a>
    					<a href="index.php?action=CliqueInvite&amp;decline=1&amp;cliqueID={$invites.cliqueID}&amp;t={@SECURITY_TOKEN}" class="deleteButton" title="{lang}wcf.clique.invite.decline{/lang}"><img src="{icon}deleteS.png{/icon}" alt="{lang}wcf.clique.invite.decline{/lang}" longdesc="" /></a>
    				</div>
    				<p class="itemListTitle">{lang}wcf.clique.invite.text.detail{/lang}</p>
    	       	</li>
    	   {/foreach}
    	</ul>
    {/if}

        {* Bewerbungen *}
    {if $aplications|count >= 1}
    	<p>{lang}wcf.clique.detail.applications{/lang}:</p>
    	<ul class="itemList">
			{foreach from=$aplications item=aplication}
				<div class="formField">
			     	{lang}wcf.clique.administrate.application.text{/lang}
					<a href="index.php?action=CliqueApplicationCancel&amp;cliqueID={$aplication.cliqueID}&amp;userID={$aplication.userID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.clique.administrate.application.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a>
                    <a href="index.php?action=CliqueApplicationAccept&amp;cliqueID={$aplication.cliqueID}&amp;userID={$aplication.userID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.clique.administrate.application.accept{/lang}"><img src="{icon}checkS.png{/icon}" alt="" /></a>
				</div>
			{/foreach}
    	</ul>
    {/if}

</div>

<script type="text/javascript">
	//<![CDATA[
	document.observe('wcf:inlineDelete', function() {
		if ($('cliqueInvite') && !$('cliqueInvite').down('li')) {
			inlineDelete($('cliqueInvite').down('.close'));
		}
	});
	//]]>
</script>