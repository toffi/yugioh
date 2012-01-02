	{* Letzten X Threads *}
{if $cliquePermissions->getCliquePermission('canSeeBoards') && $clique->boardEnable == 1}
	<div class="contentBox">
		<h3 class="subHeadline"><a href="index.php?page=CliqueBoard&amp;cliqueID={$cliqueID}{@SID_ARG_2ND}">{lang}wbb.board.threads.normal{/lang}</a></h3>

		<ul class="dataList">
			{if $lastThreads|count > 0}
				{foreach from=$lastThreads item=thread}
					<li class="{cycle values='container-1,container-2'}">
        				<div class="containerIcon">
        					<img src="{icon}postM.png{/icon}" alt="" />
        				</div>
        				<div class="containerContent">
        					<h4><a href="index.php?page=CliqueThread&amp;cliqueID={$cliqueID}&amp;threadID={$thread->threadID}{@SID_ARG_2ND}">{$thread->topic}</a></h4>
        					<p class="firstPost smallFont light">{lang}wcf.clique.detail.at{/lang} {if $thread->userID}<a href="index.php?page=User&amp;userID={$thread->userID}{@SID_ARG_2ND}">{$thread->username}</a>{else}{$thread->username}{/if} ({@$thread->time|time})</p>
        				</div>
					</li>
				{/foreach}
			{else}
				<li class="container-1">{lang}wbb.board.name.clique.board.noThreads{/lang}</li>
			{/if}
		</ul>

		<div class="buttonBar">
			<div class="smallButtons">
				<ul>
					<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" width="16" height="16" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
				</ul>
			</div>
		</div>
	</div>
{/if}