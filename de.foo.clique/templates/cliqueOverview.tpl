{include file="documentHeader"}
<head>
	<title>{lang}wcf.clique.overview.list{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
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

	<div class="tabMenu" style="width: 79%;">
		<ul>
			<li class="activeTabMenu"><a href="index.php?page=CliqueOverview{@SID_ARG_2ND}"><img src="{icon}cliqueM.png{/icon}" alt="" /> <span>{lang}wcf.clique.overview.list{/lang}</span></a></li>
			{if $cliqueCategories|count > 0}<li><a href="index.php?page=CliqueCategories{@SID_ARG_2ND}"><img src="{icon}cliqueM.png{/icon}" alt="" /> <span>{lang}wcf.clique.categorie{/lang}</span></a></li>{/if}
	         {if $this->user->getPermission('user.clique.general.canRaise') && $this->user->userID != 0}<li style="float: right;"><a href="index.php?form=CliqueAdd{@SID_ARG_2ND}"><img src="{icon}addM.png{/icon}" alt="" /><span>{lang}wcf.clique.raiser.rais{/lang}</span></a></li>{/if}
		</ul>
	</div>
    <div style="float: left !important; width: 79%;">
    	<div class="subTabMenu">
    		<div class="containerHead">
    			<ul>
    				<li{if $letter|empty} class="activeSubTabMenu"{/if}><a href="index.php?page=CliqueOverview{@SID_ARG_2ND}"><span>{lang}wcf.clique.overview.all{/lang}</span></a></li>
    				{foreach from=$letters item=letterItem}
    					<li{if $letterItem|rawurlencode == $letter} class="activeSubTabMenu"{/if}><a href="index.php?page=CliqueOverview&amp;letter={@$letterItem|rawurlencode}{@SID_ARG_2ND}"><span>{$letterItem}</span></a></li>
    				{/foreach}
    			</ul>
    		</div>
    	</div>
    
    	<div class="border tabMenuContent">
    		{if $cliquen|count > 0}
    			<table class="tableList membersList">
    				<thead>
    					<tr class="tableHead">
    						{foreach from=$columns item=column}
    							<th{if $sortField == $column} class="active"{/if}>
    								<a href="index.php?page=CliqueOverview&amp;pageNo={@$pageNo}&amp;sortField={$column}&amp;sortOrder={if $sortField == $column && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@SID_ARG_2ND}">
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
    									<a href="index.php?page=CliqueDetail&amp;cliqueID={$clique.cliqueID}{@SID_ARG_2ND}">{if $column == 'image'}<img alt="" src="{if !$clique.image|empty}{RELATIVE_WCF_DIR}images/clique/{$clique.image}{else}{icon}noCliquePicL.png{/icon}{/if}" width="{if !$clique.image|empty}{$clique.width}{else}50{/if}" height="{if !$clique.image|empty}{$clique.height}{else}50{/if}" />{elseif $column == 'name'}{$clique.name}{elseif $column == 'countMemberships'}{#$clique.countMemberships}{elseif $column == 'time'}{$clique.time|date}{elseif $column == 'status'}{lang}wcf.clique.general.status.{@$clique.status}{/lang}{elseif $column == 'shortDescription'}{$clique.shortDescription}{elseif $column == 'description'}{@$clique.description}{elseif $column == 'rating'}{$clique.rating}{/if}</a>
    								    {if $column == 'status' && $clique.groupType < 1 && $this->user->userID != 0}<br />
                                            {if $clique.status == 0}<a href="index.php?action=CliqueJoin&amp;cliqueID={@$clique.cliqueID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.clique.general.enter{/lang}"><img src="{icon}addS.png{/icon}" alt="{lang}wcf.clique.general.enter{/lang}" width="16" height="16" /></a>
                                            {else}<a href="index.php?page=CliqueDetail&amp;cliqueID={@$clique.cliqueID}&amp;application=1&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.clique.detail.application{/lang}"><img src="{icon}applicationS.png{/icon}" alt="{lang}wcf.clique.detail.application{/lang}" width="16" height="16" /></a>{/if}
                                        {/if}
                                    </td>
    							{/foreach}
    						</tr>
    					{/foreach}
    				</tbody>
    			</table>
    		{else}
    			<div class="container-1">
    				{lang}wcf.clique.overview.noClique{/lang}
    			</div>
    		{/if}
    	</div>
    </div>

    <div class="layout-1" style="width: 20%; float:right;">
        <div class="columnContainer" style="border: 0pt none;">
            <div class="column">
                <div class="contentBox linkListBox" style="padding: 0pt;">
                    <div class="border titleBarPanel">
                        <div class="containerHead">
                            <h4>{lang}wcf.clique.overview.newest{/lang}</h4>
                        </div>
                        <ul class="dataList">
                            {foreach from=$newestCliques item=newestClique}
                                <li class="container-1">
                                    <div class="containerIcon">
                                        <img src="{icon}cliqueM.png{/icon}" alt="" />
                                    </div>
                                    <div class="containerContent">
                                        <h4><a href="index.php?page=CliqueDetail&amp;cliqueID={$newestClique.cliqueID}">{$newestClique.name}</a></h4>
                                        <p class="smallFont light">{lang}wcf.clique.general.raiser{/lang} <a href="index.php?page=User&amp;userID={$newestClique.raiserID}"><span>{$newestClique.username}</span></a> ({@$newestClique.time|time})</p>
                                    </div>
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

	{pages print=true link="index.php?page=CliqueOverview&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&letter=$letter"|concat:SID_ARG_2ND_NOT_ENCODED}

</div>

{include file='footer' sandbox=false}
</body>
</html>