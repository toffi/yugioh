{if $this->getUser()->getPermission('user.board.canViewGeburtstagIndex')}
	<div class="container-1">
		<div class="containerIcon"><img src="icon/birthdayM.png" alt="" /></div>
		<div class="containerContent">
			<h3>{lang}wbb.index.geburtstagindex{/lang}</h3>
			<p class="smallFont">{lang}wbb.index.geburtstagindex.description{/lang}</p>
			<p class="smallFont">{implode from=$birthdayData item=birthday}<a href="index.php?page=User&amp;userID={@$birthday.userID}{@SID_ARG_2ND}">{@$birthday.username} ({@$birthday.userOption})</a> {/implode}</p>
        </div>
	</div>
{/if}