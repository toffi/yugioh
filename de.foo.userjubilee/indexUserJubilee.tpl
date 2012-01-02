{if $this->user->getPermission('user.board.canViewUserjubilee')}
	<div class="container-1">
		<div class="containerIcon"><img src="{icon}userAddM.png{/icon}" alt="" /></div>
		<div class="containerContent">
			<h3>{lang}wbb.index.userjubilee{/lang}</h3>
			<p class="smallFont">{lang}wbb.index.userjubilee.description{/lang}</p>
			<p class="smallFont">
				{foreach from=$jubileeData item=data}
					<a href="index.php?page=User&amp;userID={@$data.userID}{@SID_ARG_2ND}">{@$data.username} ({$data.years} {if $data.years == 1}{lang}wbb.index.userjubilee.year{/lang}{else}{lang}wbb.index.userjubilee.years{/lang}{/if})</a>
				{/foreach}
			</p>
		</div>
	</div>
{/if}