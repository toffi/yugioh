<div class="message content">
	<div class="messageInner container-{cycle name='results' values='1,2'}">
		<div class="messageHeader">
			<div class="containerIcon">
				<a href="index.php?page=CliqueDetail&amp;cliqueID={@$item.message->cliqueID}{@SID_ARG_2ND}"><img src="{icon}cliqueM.png{/icon}" alt="" /></a>
			</div>
			<div class="containerContent">
				<p class="light smallFont">{@$item.message->getFormattedMessage($item.message->name)}</p>
			</div>
		</div>
		
		<h3>
			<a href="index.php?page=CliqueDetail&amp;cliqueID={@$item.message->cliqueID}{@SID_ARG_2ND}">
				{@$item.message->getFormattedMessage($item.message->name)} - {@$item.message->getFormattedMessage($item.message->shortDescription)}
			</a>
		</h3>
		
		<div class="messageBody">
			<div class="userAvatar">
				<a href="index.php?page=CliqueDetail&amp;cliqueID={@$item.message->cliqueID}{@SID_ARG_2ND}">
					<img alt="" src="{if !$item.message->image|empty}{RELATIVE_WCF_DIR}images/clique/{$item.message->image}{else}{icon}noCliquePicL.png{/icon}{/if}" width="{if !$item.message->image|empty}{$item.message->width}{else}100{/if}" height="{if !$item.message->image|empty}{$item.message->height}{else}100{/if}" />
				</a>
			{@$item.message->getFormattedMessage($item.message->description)}
			</div>
		</div>
		
		<div class="messageFooter">
			<div class="smallButtons">
				<ul>
					<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
				</ul>
			</div>
		</div>
		<hr />
	</div>
</div>