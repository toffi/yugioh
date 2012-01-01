{include file="documentHeader"}
<head>
	<title>{PAGE_TITLE}</title>
	{include file='headInclude' sandbox=false}
	<link rel="stylesheet" type="text/css" media="screen" href="./style/ecp.css" />
</head>
<body>
{include file='header' sandbox=false}
{include file='ecpHeader'}

<div id="main">
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="icon/indexS.png" alt="" /> <span>{PAGE_TITLE}</span></a> »
			<a href="index.php?page=ECPNews{@SID_ARG_2ND}"><img src="{icon}ecpS.png{/icon}" alt="" /> <span>{lang}wcf.ecp.title.short{/lang}</span></a> »
		</li>
	</ul>

	<div class="mainHeadline">
		<img src="{icon}ecpheader.png{/icon}" alt="" />
		<div class="headlineContainer">
		<h2>{lang}wcf.ecp.title{/lang}</h2>
		<p>{lang}wcf.ecp.title.description{/lang}</p>
		</div>
	</div>
    
{if $userMessages|isset}{@$userMessages}{/if}

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
	<div class="firstbox">
		<div id="firstboxcontent">
			<div class="containerHead">
				<div class="containerContent">{lang}wcf.ecp.title.ff{/lang}</div>
			</div>
			<div class="container-1">
				<div class="containerContent">
					<div class="messageContentInner color-1">
						{foreach from=$itemLeft item=news1}
							{if $news1.topic|isset}
								<div class="bigFont light" style="border: 0;">
									<strong>{$news1.time|time}<br />{$news1.topic}</strong>
								</div>
								<div class="smallFont light" style="border: 0;">
									{@$news1.message}<br />
									<span class="smallFont">
										<a href="index.php?page=Thread&amp;threadID={@$news1.threadID}{@SID_ARG_2ND}">{lang}wcf.ecp.news.readmore{/lang}</a>
									</span>
								</div>
							{else}
								<ul style="margin: 0; padding: 1;">
									<li><p>{lang}wcf.ecp.news.noNews{/lang}</p></li>
								</ul>
							{/if}
						{/foreach}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="thirdbox">
		<div id="thirdboxcontent">
			<div class="containerHead">
				<div class="containerContent">{lang}wcf.ecp.teamarea{/lang}</div>
			</div>
			<div class="container-1">
				<div class="containerContent">
					<div class="messageContentInner color-1">
						{foreach from=$itemRight item=news1}
							{if $news1.topic|isset}	
								<div class="bigFont light" style="border: 0;">
									<strong>{$news1.time|time}<br />{$news1.topic}</strong>
								</div>
								<div class="smallFont light" style="border: 0;">
									{@$news1.message}<br />
									<span class="smallFont">
										<a href="index.php?page=Thread&amp;threadID={@$news1.threadID}{@SID_ARG_2ND}">{lang}wcf.ecp.news.readmore{/lang}</a>
									</span>
								</div>
							{else}
								<ul style="margin: 0; padding: 1;">
									<li><p>{lang}wcf.ecp.news.noNews{/lang}</p></li>
								</ul>
							{/if}
						{/foreach}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="secondbox">
		<div id="secondboxcontent">
			<div class="containerHead">
				<div class="containerContent">{lang}wcf.ecp.tourney{/lang}</div>
			</div>
			<div class="container-1">
				<div class="containerContent">
					<div class="messageContentInner color-1">
						{foreach from=$itemMiddle item=news1}
							{if $news1.topic|isset}	
								<div class="bigFont light" style="border: 0;">
									<strong>{$news1.time|time}<br />{$news1.topic}</strong>
								</div>
								<div class="smallFont light" style="border: 0;">
									{@$news1.message}<br />
									<span class="smallFont">
										<a href="index.php?page=Thread&amp;threadID={@$news1.threadID}{@SID_ARG_2ND}">{lang}wcf.ecp.news.readmore{/lang}</a>
									</span>
								</div>
							{else}
								<ul style="margin: 0; padding: 1;">
									<li><p>{lang}wcf.ecp.news.noNews{/lang}</p></li>
								</ul>
							{/if}
						{/foreach}
					</div>
				</div>
			</div>
		</div>
	</div>
{/if}
</div>

{include file="footer" sandbox=false}
</body>
</html>