<div class="mainHeadline">
	<img src="{icon}cliqueL.png{/icon}" alt="" />
	<div class="headlineContainer">
		<h2>{$clique->name}</h2>
	</div>
</div>


<div id="userCard" class="border">
	<div class="userCardInner container-1">
		<ul class="userCardList">
			<li id="userCardAvatar">
				<div class="userAvatar">
					<img alt="" src="{if $clique->image != ''}{RELATIVE_WCF_DIR}images/clique/{$clique->image}{else}{icon}noCliquePicL.png{/icon}{/if}" width="{if $clique->image != ''}{$clique->width}{else}100{/if}" height="{if $clique->image != ''}{$clique->height}{else}100{/if}" />
				</div>
			</li>

			<li id="userCardCredits" style="margin-left: 200px;">
				<div class="userCardCreditsInner">
					<div class="userPersonals">
						<p class="userName">
							<span>{$clique->name}</span>
						</p>
					</div>

					<div class="smallButtons userCardOptions">
						<ul>
							{if $cliquePermissions->getCliquePermission('canEditRights') || $cliquePermissions->getCliquePermission('canInviteUsers') || $cliquePermissions->getCliquePermission('canAttendInvites') || $this->user->getPermission('mod.clique.general.canAdministrateEveryClique')}<li><a href="index.php?form=CliqueAdministrate&amp;cliqueID={$clique->cliqueID}{@SID_ARG_2ND}" title="{lang}wcf.clique.edit{/lang}"><img src="{icon}cogM.png{/icon}" alt="" width="24" height="24" /> <span>{lang}wcf.clique.administrate{/lang}</span></a></li>{/if}
							{if $cliquePermissions->getCliquePermission('canEditClique') || $this->user->getPermission('mod.clique.general.canEditEveryClique')}<li><a href="index.php?form=CliqueEdit&amp;cliqueID={$clique->cliqueID}{@SID_ARG_2ND}" title="{lang}wcf.clique.edit{/lang}"><img src="{icon}cliqueEditM.png{/icon}" alt="" width="24" height="24" /> <span>{lang}wcf.clique.edit{/lang}</span></a></li>{/if}
							{if $cliquePermissions->getCliquePermission('canDeleteClique') || $this->user->getPermission('mod.clique.general.canDeleteEveryClique')}<li id="cliqueDelete"><a href="index.php?action=CliqueDelete&amp;cliqueID={$clique->cliqueID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.clique.general.delete{/lang}" onclick="return confirm('{lang}wcf.clique.delete.sure{/lang}')"><img src="{icon}deleteM.png{/icon}" alt="" width="24" height="24"/> <span>{lang}wcf.clique.delete{/lang}</span></a></li>{/if}

							{if $clique->status == 0 && $clique->raiserID != $this->user->userID && $this->user->userID != 0}
								<li id="cliqueLeave"><a href="index.php?action={if $isMember != 1}CliqueJoin{else}CliqueLeave{/if}&amp;cliqueID={@$clique->cliqueID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.clique.general.{if $isMember != 1}enter{else}leave{/if}{/lang}"{if $isMember == 1} onclick="return confirm('{lang}wcf.clique.general.leave.sure{/lang}')"{/if}><img src="{icon}{if $isMember != 1}add{else}delete{/if}M.png{/icon}" alt="" width="24" height="24" /> <span>{lang}wcf.clique.general.{if $isMember != 1}enter{else}leave{/if}{/lang}</span></a></li>
							{elseif $clique->status == 1 && $isMember == 1 && $clique->raiserID != $this->user->userID}
								<li id="cliqueLeave"><a href="index.php?action=CliqueLeave&amp;cliqueID={@$clique->cliqueID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.clique.general.leave{/lang}" onclick="return confirm('{lang}wcf.clique.general.leave.sure{/lang}')"><img src="{icon}deleteM.png{/icon}" alt="" width="24" height="24" /><span>{lang}wcf.clique.general.leave{/lang}</span></a></li>
							{elseif $clique->status == 1 && $isMember != 1 && $haveApplay == 0 && $this->user->userID != 0}
								<li><a href="#" onclick="javascript:toggledisplay('hiddendiv'); return false" title="{lang}wcf.clique.detail.application{/lang}"><img src="{icon}applicationM.png{/icon}" alt="" width="24" height="24" /> {lang}wcf.clique.detail.application{/lang}</a></li>
							{elseif $clique->status == 1 && $isMember != 1 && $haveApplay == 1 && $this->user->userID != 0}
								<li><a href="index.php?action=CliqueApplicationCancel&amp;cliqueID={@$clique->cliqueID}&amp;userID={@$this->user->userID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.clique.detail.applications.cancel{/lang}"><img src="{icon}applicationM.png{/icon}" alt="" width="24" height="24" /> {lang}wcf.clique.detail.applications.cancel{/lang}</a></li>
							{/if}
						</ul>
					</div>
					{include file='cliqueApplication'}
				</div>

				<div class="friendsNone">
					<h3 class="light">{$clique->shortDescription}</h3>
				</div>
			</li>
		</ul>
	</div>
</div>

	<div id="cliqueContent" class="tabMenu">
		<ul>
		{foreach from=$cliqueMenuItems item=cliqueMenuItem}
			<li {if $cliqueMenuItem.active == 1}class="activeTabMenu"{/if}><a href="{$cliqueMenuItem.menuItemLink}&amp;cliqueID={$clique->cliqueID}{@SID_ARG_2ND}"><img src="{icon}{$cliqueMenuItem.menuItemIcon}{/icon}" alt="" width="24" height="24" /> <span>{lang}{$cliqueMenuItem.menuItem}{/lang}</span></a></li>
		{/foreach}

		</ul>
	</div>
	<div class="subTabMenu">
		<div class="containerHead"></div>
	</div>