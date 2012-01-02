<ul class="breadCrumbs">
	{if !$hideRoot|isset}
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
	{/if}
	
	{foreach from=$board->getParentBoards() item=parentBoard}
        {counter assign=cliqueBoardNo print=false}
        {if $cliqueBoardNo == 1}
    		<li><a href="index.php?page=CliqueOverview{@SID_ARG_2ND}"><img src="{icon}cliqueS.png{/icon}" alt="" width="16" height="16" /> <span>{lang}wcf.clique.general.name.singulary{/lang}</span></a> &raquo;</li>
        {else}
		  <li><a href="index.php?page=Board&amp;boardID={@$parentBoard->boardID}{@SID_ARG_2ND}"><img src="{icon}{@$parentBoard->getIconName()}S.png{/icon}" alt="" /> <span>{lang}{$parentBoard->title}{/lang}</span></a> &raquo;</li>
        {/if}
	{/foreach}
	
	{if $showBoard|isset || $showThread|isset}
		<li><a href="index.php?page=Board&amp;boardID={@$board->boardID}{@SID_ARG_2ND}"><img src="{icon}{@$board->getIconName()}S.png{/icon}" alt="" /> <span>{lang}{$board->title}{/lang}</span></a> &raquo;</li>
	{/if}
	
	{if $showThread|isset}
		<li><a href="index.php?page=Thread&amp;threadID={@$thread->threadID}{@SID_ARG_2ND}"><img src="{icon}threadS.png{/icon}" alt="" /> {if $thread->prefix}<span class="prefix"><strong>{lang}{$thread->prefix}{/lang}</strong></span> {/if}<span>{$thread->topic}</span></a> &raquo;</li>
	{/if}
</ul>