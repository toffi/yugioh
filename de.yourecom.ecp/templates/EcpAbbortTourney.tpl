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
			<a href="index.php?page=ECPTourney{@SID_ARG_2ND}"><img src="{icon}DDiskGoldS.png{/icon}" alt="" /> <span>{lang}wcf.ecp.tourney.tourney{/lang}</span></a> &raquo;
		</li>
	</ul>
	
	<div class="mainHeadline">
		<img src="{icon}DDiskM.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.ecp.tourney.abbort{/lang}</h2>
		</div>
	</div>
	 <!-- PN Abfrage -->
	{if $userMessages|isset}{@$userMessages}{/if}

	{if $this->user->getPermission('mod.ecp.canCreateTourney')}
		<div class="smallButtons">
			<ul>
				<li>
					<a id="threadLink0" href="index.php?form=EcpInsertTourney{@SID_ARG_2ND}">
						<img src="{icon}messageAddM.png{/icon}" alt="" />
						<span>{lang}wcf.ecp.tourney.insert.name{/lang}</span>
					</a>
				</li>
				<li>
					<a id="threadLink0" href="index.php?page=EcpAbbortTourney{@SID_ARG_2ND}">
						<img src="{icon}cancelS.png{/icon}" alt="" />
						<span>{lang}wcf.ecp.tourney.abbort{/lang}</span>
					</a>
				</li>
			</ul>
		</div>
	{/if}
 <!-- Turnier Übersicht -->
	<div class="border">
		{if $tourneyList|isset && $tourneyList|count}
			<table class="tableList membersList">
				<thead>
					<tr class="tableHead">
						<th class="columnName{if $sortField == 'name'} active{/if}"><a href="index.php?page=EcpAbbortTourney&amp;sortField=name&amp;amp;sortOrder={if $sortField == 'name' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.ecp.tourney.show.name{/lang}{if $sortField == 'name'} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}</a></th>
						<th class="columnName{if $sortField == 'time'} active{/if}"><a href="index.php?page=EcpAbbortTourney&amp;sortField=time&amp;sortOrder={if $sortField == 'time' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.ecp.tourney.show.time{/lang}{if $sortField == 'time'} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}</a></th>
						<th class="columnName{if $sortField == 'art'} active{/if}"><a href="index.php?page=EcpAbbortTourney&amp;sortField=art&amp;amp;sortOrder={if $sortField == 'art' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.ecp.tourney.show.art{/lang}{if $sortField == 'art'} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}</a></th>
						<th class="columnName{if $sortField == 'contacts'} active{/if}"><a href="index.php?page=EcpAbbortTourney&amp;sortField=contacts&amp;amp;sortOrder={if $sortField == 'contacts' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.ecp.tourney.show.admin{/lang}{if $sortField == 'contacts'} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}</a></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$tourneyList item=value}
						<tr class="container-{cycle values="1,2"}">
							<td align="center"><a href="index.php?form=ECPTourneyDetail&amp;eventID={$value.id}{@SID_ARG_2ND}">{$value.name}</a></td>
							<td align="center">{@$value.time|time}</td>
							<td align="center">{lang}{$value.artName}{/lang}</td>
							<td align="center"><a href="index.php?page=User&amp;userID={$value.contacts}{@SID_ARG_2ND}">{$value.contacts_name}</a></td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		{else}
		<!-- Keine Ergebnisse? -->
				<p align="center">{lang}wcf.ecp.tourney.noTourney{/lang}</p>
		{/if}
	</div>
</div>

{include file="footer" sandbox=false}

</body>
</html>
