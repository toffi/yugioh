{if $memberships|count > 0}
	<div class="contentBox">
		<div class="border">
			<div class="containerHead">
				<h3>{lang}wcf.clique.general.name.plural{/lang}</h3>
			</div>

			<ul class="dataList">
				{foreach from=$memberships item=membership}
					<li class="{cycle values='container-1,container-2'}">
						<div class="containerIcon">
							<img alt="" src="{if !$membership.image|empty}{RELATIVE_WCF_DIR}images/clique/{$membership.image}{else}{icon}noCliquePicL.png{/icon}{/if}" style="width: 24px; height: 24px" />
						</div>
						<div class="containerContent">
							<h4><a href="index.php?page=CliqueDetail&amp;cliqueID={$membership.cliqueID}{@SID_ARG_2ND}" title="{lang cliqueName=$membership.name}wcf.user.viewClique{/lang}">{$membership.name}</a></h4>
							<p class="light smallFont">{lang}wcf.user.enteredClique{/lang}: {@$membership.enteredTime|time}</p>
						</div>
					</li>
				{/foreach}
			</ul>
		</div>
	</div>
{/if}