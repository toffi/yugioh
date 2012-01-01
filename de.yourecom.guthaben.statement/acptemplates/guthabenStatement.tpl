{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/guthabenLogL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.guthaben.statement{/lang}</h2>
	</div>
</div>

{if $deletedLanguageID|isset}
	<p class="success">{lang}wcf.acp.language.delete.success{/lang}</p>	
{/if}

{if $deletedVariable|isset}
	<p class="success">{lang}wcf.acp.language.variable.delete.success{/lang}</p>	
{/if}

<div class="contentHeader">
	{pages print=true assign=pagesLinks link="index.php?page=GuthabenStatement&userID=$userID&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}
</div>

{if $statements|count}
	<div class="border titleBarPanel">
		{* <div class="containerHead"><h3>{lang}wcf.acp.language.view.count{/lang} </h3></div> *}
	</div>
	<div class="border borderMarginRemove">
		<table class="tableList">
			<thead>
				<tr class="tableHead">
					<th colspan="2"{if $sortField == 'logID'} class="active"{/if}><div><a href="index.php?page=GuthabenStatement&amp;userID={$userID}&amp;pageNo={@$pageNo}&amp;sortField=logID&amp;sortOrder={if $sortField == 'logID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.guthaben.log.id{/lang}{if $sortField == 'logID'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th{if $sortField == 'time'} class="active"{/if}><div><a href="index.php?page=GuthabenStatement&amp;userID={$userID}&amp;pageNo={@$pageNo}&amp;sortField=time&amp;sortOrder={if $sortField == 'time' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.guthaben.log.time{/lang}{if $sortField == 'time'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th{if $sortField == 'langvar'} class="active"{/if}><div><a href="index.php?page=GuthabenStatement&amp;userID={$userID}&amp;pageNo={@$pageNo}&amp;sortField=langvar&amp;sortOrder={if $sortField == 'langvar' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.guthaben.log.desc{/lang}{if $sortField == 'langvar'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th{if $sortField == 'guthaben'} class="active"{/if}><div><a href="index.php?page=GuthabenStatement&amp;userID={$userID}&amp;pageNo={@$pageNo}&amp;sortField=guthaben&amp;sortOrder={if $sortField == 'guthaben' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.guthaben.log.change{/lang}{if $sortField == 'guthaben'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$statements item=statement}
				<tr class="{cycle values="container-1,container-2"}">
				    <td class="columnIcon">
                        {if $statement.langvar != 'wcf.guthaben.log.compress'}
                            <a href="index.php?action=GuthabenStatementDelete&amp;userID={$userID}&amp;logID={$statement.logID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.guthaben.statement.delete{/lang}" onclick="return confirm('{lang}wcf.guthaben.statement.delete.sure{/lang}')"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" /></a>
                            <a href="index.php?action=GuthabenStatementRemove&amp;userID={$userID}&amp;logID={$statement.logID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.guthaben.statement.remove{/lang}" onclick="return confirm('{lang}wcf.guthaben.statement.remove.sure{/lang}')"><img src="{@RELATIVE_WCF_DIR}icon/removeS.png" alt="" /></a>
                        {else}-{/if}
                    </td>
                    <td class="columnID">{#$statement.logID}</td>
                    <td class="columnText">{@$statement.time|time}</td>
					<td class="columnText">{lang}{$statement.langvar}{/lang} {if $statement.link}<a href="../{$statement.link}">{/if}{$statement.text}{if $statement.link}</a>{/if}</td>
                    <td class="columnNumbers">{$statement.guthaben}</td>
				</tr>
			{/foreach}
                <tr class="container-2">
					<td colspan="4" class="columnText">{lang}wcf.guthaben.log.allsum{/lang}</td>
					<td class="columnNumbers"><strong>{#$money} {lang}wcf.guthaben.currency{/lang}</strong></td>
				</tr>
			</tbody>
		</table>
	</div>
    <a href="index.php?action=GuthabenStatementCompress&amp;userID={$userID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.guthaben.log.compress{/lang}</a>
	<div class="contentFooter">
		{@$pagesLinks}
	</div>
{/if}

{include file='footer'}