{if $invitableCliques|count >= 1}
    <div class="contentBox">
        <div class="border"> 
    		<div class="containerHead"> 
    			<h3>{lang}wcf.clique.administrate.members{/lang}</h3> 
    		</div> 
    		<div class="container-1">
                <form method="post" action="index.php?action=CliqueInviteProfile&amp;userID={$user->userID}&amp;t={SECURITY_TOKEN}{@SID_ARG_2ND}" onsubmit="return alert('{lang}wcf.clique.invite.succes{/lang}');">
                    <div class="containerContent">
             			<select name="cliqueID">
                			{foreach from=$invitableCliques item=invitableClique}
                                <option value="{$invitableClique.cliqueID}">{$invitableClique.name}</option>
               				{/foreach}
             			</select>
    
                        <input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
                    </div>
                </form>
    		 </div>
    	</div>
    </div>
{/if}