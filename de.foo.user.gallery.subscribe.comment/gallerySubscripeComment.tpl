<li class="options" id="SubscripeCommentButton">
	<a href="index.php?action=GalleryCommentSubscribe{if !$isSubscription|empty}Delete{/if}&photoID={$photoID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" id="SubscripeComment" title="{lang}wcf.user.gallery.comment.{if !$isSubscription|empty}un{/if}subscripe{/lang}">
		<img src="{icon}{if !$isSubscription|empty}un{/if}subscribeS.png{/icon}" alt="" />
		<span>{lang}wcf.user.gallery.comment.{if !$isSubscription|empty}un{/if}subscripe{/lang}</span>
	</a>
</li>