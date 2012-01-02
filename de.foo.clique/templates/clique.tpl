{include file="documentHeader"}
<head>
	<title>{lang}wcf.clique.general.name.plural{/lang} - {lang}wcf.user.usercp{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{* --- quick search controls --- *}
{assign var='searchFieldTitle' value='{lang}wcf.clique.general.search{/lang}'}
{assign var='searchFieldOptions' value=false}
{capture assign=searchHiddenFields}
	<input type="hidden" name="types[]" value="clique" />
{/capture}
{* --- end --- *}
{include file='header' sandbox=false}

<div id="main">
	{include file="userCPHeader"}

	<div class="border tabMenuContent">
		<div class="container-1">
			<h3 class="subHeadline">{lang}wcf.clique.general.name.plural{/lang}</h3>
			
			{if $memberships|count > 0}
				<fieldset>
					<legend>{lang}wcf.clique.membership.own{/lang}</legend>
					{assign var=membershipMultiplePagesLink value="index.php?page=Clique&sortableOrder=0&pageNo=%d"}
					{pages print=true assign=membershipPagesOutput link=$membershipMultiplePagesLink|concat:SID_ARG_2ND_NOT_ENCODED}
					<ul class="userGroupsList">
						{foreach from=$memberships item=membership}
							<li>
								<h4><a href="index.php?page=CliqueDetail&amp;cliqueID={@$membership.cliqueID}{@SID_ARG_2ND}">{$membership.name}</a></h4>

								<div class="smallFont">
									<p>{$membership.shortDescription}</p>
									<p>{lang}wcf.clique.general.status.{@$membership.status}{/lang}</p>
									{if $groupLeaders[$membership.raiserID]|isset}
										<p>
											{lang}wcf.clique.general.raiser{/lang}:
											<a href="index.php?page=User&amp;userID={@$membership.raiserID}{@SID_ARG_2ND}">{$membership.username}</a>
										</p>
									{/if}
								</div>

								<div class="smallButtons"><ul><li><a href="index.php?action=CliqueLeave&amp;cliqueID={@$membership.cliqueID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.clique.{if $membership.raiserID != $this->user->userID}general.leave{else}delete{/if}{/lang}"{if $membership.raiserID == $this->user->userID}onclick="return confirm('{lang}wcf.clique.delete.sure{/lang}')"{/if}><img src="{icon}deleteS.png{/icon}" alt="" /> <span>{lang}wcf.clique.{if $membership.raiserID != $this->user->userID}general.leave{else}delete{/if}{/lang}</span></a></li></ul></div>
							</li>
						{/foreach}
					</ul>
				</fieldset>
			{/if}

			{if $applications|count > 0}
				<fieldset>
					<legend>{lang}wcf.clique.detail.applications{/lang}</legend>
					<ul class="userGroupsList">
						{foreach from=$applications item=application}
							<li>
								<h4><a href="index.php?page=CliqueDetail&amp;cliqueID={@$application.cliqueID}{@SID_ARG_2ND}">{$application.name}</a></h4>

								<div class="smallFont">
									<p>{$application.message}</p>
								</div>

								<div class="smallButtons"><ul><li><a href="index.php?action=CliqueApplicationCancel&amp;cliqueID={@$application.cliqueID}&amp;userID={@$this->user->userID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.clique.detail.applications.cancel{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /> <span>{lang}wcf.clique.detail.applications.cancel{/lang}</span></a></li></ul></div>
							</li>
						{/foreach}
					</ul>
				</fieldset>
			{/if}

			{if $invites2|count > 0}
				<fieldset>
					<legend>{lang}wcf.clique.invite.text.{if $invites2|count == 1}singulary{else}plural{/if}{/lang}</legend>
					<ul class="userGroupsList">
            			{foreach from=$invites2 item=invite2}
            				<li>
            				    <h4>{lang}wcf.clique.invite.text.detail2{/lang}
            						<a href="index.php?action=CliqueInvite&amp;accept=1&amp;cliqueID={$invite2.cliqueID}&amp;t={@SECURITY_TOKEN}" class="deleteButton" title="{lang}wcf.clique.invite.accept{/lang}"><img src="{icon}checkS.png{/icon}" alt="{lang}wcf.clique.invite.accept{/lang}" longdesc="" /></a>
            						<a href="index.php?action=CliqueInvite&amp;decline=1&amp;cliqueID={$invite2.cliqueID}&amp;t={@SECURITY_TOKEN}" class="deleteButton" title="{lang}wcf.clique.invite.decline{/lang}"><img src="{icon}deleteS.png{/icon}" alt="{lang}wcf.clique.invite.decline{/lang}" longdesc="" /></a>
                                </h4>
            				</li>
            			{/foreach}
					</ul>
				</fieldset>
			{/if}

		</div>
	</div>

</div>

{include file='footer' sandbox=false}
</body>
</html>