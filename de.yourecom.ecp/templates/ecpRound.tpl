{include file="documentHeader"}
<head>
	<title>{$gameday}. {lang}wcf.ecp.admin.end.gameday{/lang} - {$event->eventName} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file="headInclude"}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file="header" sandbox=false}
{include file='ecpHeader'}

<div id="main">
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
        <li><a href="index.php?page=ECPNews{@SID_ARG_2ND}"><img src="{RELATIVE_WBB_DIR}icon/ecpS.png" alt="" /> <span>{lang}wcf.ecp.title.short{/lang}</span></a> &raquo;</li>
        <li><a href="index.php?page=ECPRound&amp;eventID={$eventID}&amp;akt={$gameday}"><img src="{RELATIVE_WBB_DIR}icon/{if $event->art == 1}oflS{elseif $event->art == 2}teampicS{/if}.png" alt="" /> <span>{$event->eventName}</span></a> &raquo;</li>
	</ul>

	<div class="mainHeadline">
		<img src="{RELATIVE_WBB_DIR}icon/{if $event->art == 1}oflL{elseif $event->art == 2}teampicL{/if}.png" alt="" />
		<div class="headlineContainer">
			<h2>{$event->eventName} - {$gameday}. {lang}wcf.ecp.admin.end.gameday{/lang}</h2>
			<p>{lang}wcf.ecp.round.currentgameday.description{/lang}</p>
		</div>
	</div>

    {if $userMessages|isset}{@$userMessages}{/if}

    {* Spieltage *}
    <div style="width: 50%; margin: 0px auto;">
    	<div class="pageNavigation">
            <ul>
                {section name=gamedaySection loop=$last_gameday/2+1 step=1 start=1}
                    {if $gamedaySection == $gameday}<li class="active"><span>{$gamedaySection}</span></li>
                    {else}<li><a href="index.php?page=ECPRound&amp;eventID={$eventID}&amp;akt={$gamedaySection}{@SID_ARG_2ND}" title="{$gamedaySection}. {lang}wcf.ecp.admin.end.gameday{/lang}">{$gamedaySection}</a></li>
                    {/if}
                {/section}
            </ul>
    
            <ul>
                {section name=gamedaySection loop=$last_gameday+1 step=1 start=$last_gameday/2+1}
                    {if $gamedaySection == $gameday}<li class="active"><span>{$gamedaySection}</span></li>
                    {else}<li><a href="index.php?page=ECPRound&amp;eventID={$eventID}&amp;akt={$gamedaySection}{@SID_ARG_2ND}" title="{$gamedaySection}. {lang}wcf.ecp.admin.end.gameday{/lang}">{$gamedaySection}</a></li>
                    {/if}
                {/section}
            </ul>
        </div>
    </div>
    
        {* Workaround for $pairings|count*2*2-2 *}
    {append var=countPairings value=$pairings|count}
    {append var=lastGameday value=$countPairings*2*2-2}
    
    <div style="width: 50%; margin: 0px auto;">
    <div class="largeButtons">
        <ul>
            {if $this->user->getPermission('mod.ecp.canSeeECP')}
                <li><a href="index.php?form=EcpAdminDetail&amp;eventID={$eventID}&amp;akt={$gameday}" title="{lang}wcf.ecp.round.ergebedit{/lang}"><img src="{icon}editM.png{/icon}" alt="" /> <span>{lang}wcf.ecp.round.ergebedit{/lang}</span></a></li>
                {* Wenn Event noch nicht beendet wurde*}
                {if $event->status != 10}
                    <li><a href="index.php?action=EcpGameDayEnd&amp;eventID={$eventID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.ecp.admin.{if $gameday < $lastGameday}gamedayend{else}end.end{/if}.sure{/lang}')" title="{lang}wcf.ecp.admin.{if $gameday < $lastGameday}gamedayend{else}end.end{/if}{/lang}"><img src="{RELATIVE_WBB_DIR}icon/finishM.png" alt="" /> <span>{lang}wcf.ecp.admin.{if $gameday < $lastGameday}gamedayend{else}end.end{/if}{/lang}</span></a></li>
                    <li><a href="index.php?action=EcpMatchplaner&amp;eventID={$eventID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.acp.option.category.ecp.matchplaner{/lang}"><img src="{RELATIVE_WBB_DIR}icon/threadM.png" alt="" /> <span>{lang}wcf.acp.option.category.ecp.matchplaner{/lang}</span></a></li>
                {/if}
            {/if}
            <li><a href="index.php?page=User&amp;userID={$contact.userID}{@SID_ARG_2ND}" title="{lang username=$contact.username}wcf.user.viewProfile{/lang}"><img src="{RELATIVE_WBB_DIR}icon/moderatorM.png" alt="" /> <span>{lang}wcf.ecp.acp.eventbetreuer.short{/lang}</span></a></li>
            <li><a href="{$event->rules}" title="{lang}wcf.ecp.round.rules{/lang}"><img src="{icon}rulesM.png{/icon}" alt="" /> <span>{lang}wcf.ecp.round.rules{/lang}</span></a></li>
        </ul>
   	</div>
    </div>
    
    <div class="border" style="width: 50%; margin: 0px auto;">
        <table class="tableList">
            <thead>
                <tr class="tableHead">
                    <th><span class="emptyHead" style="text-align:left;">{lang}wcf.ecp.round.home{/lang}</span></th>
                    <th><span class="emptyHead" style="text-align:left;"></span></th>
                    <th><span class="emptyHead" style="text-align:left;">{lang}wcf.ecp.round.guest{/lang}</span></th>
                    <th><span class="emptyHead" style="text-align:left;"></span></th>
                    <th><span class="emptyHead" style="text-align:left;">{lang}wcf.acp.option.category.ecp.matchplaner{/lang}</span></th>
                </tr>
            </thead>
            
            <tbody>
    			{foreach from=$pairings item=paarungen}
                    <tr class="container-{cycle values='1,2'}">
                    	<td>{if $paarungen.userID1 != 0}<a href="index.php?page=User&amp;userID={$paarungen.userID1}{@SID_ARG_2ND}" title="{lang username=$paarungen.gamer1}wcf.user.viewProfile{/lang}">{@$paarungen.gamer1}</a>{else}{lang}wcf.ecp.round.freilos{/lang}{/if}</td>
                    	<td>:</td>
                    	<td>{if $paarungen.userID2 != 0}<a href="index.php?page=User&amp;userID={$paarungen.userID2}{@SID_ARG_2ND}" title="{lang username=$paarungen.gamer2}wcf.user.viewProfile{/lang}">{@$paarungen.gamer2}</a>{else}{lang}wcf.ecp.round.freilos{/lang}{/if}</td>
                        <td>{$paarungen.scoreID1}:{$paarungen.scoreID2}</td>
                        <td>{if !$paarungen.matchPlanerID|empty}<a href="index.php?page=Thread&amp;threadID={$paarungen.matchPlanerID}&amp;action=firstNew"><img class="goToNewPost" src="{RELATIVE_WBB_DIR}icon/goToFirstNewPostS.png" alt="" title="{lang}wbb.index.gotoFirstNewPost{/lang}" /></a>{/if}</td>
                    </tr>
    			{/foreach}
			</tbody>
        </table>
    </div>        
    <div class="border" style="width: 50%; margin: 0px auto; margin-top: 20px;">
        <table class="tableList">
            <thead>
                <tr class="tableHead">
                    <th><span class="emptyHead" style="text-align:left;"></span></th>
                    <th><span class="emptyHead" style="text-align:left;">{lang}wcf.ecp.round.name{/lang}</span></th>
                    <th><span class="emptyHead" style="text-align:left;">{lang}wcf.ecp.round.games{/lang}</span></th>
                    <th><span class="emptyHead" style="text-align:left;">{lang}wcf.ecp.round.saetze{/lang}</span></th>
                    <th><span class="emptyHead" style="text-align:left;">{lang}wcf.ecp.round.points{/lang}</span></th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$tabelle item=tabelle3}
                    {counter assign=boardNo print=false name=platzierung}
                    <tr class="container-{cycle values='1,2'}">
                        <td>{$boardNo}.</td>
                        <td><a href="index.php?page=User&amp;userID={$tabelle3.userID}{@SID_ARG_2ND}" title="{lang username=$tabelle3.username}wcf.user.viewProfile{/lang}">{@$tabelle3.username}</a> {if $tabelle3.lastChange + PROFILE_SHOW_OLD_USERNAME * 86400 > TIME_NOW}(ehemals &raquo;{$tabelle3.odUsername}&laquo;){/if}</td>
                        <td>{$tabelle3.games}</td>
                        <td>{$tabelle3.wins}:{$tabelle3.loos}</td>
                        <td>{$tabelle3.points}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>		

</div>
{include file="footer" sandbox=false}

</body>
</html>
