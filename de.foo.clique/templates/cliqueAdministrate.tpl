{include file="documentHeader"}<head>	<title>{lang}wcf.clique.administrate{/lang} - {lang}{PAGE_TITLE}{/lang}</title>	{include file='headInclude' sandbox=false}	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/Suggestion.class.js"></script>	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script></head><body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>{* --- quick search controls --- *}{assign var='searchFieldTitle' value='{lang}wcf.clique.general.search{/lang}'}{assign var='searchFieldOptions' value=false}{capture assign=searchHiddenFields}	<input type="hidden" name="types[]" value="clique" />{/capture}{* --- end --- *}{include file='header' sandbox=false}<div id="main">	<ul class="breadCrumbs">		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>		<li><a href="index.php?page=CliqueOverview{@SID_ARG_2ND}"><img src="{icon}cliqueS.png{/icon}" alt="" width="16" height="16" /> <span>{lang}wcf.clique.general.name.singulary{/lang}</span></a> &raquo;</li>	</ul>	<div class="mainHeadline">		<img src="{icon}cogL.png{/icon}" alt="" />		<div class="headlineContainer">			<h2>{lang}wcf.clique.administrate{/lang}</h2>			<p>{lang}wcf.clique.administrate.description{/lang}</p>		</div>	</div>	{if $userMessages|isset}{@$userMessages}{/if}	{capture append=userMessages}		{if $errorField}			<p class="error">{lang}wcf.global.form.error{/lang}</p>		{/if}	{/capture}	<div class="tabMenu">		<ul>			<li><a href="index.php?page=CliqueDetail&amp;cliqueID={$cliqueID}{@SID_ARG_2ND}"><img src="{icon}backM.png{/icon}" alt="" /> <span>{lang}wcf.clique.general.back{/lang}</span></a></li>			{if $cliquePermissions->getCliquePermission('canEditClique') || $this->user->getPermission('mod.clique.general.canEditEveryClique')}<li><a href="index.php?form=CliqueEdit&amp;cliqueID={$cliqueID}{@SID_ARG_2ND}"><img src="{icon}cliqueEditM.png{/icon}" alt="" /> <span>{lang}wcf.clique.edit{/lang}</span></a></li>{/if}			{if $cliquePermissions->getCliquePermission('canEditRights') || $cliquePermissions->getCliquePermission('canInviteUsers') || $cliquePermissions->getCliquePermission('canAttendInvites') || $this->user->getPermission('mod.clique.general.canAdministrateEveryClique')}<li class="activeTabMenu"><a href="index.php?form=CliqueAdministrate&amp;cliqueID={$cliqueID}{@SID_ARG_2ND}"><img src="{icon}cogM.png{/icon}" alt="" /> <span>{lang}wcf.clique.administrate{/lang}</span></a></li>{/if}		</ul>	</div>	<div class="subTabMenu">		<div class="containerHead">		</div>	</div>	<form method="post" action="index.php?form=CliqueAdministrate&amp;cliqueID={$cliqueID}{@SID_ARG_2ND}">		<div class="border tabMenuContent">			<div class="container-1">					{* Funktionen *}                {if $cliquePermissions->getCliquepermission('canActivateModules')}    				<fieldset>    					<legend>{lang}wcf.clique.administrate.functions{/lang}</legend>        					{foreach from=$cliqueMenuItems item=cliqueMenuItem}    						{assign var=menuItemExplode value='.'|explode:$cliqueMenuItem.menuItem}                            {assign var=menuItemExplodeEnd value=$menuItemExplode|end|concat:'Enable'}                            {assign var=menuItem value=$menuItemExplodeEnd}    						{if $menuItem != 'overviewEnable'}    							<div class="formElement">    								<div class="formFieldLabel">    									<label for="{$menuItem}">{lang}wcf.clique.administrate.functions.{$menuItem}{/lang}</label>    								</div>    								<div class="formField">    									<input type="checkbox" id="{$menuItem}" name="{$menuItem}" {if $clique->$menuItem == 1}checked="checked" {/if}/>    								</div>    							</div>    						{/if}    					{/foreach}        					{if $additionalFunctions|isset}{@$additionalFunctions}{/if}    				</fieldset>                {/if}					{* Einladungen *}				{if $cliquePermissions->getCliquePermission('canInviteUsers') || $this->user->getPermission('mod.clique.general.canAdministrateEveryClique')}					<fieldset>						<legend>{lang}wcf.clique.administrate.members{/lang}</legend>						<div class="formElement{if $errorField == 'usernames'} formError{/if}">							<div class="formFieldLabel">								<label for="usernames">{lang}wcf.clique.administrate.members.add{/lang}</label>							</div>							<div class="formField">								<input type="text" class="inputText" name="usernames" value="{$usernames}" id="usernames" />								<script type="text/javascript">									//<![CDATA[									suggestion.setSource('index.php?page=PublicUserSuggest{@SID_ARG_2ND_NOT_ENCODED}');									suggestion.init('usernames');									//]]>								</script>								{if $errorField == 'usernames'}									<div class="innerError">										{if $errorType|is_array}											{foreach from=$errorType item=error}												<p> {* Username übergeben*}													{if $error.type == 'notFound'}{lang users=$error.username}wcf.clique.administrate.members.notFound{/lang}{/if}													{if $error.type == 'alreadyExist'}{lang users=$error.username}wcf.clique.administrate.members.alreadyExist{/lang}{/if}													{if $error.type == 'alreadyInvitet'}{lang users=$error.username}wcf.clique.administrate.members.alreadyInvitet{/lang}{/if}												</p>											{/foreach}										{else}											{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}										{/if}									</div>								{/if}							</div>							<div class="formFieldDesc">								<p>{lang}wcf.clique.administrate.members.add.description{/lang}</p>							</div>						</div>					</fieldset>				{/if}					{* Ausstehende Einladungen *}				{if $cliquePermissions->getCliquePermission('canAttendInvites') || $this->user->getPermission('mod.clique.general.canAdministrateEveryClique')}    					<fieldset>    						<legend>{lang}wcf.clique.administrate.invites.notAnswered{/lang}</legend>        						<div class="formElement">				   			 {if $notAnswerdInvites|count > 0}    								{foreach from=$notAnswerdInvites item=notAnswerdInvite}    									<div class="formField">    										<a href="index.php?action=CliqueDeleteInvite&amp;cliqueID={$cliqueID}&amp;inviteeID={$notAnswerdInvite.inviteeID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.clique.administrate.invites.delete{/lang}">    											<img src="{icon}deleteS.png{/icon}" alt="" /> {lang}wcf.clique.administrate.invites.text{/lang}    										</a>    									</div>    								{/foreach}    								<div class="formFieldDesc">    									<p>{lang}wcf.clique.administrate.invites.notAnswered.description{/lang}</p>    								</div>    							{else}    								<div class="formField">    									{lang}wcf.clique.administrate.invites.notAnswered.noOne{/lang}    								</div>    							{/if}    						</div>    					</fieldset>					<fieldset>						<legend>{lang}wcf.clique.detail.applications{/lang}</legend>						<div class="formElement">							{if $aplications|count > 0}								{foreach from=$aplications item=aplication}									<div class="formField">										{lang}wcf.clique.administrate.application.text{/lang}										<a href="index.php?action=CliqueApplicationCancel&amp;cliqueID={$cliqueID}&amp;userID={$aplication.userID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.clique.administrate.application.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a>                                        <a href="index.php?action=CliqueApplicationAccept&amp;cliqueID={$cliqueID}&amp;userID={$aplication.userID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.clique.administrate.application.accept{/lang}"><img src="{icon}checkS.png{/icon}" alt="" /></a>									</div>								{/foreach}								<div class="formFieldDesc">									<p>{lang}wcf.clique.administrate.application.description{/lang}</p>								</div>							{else}								<div class="formField">									{lang}wcf.clique.administrate.application.noOne{/lang}								</div>							{/if}						</div>					</fieldset>				{/if}					{* Cliquenrechte *}				{if $cliquePermissions->getCliquepermission('canEditRights') || $this->user->getPermission('mod.clique.general.canAdministrateEveryClique')}					<fieldset>						<legend>{lang}Rechteverwaltung{/lang}</legend>						<div class="formElement">							<table class="tableList">								<thead>									<tr class="tableHead">										<th><div><strong>{lang}wcf.clique.administrate.right{/lang}</strong></div></th>										<th><div><strong>{lang}wcf.clique.administrate.raiser{/lang}</strong></div></th>										<th><div><strong>{lang}wcf.clique.administrate.administrator{/lang}</strong></div></th>										<th><div><strong>{lang}wcf.clique.administrate.moderator{/lang}</strong></div></th>										<th><div><strong>{lang}wcf.clique.administrate.member{/lang}</strong></div></th>										<th><div><strong>{lang}wcf.clique.administrate.guest{/lang}</strong></div></th>									</tr>								</thead>								<tbody>									{foreach from=$cliqueRights item=cliqueRight}										{assign var=guest value=$cliqueRight.rightName|concat:"1"}										{assign var=member value=$cliqueRight.rightName|concat:"2"}										{assign var=moderator value=$cliqueRight.rightName|concat:"3"}										{assign var=administrator value=$cliqueRight.rightName|concat:"4"}										<tr class="{cycle values="container-1,container-2"}">											<td>{$cliqueRight.rightLanguage}</td>											<td><input type="checkbox" name="{$cliqueRight.rightName}" checked="checked" disabled="disabled" /></td>											<td><input type="checkbox" name="{$cliqueRight.rightName}4" {if $groupRights.$administrator == 1} checked="checked"{/if}value="1" /></td>											<td><input type="checkbox" name="{$cliqueRight.rightName}3" {if $groupRights.$moderator == 1} checked="checked"{/if}value="1" /></td>											<td><input type="checkbox" name="{$cliqueRight.rightName}2" {if $groupRights.$member == 1} checked="checked"{/if}value="1" /></td>											<td><input type="checkbox" name="{$cliqueRight.rightName}1" {if $groupRights.$guest == 1} checked="checked"{/if}value="1" /></td>										</tr>									{/foreach}								</tbody>							</table>						</div>					</fieldset>						{* User zur Gruppe *}					<fieldset>						<legend>{lang}wcf.clique.administrate.usercp{/lang}</legend>						<div class="formElement">							{if $memberships|count > 0}								{foreach from=$memberships item=membership}									<div class="formFieldLabel">										<label for="usernames"><a href="index.php?page=User&amp;userID={$membership.userID}{@SID_ARG_2ND}" title="{lang username=$membership.username}wcf.clique.comment.viewProfile{/lang}">{$membership.username}</a> <a href="index.php?action=CliqueKickUser&amp;cliqueID={$cliqueID}&amp;userID={$membership.userID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang username=$membership.username}wcf.clique.administrate.kickUser{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a></label>									</div>									<div class="formField">										{htmloptions name=$membership.userID options=$groups selected=$membership.groupType}									</div>								{/foreach}								<div class="formFieldDesc">										<p>{lang}wcf.clique.administrate.usercp.description{/lang}</p>								</div>							{else}								<div class="formField">									{lang}wcf.clique.administrate.usercp.noone{/lang}								</div>							{/if}						</div>					</fieldset>				{/if}					{* Clique übertragen *}				{if $clique->raiserID == $this->user->userID || $this->user->getPermission('mod.clique.general.canAdministrateEveryClique')}					<fieldset>						<legend>{lang}wcf.clique.administrate.changeRaiser{/lang}</legend>						<div class="formElement{if $errorField == 'changeRaiser'} formError{/if}">							<div class="formFieldLabel">								<label for="changeRaiser">{lang}wcf.clique.administrate.changeRaiser.username{/lang}</label>							</div>							<div class="formField">								<input type="text" class="inputText" name="changeRaiser" value="{$usernames}" id="changeRaiser" />                                <input id="changeRaiserSure" name="changeRaiserSure" type="checkbox" />{lang}wcf.clique.administrate.changeRaiser.sure{/lang}								<script type="text/javascript">									//<![CDATA[									suggestion.setSource('index.php?page=PublicUserSuggest{@SID_ARG_2ND_NOT_ENCODED}');									suggestion.init('changeRaiser');									//]]>								</script>								{if $errorField == 'changeRaiser'}									<div class="innerError">										{if $errorType|is_array}											{foreach from=$errorType item=error}												<p> {* Username übergeben*}													{if $error.type == 'notFound'}{lang users=$error.username}wcf.clique.administrate.members.notFound{/lang}{/if}													{if $error.type == 'notOne'}{lang users=$error.username}wcf.clique.administrate.changeRaiser.notOne{/lang}{/if}                                                    {if $error.type == 'changeRaiserSure'}{lang}wcf.clique.administrate.changeRaiser.sure.notFilled{/lang}{/if}												</p>											{/foreach}										{else}											{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}										{/if}									</div>								{/if}							</div>							<div class="formFieldDesc">								<p>{lang}wcf.clique.administrate.changeRaiser.description{/lang}</p>							</div>						</div>					</fieldset>				{/if}			</div>		</div>		<div class="formSubmit">			<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />			<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />			<input type="hidden" name="cliqueID" value="{$cliqueID}" />			{@SID_INPUT_TAG}		</div>	</form></div>{include file='footer' sandbox=false}</body></html>