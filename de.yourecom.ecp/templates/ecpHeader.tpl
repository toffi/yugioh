<div id="mainMenu2" class="mainMenu">
	 <div class="mainMenuInner">
		<ul>
            {foreach from=$headerMenuItems item=menuItems}{if !$menuItems.eventID|empty}<li class="first"><a href="index.php?page=ECPRound&amp;eventID={$menuItems.eventID}&amp;akt={$menuItems.currentGameday}{@SID_ARG_2ND}"><img src="{RELATIVE_WBB_DIR}icon/oflM.png" alt="" /><span>{$menuItems.nameAbbreviation}</span></a></li>{/if}{/foreach}<li><a href="index.php?page=ECPBeendet{@SID_ARG_2ND}"><img src="{icon}ecpend.png{/icon}" alt="" /><span>{lang}wcf.ecp.beendet.name{/lang}</span></a></li><li><a href="index.php?page=TotalRanking{@SID_ARG_2ND}"><img src="{RELATIVE_WBB_DIR}icon/totalRankingM.png" alt="" /><span>{lang}wcf.ecp.totalRanking{/lang}</span></a></li>
		</ul>
    </div>
</div>