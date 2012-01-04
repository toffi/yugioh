{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/smileyCategoryL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.clique.categorie{/lang}</h2>
	</div>
</div>

{if !$deletedCliqueCategory|empty}
	<p class="success">{lang}wcf.acp.clique.categorie.delete.success{/lang}</p>
{/if}

<div class="contentHeader">
	{pages print=true assign=pagesLinks link="index.php?page=CliqueCategoryList&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}
	
	<div class="largeButtons">
		<ul><li><a href="index.php?form=CliqueCategoryAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/smileyCategoryAddM.png" alt="" title="{lang}wcf.acp.clique.categorie.add{/lang}" /> <span>{lang}wcf.acp.clique.categorie.add{/lang}</span></a></li></ul>
	</div>
</div>

{if $categoryList|count}
	<div class="border titleBarPanel">
		<div class="containerHead"><h3>{lang}Insgesamt {#$countCategories} Cliquen-Kategorie{if $items != 1}n{/if}{/lang}</h3></div>
	</div>
	<div class="border borderMarginRemove">
		<table class="tableList">
			<thead>
				<tr class="tableHead">
					<th class="columnSmileyCategoryID{if $sortField == 'categoryID'} active{/if}" colspan="2"><div><a href="index.php?page=CliqueCategoryList&amp;pageNo={@$pageNo}&amp;sortField=categoryID&amp;sortOrder={if $sortField == 'categoryID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}ID{/lang}{if $sortField == 'categoryID'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnSmileyCategoryTitle{if $sortField == 'category'} active{/if}"><div><a href="index.php?page=CliqueCategoryList&amp;pageNo={@$pageNo}&amp;sortField=category&amp;sortOrder={if $sortField == 'category' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.clique.categorie{/lang}{if $sortField == 'category'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnShowOrder{if $sortField == 'showOrder'} active{/if}"><div><a href="index.php?page=CliqueCategoryList&amp;pageNo={@$pageNo}&amp;sortField=showOrder&amp;sortOrder={if $sortField == 'showOrder' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.clique.categorie.showorder{/lang}{if $sortField == 'showOrder'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$categoryList item=list}
				<tr class="{cycle values="container-1,container-2"}">
					<td class="columnIcon">
						{if $list.disabled}
							<a href="index.php?action=CliqueCategoryEnable&amp;categoryID={@$list.categoryID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/disabledS.png" alt="" title="{lang}wcf.acp.clique.categorie.activate{/lang}" /></a>
						{else}
							<a href="index.php?action=CliqueCategoryDisable&amp;categoryID={@$list.categoryID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/enabledS.png" alt="" title="{lang}wcf.acp.clique.categorie.deactivate{/lang}" /></a>
						{/if}
						<a href="index.php?form=CliqueCategoryEdit&amp;categoryID={@$list.categoryID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" title="{lang}wcf.acp.clique.categorie.edit{/lang}" /></a>
						<a onclick="return confirm('{lang}wcf.acp.clique.categorie.delete.sure{/lang}')" href="index.php?action=CliqueCategoryDelete&amp;categoryID={@$list.categoryID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" title="{lang}wcf.acp.clique.categorie.delete{/lang}" /></a>
					</td>
					<td class="columnSmileyCategoryID columnID">{@$list.categoryID}</td>
					<td class="columnSmileyCategoryTitle columnText">
						<a href="index.php?form=CliqueCategoryEdit&amp;categoryID={@$list.categoryID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.clique.categorie.edit{/lang}">{lang}{$list.category}{/lang}</a>
					</td>
					<td class="columnShowOrder columnNumbers">{#$list.showOrder}</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>

	<div class="contentFooter">
		{@$pagesLinks}
		
		<div class="largeButtons">
		<ul><li><a href="index.php?form=CliqueCategoryAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/smileyCategoryAddM.png" alt="" title="{lang}wcf.acp.clique.categorie.add{/lang}" /> <span>{lang}wcf.acp.clique.categorie.add{/lang}</span></a></li></ul>
		</div>
	</div>
{else}
	<div class="border content">
		<div class="container-1">
			<p>{lang}wcf.acp.clique.categorie.noresult{/lang}</p>
		</div>
	</div>
{/if}

{include file='footer'}
