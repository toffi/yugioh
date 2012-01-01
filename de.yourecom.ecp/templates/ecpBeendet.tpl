{include file="documentHeader"}
<head>
	<title>{lang}wcf.ecp.beendet.name{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file="headInclude"}
</head>
<body>
{include file="header" sandbox=false}
{include file='ecpHeader'}

<div id="main">
  <ul class="breadCrumbs">
  	<li>
      <a href="index.php?page=Index{@SID_ARG_2ND}"><img src="icon/indexS.png" alt="" /> <span>{PAGE_TITLE}</span></a> &raquo;
      <a href="index.php?page=ECPNews{@SID_ARG_2ND}"><img src="{@RELATIVE_WBB_DIR}icon/ecpS.png" alt="" /> <span>{lang}wcf.ecp.title.short{/lang}</span></a> &raquo;
      <a href="index.php?page=ECPBeendet{@SID_ARG_2ND}"><img src="{@RELATIVE_WBB_DIR}icon/finishS.png" alt="" /> <span>{lang}wcf.ecp.beendet.name{/lang}</span></a> &raquo;
  	</li>
  </ul>

	<div class="mainHeadline">
        <img src="{@RELATIVE_WBB_DIR}icon/finishL.png" alt="" />
		<div class="headlineContainer">
    		<h2>{lang}wcf.ecp.beendet.name{/lang}</h2>
    		<p></p>
		</div>
    </div>
    
	<div class="subTabMenu">
		<div class="containerHead">
			<ul>
				<li{if $letter|empty} class="activeSubTabMenu"{/if}><a href="index.php?page=ECPBeendet{@SID_ARG_2ND}"><span>{lang}Alle{/lang}</span></a></li>
				{foreach from=$letters item=letterItem}
					<li{if $letterItem|rawurlencode == $letter} class="activeSubTabMenu"{/if}><a href="index.php?page=ECPBeendet&amp;letter={@$letterItem|rawurlencode}{@SID_ARG_2ND}"><span>{$letterItem}</span></a></li>
				{/foreach}
			</ul>
		</div>
	</div>
 
	{if ECP_ENABLE == '1'}
		<div class="border content">
			<div class="container-1">
				<fieldset>
					<legend><b>{lang}wcf.ecp.offline{/lang}</b></legend>
					{@ECP_MESSAGE}
				</fieldset>
			</div>	
		</div>
	{else}
		<div class="border">
			{if $old_events != ''}
				<table class="tableList">
					<thead>
						<tr class="tableHead">
							<th{if $sortField == 'eventName'} class="active"{/if}><a href="index.php?page=ECPBeendetl&amp;pageNo={@$pageNo}&amp;sortField=eventName&amp;sortOrder={if $sortField == 'eventName' && $sortOrder == 'ASC'}DESC{elseif $sortField == 'eventName'}ASC{/if}{@SID_ARG_2ND}"><span>{lang}wcf.ecp.beendet.eventname{/lang}{if $sortField == 'eventName'} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}</span></a></th>
							<th{if $sortField == 'art'} class="active"{/if}><a href="index.php?page=ECPBeendetl&amp;pageNo={@$pageNo}&amp;sortField=art&amp;sortOrder={if $sortField == 'art' && $sortOrder == 'ASC'}DESC{elseif $sortField == 'art'}ASC{/if}{@SID_ARG_2ND}"><span>{lang}wcf.ecp.beendet.eventart{/lang}{if $sortField == 'art'} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}</span></a></th>
							<th{if $sortField == 'username'} class="active"{/if}><a href="index.php?page=ECPBeendetl&amp;pageNo={@$pageNo}&amp;sortField=username&amp;sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{elseif $sortField == 'username'}ASC{/if}{@SID_ARG_2ND}"><span>{lang}wcf.ecp.beendet.eventveranstalter{/lang}{if $sortField == 'username'} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}</span></a></th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$old_events item=events}
							<tr class="container-{cycle values='1,2'}">
								<td>
									<a href="index.php?page=ECPRound&amp;eventID={$events.eventID}&amp;akt={$events.currentGameday}{@SID_ARG_2ND}">{$events.eventName}</a>
								</td>
								<td>
									<a href="index.php?page=ECPRound&amp;eventID={$events.eventID}&amp;akt={$events.currentGameday}{@SID_ARG_2ND}">{$events.arten_name}</a>
								</td>
								<td>
								    <a href="index.php?page=User&amp;userID={$events.contacts}{@SID_ARG_2ND}" title="{lang username=$events.username}wcf.user.viewProfile{/lang}">{$events.username}</a>
								</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			{else}
				<div class="border content">
					<div class="container-1">
						<fieldset>
							<legend><b>{lang}wcf.ecp.beendet.name{/lang}</b></legend>
							{lang}wcf.ecp.beendet.name.description{/lang}
						</fieldset>
					</div>	
				</div>
			{/if}
		</div>
	{/if}
    
    {pages print=true link="index.php?page=ECPBeendet&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&letter=$letter"|concat:SID_ARG_2ND_NOT_ENCODED}
</div>

{include file="footer" sandbox=false}

</body>
</html>
