{include file="documentHeader"}
<head>
	<title>{lang}wcf.user.userGroups.leader.title{/lang} - {lang}wcf.user.usercp{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='header' sandbox=false}

<div id="main">
	
	{include file="userCPHeader"}
	
	<div class="border tabMenuContent">
		<div class="container-1">
			<h3 class="subHeadline">{lang}wcf.clique.raiser{/lang}</h3>

			<fieldset>
				<legend>{lang}wcf.clique.raiser.leader{/lang}</legend>

				{if $openCliquen|count > 0}
					<ul class="userGroupsList">
						{foreach from=$openCliquen item=openClique}
							<li>
								<h4><a href="index.php?form=CliqueEdit&amp;cliqueID={$openClique.cliqueID}{@SID_ARG_2ND}" title="{lang}wcf.clique.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="{lang}wcf.clique.edit{/lang}" /></a><a href="index.php?form=CliqueAdministrate&amp;cliqueID={$openClique.cliqueID}{@SID_ARG_2ND}" title="{lang}wcf.clique.administrate{/lang}"><img src="{icon}cogS.png{/icon}" alt="{lang}wcf.clique.administrate{/lang}" /></a><a href="index.php?action=CliqueDelete&amp;cliqueID={$openClique.cliqueID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.clique.delete{/lang}" onclick="return confirm('{lang}wcf.clique.delete.sure{/lang}')"><img src="{icon}deleteS.png{/icon}" alt="" /></a> <span>{$openClique.name} ({#$openClique.countMemberships})</span></h4>
								<div class="smallFont">
									<p>{$openClique.shortDescription}</p>
									<p>{lang}wcf.clique.general.status.{@$openClique.status}{/lang}</p>
								</div>
							</li>
						{/foreach}
					</ul>
				{else}
					{lang}wcf.clique.raiser.leader.noresult{/lang}
				{/if}
			</fieldset>
		</div>
	</div>

</div>

{include file='footer' sandbox=false}
</body>
</html>