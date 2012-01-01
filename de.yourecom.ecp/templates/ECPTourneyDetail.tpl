{include file="documentHeader"}
<head>
	<title>{PAGE_TITLE}</title>
	{include file="headInclude"}

	<link rel="stylesheet" type="text/css" media="screen" href="./style/ecp.css" />
</head>
<body>
{include file="header" sandbox=false}
{include file='header_ecp' sandbox=false}

<div id="main">
	<ul class="breadCrumbs">
		<li>
			<a href="index.php?page=Index{@SID_ARG_2ND}"><img src="icon/indexS.png" alt="" /> <span>{PAGE_TITLE}</span></a> &raquo;
			<a href="index.php?page=ECPNews{@SID_ARG_2ND}"><img src="{icon}ecpS.png{/icon}" alt="" /> <span>{lang}wcf.ecp.title.short{/lang}</span></a> &raquo;
			<a href="index.php?page=ECPTourney{@SID_ARG_2ND}"><img src="{icon}DDiskGoldS.png{/icon}" alt="" /> <span>{lang}wcf.ecp.tourney.tourney{/lang}</span></a> &raquo;
		</li>
	</ul>

	<div class="mainHeadline">
		<img src="{icon}DDiskM.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{@$tourneyList.name}</h2>
		</div>
	</div>
	<!-- PN Abfrage -->
	{if $userMessages|isset}{@$userMessages}{/if}
