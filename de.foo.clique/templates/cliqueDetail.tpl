{include file="documentHeader"}
<head>
	<title>{lang}wcf.clique.detail{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	<script type="text/javascript">
		//<![CDATA[
		var INLINE_IMAGE_MAX_WIDTH = {@INLINE_IMAGE_MAX_WIDTH};
		//]]>
	</script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ImageResizer.class.js"></script>
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{* --- quick search controls --- *}
{assign var='searchFieldTitle' value='{lang}wcf.clique.general.search{/lang}'}
{assign var='searchFieldOptions' value=false}
{capture assign=searchHiddenFields}
	<input type="hidden" name="types[]" value="clique" />
{/capture}
{* --- end --- *}
{include file='header' sandbox=false}

<div id="main">
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" width="16" height="16" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo; </li>
		<li><a href="index.php?page=CliqueOverview{@SID_ARG_2ND}"><img src="{icon}cliqueS.png{/icon}" alt="" width="16" height="16" /> <span>{lang}wcf.clique.general.name.singulary{/lang}</span></a> &raquo;</li>
	</ul>
	{if $userMessages|isset}{@$userMessages}{/if}

	{include file="cliqueDetailHeader"}

	<div class="border">
		<div class="layout-2">
			<div class="columnContainer">
				<div class="container-1 column first">
					<div class="columnInner">

								{* Cliquen Infos *}
							<div class="contentBox">
								<h3 class="subHeadline">{lang}wcf.clique.detail.information{/lang}</h3>
								<ul class="dataList">
									<li class="container-1 formElement">
										<p class="formFieldLabel">{lang}wcf.clique.add.description{/lang}</p>
										<p class="formField">{@$clique->description}</p>
									</li>
								</ul>
								<div class="buttonBar">
									<div class="smallButtons">
										<ul><li class="extraButton"><a href="#top" title="{lang}wcf.clique.general.top{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.clique.general.top{/lang}" width="16" height="16" /> <span class="hidden">{lang}wcf.clique.general.top{/lang}</span></a></li></ul>
									</div>
								</div>
							</div>

								{* Mitglieder *}
							<div class="contentBox">
								<h3 class="subHeadline">{lang}wcf.clique.membership.members{/lang}</h3>

								<ul class="formField">
									<li style="list-style: none;" class="container-2 formElement">
										{lang}wcf.clique.membership.numberOfMembers{/lang}
									</li>
									{foreach from=$memberships item=userData}
										<li class="dynContainer" style="float: left">
												{if $userData->getAvatar()}
													{assign var=y value=$userData->getAvatar()->setMaxSize(100, 100)}
													<div style="min-height:100px; max-height:100px; min-width:120px;"><a href="index.php?page=User&amp;userID={$userData->userID}{@SID_ARG_2ND}"><img alt="" src="{$userData->getAvatar()->getURL()}" style="width: {$userData->getAvatar()->width}px; height: {$userData->getAvatar()->height}px;" /></a></div><br />
												{else}
													<div style="min-height:100px; max-height:100px; min-width:120px;"><a href="index.php?page=User&amp;userID={$userData->userID}{@SID_ARG_2ND}"><img alt="" src="{RELATIVE_WCF_DIR}images/avatars/avatar-default.png" style="width: 100}px; height: 100px;" /></a></div><br />
												{/if}
												<a href="index.php?page=User&amp;userID={$userData->userID}{@SID_ARG_2ND}">{$userData->username}</a>
										</li>
									{/foreach}
								</ul>

								<div class="buttonBar">
									<div class="smallButtons">
										<ul>
                                            <li class="extraButton"><a href="#top" title="{lang}wcf.clique.general.top{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.clique.general.top{/lang}" width="16" height="16" /> <span class="hidden">{lang}wcf.clique.general.top{/lang}</span></a></li>
                                            {if $countMemberships > 5}<li><a href="index.php?page=CliqueMembers&amp;cliqueID={$cliqueID}{@SID_ARG_2ND}"><span><img src="{icon}membersS.png{/icon}" alt="" />{lang}wcf.clique.membership.all{/lang}</span></a></li>{/if}
                                        </ul>

									</div>
								</div>
							</div>

							{* Letzten X Kommentare *}
						{if $cliquePermissions->getCliquePermission('canSeeComments') && $clique->commentEnable == 1}
							<div class="contentBox">
								<h3 class="subHeadline"><a href="index.php?page=CliqueComments&amp;cliqueID={$clique->cliqueID}{@SID_ARG_2ND}">{lang}wcf.clique.comment{/lang}</a> <span>({$countComments})</span></h3>

								<ul class="dataList">
									{if $cliqueComments|count > 0}
										{foreach from=$cliqueComments item=comment}
											<li class="{cycle values='container-1,container-2'}">
												<div class="containerIcon">
													{if $comment->getAvatar()}
														{assign var=x value=$comment->getAvatar()->setMaxSize(24, 24)}
														<img alt="" src="{$comment->getAvatar()->getURL()}" style="width: {$comment->getAvatar()->width}px; height: {$comment->getAvatar()->height}px;" /><br />
													{else}
														<img alt="" src="{RELATIVE_WCF_DIR}images/avatars/avatar-default.png" style="width: 24px; height: 24;" /><br />
													{/if}
												</div>
												<div class="containerContent">
													<h4><a href="index.php?page=CliqueComments&amp;cliqueID={$clique->cliqueID}#entry{$comment->commentID}{@SID_ARG_2ND}">{@$comment->message|truncatehtml(CLIQUE_COMMENTS_CHARACTER_NUMBER)}</a></h4>
													<p class="firstPost smallFont light">{lang}wcf.clique.detail.at{/lang} {if $comment->userID}<a href="index.php?page=User&amp;userID={$comment->userID}{@SID_ARG_2ND}">{$comment->username}</a>{else}{$comment->username}{/if} ({@$comment->time|time})</p>
												</div>
											</li>
										{/foreach}
									{else}
										<li class="container-1">{lang}wcf.clique.comment.noone{/lang}</li>
									{/if}
								</ul>

								<div class="buttonBar">
									<div class="smallButtons">
										<ul>
											<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" width="16" height="16" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
											<li><a href="index.php?page=CliqueComments&amp;cliqueID={$clique->cliqueID}{@SID_ARG_2ND}" title="{lang}wcf.clique.comment.showAll{/lang}"><img src="{icon}messageS.png{/icon}" alt="" /> <span>{lang}wcf.clique.comment.showAll{/lang}</span></a></li>
											{if $cliquePermissions->getCliquePermission('canMakeComments') && $this->user->getPermission('user.clique.comments.canMakeComment')}<li><a href="index.php?form=CliqueAddComment&amp;cliqueID={$clique->cliqueID}{@SID_ARG_2ND}" title="{lang}wcf.clique.comment.makeInsert{/lang}"><img src="{icon}addS.png{/icon}" alt="" /><span>{lang}wcf.clique.comment.insert{/lang}</span></a></li>{/if}
										</ul>
									</div>
								</div>
							</div>
						{/if}

						{if $additionalMainframeBottom|isset}{@$additionalMainframeBottom}{/if}
					</div>
				</div>

				<div class="container-3 column second profileSidebar">
					<div class="columnInner">
						<div class="contentBox">
							<div class="border">
								<div class="containerHead">
									<h3>{lang}wcf.clique.detail.raisinginfo{/lang}</h3>
								</div>
								<ul class="dataList">

										{* Cliquengründer *}
									<li class="container-1" id="cliquenRaiser">
										<div class="containerIcon">
											<img src="{icon}cliqueRaiserM.png{/icon}" alt="" title="{lang}wcf.clique.general.raiser{/lang}" width="24" height="24" />
										</div>
										<div class="containerContent">
											<h4 class="smallFont">{lang}wcf.clique.general.raiser{/lang}</h4>
											<p>
												<a href="index.php?page=User&amp;userID={$clique->raiserID}{@SID_ARG_2ND}"><span>{$clique->username}</span></a>
											</p>
										</div>
									</li>

										{* Gründungszeit *}
									<li class="container-2" id="cliquenTime">
										<div class="containerIcon">
											<img src="{icon}cliqueRaiseTimeM.png{/icon}" alt="" title="{lang}wcf.clique.general.creationDate{/lang}" width="24" height="24" />
										</div>
										<div class="containerContent">
											<h4 class="smallFont">{lang}wcf.clique.general.creationDate{/lang}</h4>
											<p>
												{$clique->time|date}
											</p>
										</div>
									</li>

										{* Status *}
									<li class="container-1" id="cliquenStatus">
										<div class="containerIcon">
											<img src="{icon}clique{if $clique->status == 0}Open{else}Privat{/if}M.png{/icon}" alt="" title="{lang}wcf.clique.add.status{/lang}" width="24" height="24" />
										</div>
										<div class="containerContent">
											<h4 class="smallFont">{lang}wcf.clique.add.status{/lang}</h4>
											<p>
												{lang}wcf.clique.general.status.{@$clique->status}{/lang}
											</p>
										</div>
									</li>

										{* Kategorie *}
									{if !$clique->categorie|empty}
										<li class="container-2">
											<div class="containerIcon">
												<img src="{icon}cliqueOpenM.png{/icon}" alt="" title="{lang}wcf.clique.detail.category{/lang}" width="24" height="24" />
											</div>
											<div class="containerContent">
												<h4 class="smallFont">{lang}wcf.clique.detail.category{/lang}</h4>
												<p>
													{$clique->categorie}
												</p>
											</div>
										</li>
									{/if}

								</ul>
							</div>
						</div>

						{if CLIQUE_VISITORS_ENABLE && $cliqueVisitors|count > 0}
							<div class="contentBox">
								<div class="border">
									<div class="containerHead">
										<h3>{lang}wcf.clique.detail.visitors{/lang}</h3>
									</div>
									<ul class="dataList">

										{foreach from=$cliqueVisitors item=visitorData}
											<li class="{cycle values="container-1,container-2"}">
												<div class="containerIcon">
													<a href="index.php?page=User&amp;userID={$visitorData->userID}{@SID_ARG_2ND}" title="{lang}wcf.clique.detail.visitProfile{/lang}"><img src="{if $visitorData->getAvatar()}{$visitorData->getAvatar()->getURL()}{else}{RELATIVE_WCF_DIR}images/avatars/avatar-default.png{/if}" alt="" style="width: 24px; height: 24px;" /></a>
												</div>
												<div class="containerContent">
													<h4><a href="index.php?page=User&amp;userID={$visitorData->userID}{@SID_ARG_2ND}" title="{lang}wcf.clique.detail.visitProfile{/lang}">{$visitorData->username}</a></h4>
													<p class="light smallFont">{@$visitorData->time|time}</p>
												</div>
											</li>
										{/foreach}

									</ul>
								</div>
							</div>
						{/if}

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

{include file='footer' sandbox=false}
</body>
</html>