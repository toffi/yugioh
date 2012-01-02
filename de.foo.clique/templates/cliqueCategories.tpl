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
			<p>{lang}wcf.clique.categorie.description{/lang}</p>
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
		<div class="container-1">
			<h3 class="subHeadline">{lang}wcf.clique.categorie{/lang}</h3>

			{if $cliqueCategories|count > 0}
				{foreach from=$cliqueCategories item=cliqueCategorie key=$key}
					{if $key%2 == 0}
						{capture append="left"}
							<li style="margin: 5px">
								<h3 class="boardTitle"><a href="index.php?page=CliquenOverviewCategory&amp;categoryID={$cliqueCategorie.categoryID}{@SID_ARG_2ND}">{$cliqueCategorie.category}</a> ({#$cliqueCategorie.countCliquen})</h3>
							</li>
						{/capture}
					{else}
						{capture append="right"}
							<li style="margin: 5px">
								<h3 class="boardTitle"><a href="index.php?page=CliquenOverviewCategory&amp;categoryID={$cliqueCategorie.categoryID}{@SID_ARG_2ND}">{$cliqueCategorie.category}</a> ({#$cliqueCategorie.countCliquen})</h3>
							</li>
						{/capture}
					{/if}
				{/foreach}

				<div style="float: left; width: 50%">
					<ul>
						{@$left}
					</ul>
				</div>
				<div style="float: right; width: 50%">
						{if !$right|empty}
                            <ul>
                                {@$right}
                            </ul>
                        {/if}
					</div>
				<div style="clear: both"></div>

				<div class="buttonBar">
					<div class="smallButtons">
						<ul>
							<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
						</ul>
					</div>
				</div>
			{else}
				<div class="container-1">
					{lang}wcf.clique.overview.noClique{/lang}
				</div>
			{/if}
		</div>
	</div>


	<div class="largeButtons">
		{if $this->user->getPermission('user.clique.general.canRaise') && $this->user->userID != 0}<ul><li><a href="index.php?form=CliqueAdd{@SID_ARG_2ND}"><img src="{icon}addM.png{/icon}" alt="" /><span>{lang}wcf.clique.raiser.rais{/lang}</span></a></li></ul>{/if}
	</div>


</div>

{include file='footer' sandbox=false}
</body>
</html>