{if $this->user->getPermission('mod.board.canEditEvent')}
	<li><a href="index.php?form=EventboxEdit{@SID_ARG_2ND}"><img src="{icon}radioS.png{/icon}" alt="" /> <span>{lang}wcf.eventbox.title{/lang}</span></a></li>
{/if}