<!-- Turnier Verwaltung -->
	{if $tourneyList.status != 10 && $tourneyList.status != 5}
		<div class="smallButtons">
			<ul>
				{if $this->user->getPermission('user.ecp.canJoinTourney')}
						{* Abmelden *}
					{if $Joiner.user_ID != 0}
						<li>
							<a id="threadLink0" href="index.php?action=EcpTourneyLogout&amp;eventID={$eventID}{@SID_ARG_2ND}">
								<img src="{icon}logoutS.png{/icon}" alt="" /> <span>{lang}wcf.ecp.tourney.logout{/lang}</span>
							</a>
						</li>
							{* Mache Bestätigung *}
						{if $tourneyList.status == 2 && $Joiner.status != 1}
							<li>
								<a id="threadLink0" href="index.php?action=EcpTourneyMakeCertification&amp;eventID={$eventID}{@SID_ARG_2ND}">
									<img src="{icon}logoutS.png{/icon}" alt="" />
									<span>{lang}wcf.ecp.tourney.detail.certification.do{/lang}</span>
								</a>
							</li>
						{/if}
						{* Anmelden *}
					{elseif $tourneyList.status <= 2}
						<li>
							<a id="threadLink0" href="index.php?action=EcpTourneyJoin&amp;eventID={$eventID}{@SID_ARG_2ND}">
								<img src="{icon}loginOptionsS.png{/icon}" alt="" />
								<span>{lang}{if $Number >= $tourneyList.participants}wcf.ecp.tourney.join.ersatz{else}wcf.ecp.tourney.join{/if}{/lang}</span>
							</a>
						</li>
					{/if}
				{/if}
				{if $this->user->userID == $tourneyList.contacts || $this->user->getPermission('mod.ecp.canEditEveryResult')}
					{* Tunier Editieren *}
					<li>
						<a ihref="index.php?form=EcpEditTourney&amp;eventID={$eventID}{@SID_ARG_2ND}">
							<img src="{icon}editS.png{/icon}" alt="" />
							<span>{lang}wcf.ecp.tourney.edit{/lang}</span>
						</a>
					</li>
						{* Tunier Abbrechen *}
					<li>
						<a href="index.php?action=EcpTourneyStatus&amp;eventID={$eventID}&amp;status=4{@SID_ARG_2ND}">
							<img src="{icon}deleteS.png{/icon}" alt="" />
							<span>{lang}wcf.ecp.tourney.detail.stop{/lang}</span>
						</a>
					</li>
						{* Tunier Löschen *}
                    {if $this->user->getPermission('mod.ecp.canDelEveryTourney')}
    					<li>
    						<a href="index.php?action=EcpTourneyDelete&amp;eventID={$eventID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}">
    							<img src="{icon}deleteS.png{/icon}" alt="" />
    							<span>{lang}wcf.ecp.tourney.delete.tourney{/lang}</span>
    						</a>
    					</li>
                    {/if}
				{if $tourneyList.status <= 3 && $Number >= 3}
					{if $end|empty}
						<li>
							<a id="threadLink0" href="index.php?action=EcpTourneyStatus&amp;eventID={$eventID}{@SID_ARG_2ND}">
								<img src="{icon}editS.png{/icon}" alt="" />
								<span>
									{if $tourneyList.status == 1}{lang}wcf.ecp.tourney.detail.certification{/lang}
									{elseif $tourneyList.status == 2}{lang}wcf.ecp.tourney.detail.start{/lang}
									{elseif $tourneyList.status == 3}{lang}wcf.ecp.tourney.detail.end{/lang}{/if}
								</span>
							</a>
						</li>
					{elseif $end == 1}
						<li>
							<a id="threadLink0" href="index.php?action=EcpTourneyStatus&amp;eventID={$eventID}{@SID_ARG_2ND}">
								<img src="{icon}editS.png{/icon}" alt="" />
								<span>
									{lang}wcf.ecp.tourney.detail.end{/lang}
								</span>
							</a>
						</li>
					{/if}
				{/if}
			{/if}
		</ul>
	</div>
{/if}
<!-- Turnier Übersicht -->
	<div class="border">
		{if $tourneyList|isset && $tourneyList|count}
			<table class="tableList">
				<thead>
					<tr class="tableHead">
						<td align="center">{lang}wcf.ecp.tourney.insert.tourney.day{/lang}</td>
						<td align="center">{lang}wcf.ecp.tourney.insert.tourney.art{/lang}</td>
						<td align="center">{lang}wcf.ecp.tourney.insert.tourney.lobby{/lang}</td>
						<td align="center">{lang}wcf.ecp.tourney.insert.tourney.participiants{/lang}</td>
						<td align="center">{lang}wcf.ecp.tourney.show.admin{/lang}</td>
					</tr>
				</thead>
				<tbody>
					<tr class="container-{cycle values="1,2"}">
						<td align="center">{@$tourneyList.time|time}</td>
						<td align="center">{lang}{$tourneyList.artName}{/lang}</td>
						<td align="center">{lang}{@$tourneyList.lobbyName}{/lang}</td>
						<td align="center">{$tourneyList.participants}</td>
						<td align="center"><a href="index.php?page=User&amp;userID={$tourneyList.contacts}{@SID_ARG_2ND}">{$tourneyList.contacts_name}</a></td>
					</tr>
				</tbody>
				<tbody>
					<tr class="tableHead">
						<td colspan="5" align="center">{lang}wcf.ecp.tourney.insert.tourney.description{/lang}</td>
					</tr>
				</tbody>
				<tbody>
					<tr class="container-{cycle values="1,2"}">
							<td colspan="5" align="center">{@$tourneyList.description}</td>
					</tr>
				</tbody>
			</table>

		<!-- Keine Ergebnisse? -->
		{else}
			<p align="center">{lang}wbb.portal.box.nocards{/lang}</p>
		{/if}

		<!-- Spieler Übersicht -->
		{if $userList|isset && $userList|count && $tourneyList.status <= 2}
			<div align="center">
				<table width="25%">
					<thead>
						<tr class="tableHead">
							<th align="center">{lang}wcf.ecp.tourney.detail.nr{/lang}</th>
							<th align="center">{lang}wcf.ecp.tourney.detail.name{/lang}</th>
							<th align="center">{lang}wcf.ecp.tourney.detail.certified{/lang}</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$userList item=value}
							<tr class="container-{cycle values="1,2"}">
								<td align="center">{counter assign=UserNo print=true}.</td>
								<td align="center">
									<a href="index.php?page=User&amp;userID={$value.user_ID}{@SID_ARG_2ND}">
										{assign var=encodedUsername value=$value.username|htmlspecialchars}
										{@$value.userOnlineMarking|sprintf:$encodedUsername}
									</a>
								</td>
							<td align="center">
								{if $value.status == 1}
									<img src="{icon}check.png{/icon}" width="16px" height="16px" alt="" />
								{else}-
								{/if}
							</td>
						</tr>
					{/foreach}
				</table>
			</div>
		{/if}
	</div>
