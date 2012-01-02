{include file="documentHeader"}
<head>
	<title>{lang}wcf.clique.membership{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='header' sandbox=false}

<div id="main">
	
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
		<li><a href="index.php?page=CliqueOverview{@SID_ARG_2ND}"><img src="{icon}cliqueS.png{/icon}" alt="" width="16" height="16" /> <span>{lang}wcf.clique.general.name.singulary{/lang}</span></a> &raquo;</li>
	</ul>
	
	{if $userMessages|isset}{@$userMessages}{/if}
    
    {include file="cliqueDetailHeader" showRaiting=true}
	
	<div class="border tabMenuContent">
		<div class="container-1">
			{if $members|count > 0}
                {foreach from=$members item=member}
					<div class="contentBox">
						<h3 class="subHeadline">{lang}{$member.groupName}{/lang}</h3>
						<div class="border">
							<table class="tableList membersList">
								<thead>
									<tr class="tableHead">
										<th class="column{if $sortField == 'username'} active{/if}"><div><a href="index.php?page=CliqueMembers&amp;cliqueID={$cliqueID}&amp;sortField=username&amp;sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@SID_ARG_2ND}">{lang}wcf.clique.administrate.changeRaiser.username{/lang}{if $sortField == 'username'} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}</a></div></th>
										<th class="column{if $sortField == 'avatarName'} active{/if}"><div><a href="index.php?page=CliqueMembers&amp;cliqueID={$cliqueID}&amp;sortField=avatarName&amp;sortOrder={if $sortField == 'avatarName' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@SID_ARG_2ND}">{lang}wcf.user.avatar{/lang}{if $sortField == 'avatarName'} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}</a></div></th>
										<th class="column{if $sortField == 'posts'} active{/if}"><div><a href="index.php?page=CliqueMembers&amp;cliqueID={$cliqueID}&amp;sortField=posts&amp;sortOrder={if $sortField == 'posts' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@SID_ARG_2ND}">{lang}wcf.user.posts{/lang}{if $sortField == 'posts'} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}</a></div></th>
										<th class="column{if $sortField == 'enteredTime'} active{/if}"><div><a href="index.php?page=CliqueMembers&amp;cliqueID={$cliqueID}&amp;sortField=enteredTime&amp;sortOrder={if $sortField == 'enteredTime' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@SID_ARG_2ND}">{lang}wcf.clique.membership.since{/lang}{if $sortField == 'enteredTime'} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}</a></div></th>
									</tr>
								</thead>
								<tbody>
								{foreach from=$member.user item=blupp}
									<tr class="container-{cycle values='1,2'}">
											<td class="column">{@$blupp.username}</td>
                                            <td class="column">{@$blupp.avatar}</td>
                                            <td class="column">{@$blupp.posts}</td>
                                            <td class="column">{@$blupp.enteredTime}</td>
									</tr>
								{/foreach}
								</tbody>
							</table>
						</div>
					</div>
                {/foreach}
			{/if}
		</div>
	</div>
</div>

{include file='footer' sandbox=false}
</body>
</html>