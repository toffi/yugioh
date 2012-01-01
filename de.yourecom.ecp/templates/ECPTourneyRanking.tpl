{include file="documentHeader"}
<head>
	<title>{PAGE_TITLE}</title>
	{include file="headInclude"}
	<link rel="stylesheet" type="text/css" media="screen" href="style/burningBoard.css" />
</head>
<body>
{include file="header" sandbox=false}
{include file='header_ecp' sandbox=false}
<div id="main">
	<ul class="breadCrumbs">
		<li>
			<a href="index.php?page=Index{@SID_ARG_2ND}"><img src="icon/indexS.png" alt="" /> <span>{PAGE_TITLE}</span></a> &raquo;
			<a href="index.php?page=ECPNews{@SID_ARG_2ND}"><img src="{icon}ecpS.png{/icon}" alt="" /> <span>{lang}wcf.ecp.title.short{/lang}</span></a> &raquo;
		</li>
	</ul>
	
	<div class="mainHeadline">
		<img src="{icon}DDiskM.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.ecp.tourney.show.tittle{/lang}</h2>
		</div>
	</div>
	 <!-- PN Abfrage -->
	{if $userMessages|isset}{@$userMessages}{/if}

		<div class="smallButtons">
			<ul>
				{if $this->user->getPermission('mod.ecp.canCreateTourney')}
					<li>
						<a href="index.php?form=EcpInsertTourney{@SID_ARG_2ND}">
							<img src="{icon}messageAddM.png{/icon}" alt="" />
							<span>{lang}wcf.ecp.tourney.insert.name{/lang}</span>
						</a>
					</li>
				{/if}
				<li>
					<a href="index.php?page=EcpAbbortTourney{@SID_ARG_2ND}">
						<img src="{icon}cancelS.png{/icon}" alt="" />
						<span>{lang}wcf.ecp.tourney.abbort{/lang}</span>
					</a>
				</li>
				<li>
					<a href="index.php?page=ECPTourneyRankingBF{@SID_ARG_2ND}">
						<img src="{icon}userProfileRankM.png{/icon}" alt="" />
						<span>{lang}wcf.ecp.tourney.ranking.BF{/lang}</span>
					</a>
				</li>
				<li>
					<a href="index.php?page=ECPTourneyRankingWDFT{@SID_ARG_2ND}">
						<img src="{icon}userProfileRankM.png{/icon}" alt="" />
						<span>{lang}wcf.ecp.tourney.ranking.WDFT{/lang}</span>
					</a>
				</li>
				<li>
					<a href="index.php?page=ECPTourneyRankingMNW{@SID_ARG_2ND}">
						<img src="{icon}userProfileRankM.png{/icon}" alt="" />
						<span>{lang}wcf.ecp.tourney.ranking.mnw{/lang}</span>
					</a>
				</li>
				<li>
					<a href="index.php?page=ECPTourneyRanking{@SID_ARG_2ND}">
						<img src="{icon}userProfileRankM.png{/icon}" alt="" />
						<span>{lang}wcf.ecp.tourney.ranking{/lang}</span>
					</a>
				</li>
			</ul>
		</div>
 <!-- Spieler Übersicht -->
	<div class="border">
		{if $userList|isset && $userList|count}
			<table class="tableList membersList">
				<thead>
					<tr class="tableHead">
						<th class="columnName">{lang}wcf.ecp.tourney.ranking.rank{/lang}</th>
						<th class="columnName">{lang}wcf.ecp.tourney.ranking.username{/lang}</th>
						<th class="columnName">{lang}wcf.ecp.tourney.ranking.points{/lang}</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$userList item=value}
						<tr class="container-{cycle values="1,2"}">
							<td align="center">{counter assign=place print=true}</td>
							<td align="center"><a href="index.php?page=User&amp;userID={$value.user_ID}{@SID_ARG_2ND}">{$value.username}</a></td>
							<td align="center">{$value.points}</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		{else}
		<!-- Keine Ergebnisse? -->
				<p style="text-align:center;">{lang}wcf.ecp.tourney.noTourney{/lang}</p>
		{/if}
	</div>
</div>

{include file="footer" sandbox=false}

</body>
</html>