</div>

		<!-- Paarungs Übersicht -->
{if $paarungenList|isset && $paarungenList|count && $tourneyList.status >= 3}
	<form method="post" action="index.php?form=ECPTourneyDetail&amp;eventID={$eventID}{@SID_ARG_2ND}">
		<div align="center">{$end}
			{if ($this->user->getPermission('mod.ecp.canEditEveryResult') || $this->user->userID == $tourneyList.contacts) && $end == 0}
				<div class="formSubmit">
					<input type="submit" name="send" value="{lang}wcf.global.button.submit{/lang}" />
				</div>
			{elseif $end == 0}
				{foreach from=$userList item=value2}
					{if $this->user->userID == $value2.user_ID}
						<div class="formSubmit">
							<input type="submit" name="send" value="{lang}wcf.global.button.submit{/lang}" />
						</div>
					{/if}
				{/foreach}
			{/if}
			<table border="0">
				{foreach from=$paarungenList item=value}
					{if !$tr|isset || $tr != $value.event_round}
						<thead>
							<tr class="tableHead">
								<td class="columnName" colspan="{$maxColspan}" style="text-align:center;">
									{if $paarungenList|count == 4 && $value.event_round == 2} 
										{lang}wcf.ecp.tourney.detail.place3{/lang}
									{elseif $paarungenList|count == 4 && $value.event_round == 3} 
										{lang}wcf.ecp.tourney.detail.final{/lang}
									{elseif $paarungenList|count == 8 && $value.event_round == 3} 
										{lang}wcf.ecp.tourney.detail.place3{/lang}
									{elseif $paarungenList|count == 8 && $value.event_round == 4} 
										{lang}wcf.ecp.tourney.detail.final{/lang}
									{elseif $paarungenList|count == 16 && $value.event_round == 4} 
										{lang}wcf.ecp.tourney.detail.place3{/lang}
									{elseif $paarungenList|count == 16 && $value.event_round == 5} 
										{lang}wcf.ecp.tourney.detail.final{/lang}	
									{elseif $paarungenList|count == 32 && $value.event_round == 5} 
										{lang}wcf.ecp.tourney.detail.place3{/lang}
									{elseif $paarungenList|count == 32 && $value.event_round == 6} 
										{lang}wcf.ecp.tourney.detail.final{/lang}	
									{elseif $paarungenList|count == 64 && $value.event_round == 6} 
										{lang}wcf.ecp.tourney.detail.place3{/lang}
									{elseif $paarungenList|count == 64 && $value.event_round == 7} 
										{lang}wcf.ecp.tourney.detail.final{/lang}	
								{else}
										{$value.event_round}. {lang}wcf.ecp.tourney.detail.round{/lang}
									{/if}
								</td>
							</tr>
						</thead>
						<tbody>
						<tr>
					{/if}
					<td colspan="{$value.colspan}">
						<div style="width:100pt; text-align:center; margin:auto">
						<div class="containerContent" align="center">
							<div class="containerHead">
								{if $value.userID1 != 0}
									<a href="index.php?page=User&amp;userID={$value.userID1}{@SID_ARG_2ND}" class="infobox2">
										<span> <b>{lang}wcf.ecp.tourney.detail.nick{/lang}</b><br />
										{if $value.ygoNick1|empty}---{else}{$value.ygoNick1}{/if}</span>
								{/if}
								
								{@$value.username1}{if $value.userID1 != 0}</a>{/if}
							</div>
							{if $value.scoreID_1 != 0 || $value.scoreID_2 != 0}
								{$value.scoreID_1}
							{elseif $value.userID1 != 0 && $value.userID2 != 0}
								{if $this->user->getPermission('mod.ecp.canEditEveryResult') || $this->user->userID == $tourneyList.contacts ||
									$this->user->userID == $value.userID1 || $this->user->userID == $value.userID2}
									<input type="text" class="inputText" maxlength="1" style="width: 20px;" name="erg1[]" value="" />
									<input type="hidden" name="erg0[]" value="{$value.id}" />
									<input type="hidden" name="user1[]" value="{$value.userID1}" />
									<input type="hidden" name="user2[]" value="{$value.userID2}" />
								{/if}
							{else} ---
							{/if}
							vs
							{if $value.scoreID_1 != 0 || $value.scoreID_2 != 0}
								{$value.scoreID_2}
								{if $this->user->getPermission('mod.ecp.canEditEveryResult') || $this->user->userID == $tourneyList.contacts}
									<a id="threadLink0" href="index.php?action=EcpEditResult&amp;eventID={$eventID}&amp;gameID={$value.id}{@SID_ARG_2ND}">
										<img src="{icon}editS.png{/icon}" title="{lang}wcf.ecp.tourney.detail.editResult{/lang}" alt="" />
									</a>
								{/if}
							{elseif $value.userID1 != 0 && $value.userID2 != 0}
								{if $this->user->getPermission('mod.ecp.canEditEveryResult') || $this->user->userID == $tourneyList.contacts ||
									$this->user->userID == $value.userID1 || $this->user->userID == $value.userID2}
									<input type="text" class="inputText" maxlength="1" style="width: 20px;" name="erg2[]" value="" />
								{/if}
							{else} ---
							{/if}
							<div class="containerHead">
								{if $value.userID2 != 0}
									<a href="index.php?page=User&amp;userID={$value.userID2}{@SID_ARG_2ND}" class="infobox2">
										<span> <b>{lang}wcf.ecp.tourney.detail.nick{/lang}</b><br />
										{if $value.ygoNick2|empty}---{else}{$value.ygoNick2}{/if}</span>
								{/if}
								{@$value.username2}{if $value.userID2 != 0}</a>{/if}
							</div>
						</div>
					</td>
					</td>
					{assign var=tr value=$value.event_round}
					{if $tr != $value.event_round}
						</tr>
						</tbody>
					{/if}
				{/foreach}
			</table>
		</div>
	</form>
{/if}

{include file="footer" sandbox=false}
</body>
</html>
