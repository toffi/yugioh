{include file="documentHeader"}
<head>
	<title>{lang}wcf.clique.categorie{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}

	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
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
	
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<img src="{icon}cliqueL.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.clique.general.name.plural{/lang}</h2>
			<p>{lang}wcf.clique.overview.description{/lang}</p>
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}

	<div class="largeButtons">
		{if $this->user->getPermission('user.clique.general.canRaise') && $this->user->userID != 0}<ul><li><a href="index.php?form=CliqueAdd{@SID_ARG_2ND}"><img src="{icon}addM.png{/icon}" alt="" /><span>{lang}wcf.clique.raiser.rais{/lang}</span></a></li></ul>{/if}
	</div>
 
	<div class="tabMenu">
		<ul>
			<li><a href="index.php?page=CliqueOverview{@SID_ARG_2ND}"><img src="{icon}cliqueM.png{/icon}" alt="" /> <span>{lang}wcf.clique.overview.list{/lang}</span></a></li>
			<li class="activeTabMenu"><a href="index.php?page=CliqueCategories{@SID_ARG_2ND}"><img src="{icon}cliqueM.png{/icon}" alt="" /> <span>{lang}wcf.clique.categorie{/lang}</span></a></li>
		</ul>
	</div>
	<div class="subTabMenu">
		<div class="containerHead">
		</div>
	</div>

	<div class="border tabMenuContent">
		{if $cliquen|count > 0}
			<table class="tableList membersList">
				<thead>
					<tr class="tableHead">
						{foreach from=$columns item=column}
							<th class="column{if $sortField == $column} active{/if}">
								<a href="index.php?page=CliquenOverviewCategory&amp;categoryID={$categoryID}&amp;pageNo={@$pageNo}&amp;sortField={$column}&amp;sortOrder={if $sortField == $column && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@SID_ARG_2ND}">
									<span class="emptyHead">
										{if $column == 'image'}{lang}wcf.clique.overview.picture{/lang}{/if}
										{if $column == 'name'}{lang}wcf.clique.add.name{/lang}{/if}
										{if $column == 'countMemberships'}{lang}wcf.clique.overview.picture{/lang}{/if}
										{if $column == 'time'}{lang}wcf.clique.general.creationDate{/lang}{/if}
										{if $column == 'status'}{lang}wcf.clique.add.status{/lang}{/if}
										{if $column == 'shortDescription'}{lang}wcf.clique.overview.shortDescription{/lang}{/if}
										{if $column == 'description'}{lang}wcf.clique.add.description{/lang}{/if}
										{if $column == 'rating'}{lang}wcf.clique.overview.raiting{/lang}{/if}
										{if $sortField == $column} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}
									</span>
								</a>
							</th>
						{/foreach}
					</tr>
				</thead>
				<tbody>
					{foreach from=$cliquen item=clique}
						<tr class="container-{cycle values='1,2'}">
							{foreach from=$columns item=column}
								<td class="column">
									<a href="index.php?page=CliqueDetail&amp;cliqueID={$clique.cliqueID}{@SID_ARG_2ND}">
										{if $column == 'image'}<img alt="" src="{if !$clique.image|empty}{RELATIVE_WCF_DIR}images/clique/{$clique.image}{else}{icon}noCliquePicL.png{/icon}{/if}" width="{if !$clique.image|empty}{$clique.width}{else}50{/if}" height="{if !$clique.image|empty}{$clique.height}{else}50{/if}" />{/if}
										{if $column == 'name'}{$clique.name}{/if}
										{if $column == 'countMemberships'}{#$clique.countMemberships}{/if}
										{if $column == 'time'}{$clique.time|date}{/if}
										{if $column == 'status'}{lang}wcf.clique.general.status.{@$clique.status}{/lang}{/if}
										{if $column == 'shortDescription'}{$clique.shortDescription}{/if}
										{if $column == 'description'}{@$clique.description}{/if}
										{if $column == 'rating'}{$clique.rating}{/if}
									</a>
								</td>
							{/foreach}
						</tr>
					{/foreach}
				</tbody>
			</table>
		{else}
			<div class="container-1">
				{lang}wcf.clique.categorie.noClique{/lang}
			</div>
		{/if}
	</div>

		<div class="largeButtons">
			{if $this->user->getPermission('user.clique.general.canRaise') && $this->user->userID != 0}<ul><li><a href="index.php?form=CliqueAdd{@SID_ARG_2ND}"><img src="{icon}addM.png{/icon}" alt="" /><span>{lang}wcf.clique.raiser.rais{/lang}</span></a></li></ul>{/if}
		</div>


</div>

{include file='footer' sandbox=false}
</body>
</html>