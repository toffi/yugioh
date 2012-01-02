{include file="documentHeader"}
<head>
	<title>{lang}wcf.clique.comment{/lang} - {lang}{PAGE_TITLE}{/lang}</title>

	{include file='headInclude' sandbox=false}

	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabbedPane.class.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		var INLINE_IMAGE_MAX_WIDTH = {@INLINE_IMAGE_MAX_WIDTH};
		//]]>
	</script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ImageResizer.class.js"></script>

	{include file='multiQuote'}
	{if $canUseBBCodes}{include file="wysiwyg"}{/if}
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
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
		<li><a href="index.php?page=CliqueOverview{@SID_ARG_2ND}"><img src="{icon}cliqueS.png{/icon}" alt="" width="16" height="16" /> <span>{lang}wcf.clique.general.name.singulary{/lang}</span></a> &raquo;</li>
	</ul>

	{if $userMessages|isset}{@$userMessages}{/if}

	{include file="cliqueDetailHeader"}

	<form method="post" action="index.php?form=CliqueEditComment&amp;cliqueID={$cliqueID}&amp;commentID={$comment.commentID}{@SID_ARG_2ND}">
		<div class="border content">
			<div class="container-1">
				<h3 class="subHeadline">{lang}wcf.clique.comment.clique{/lang}</h3>
				<div class="contentHeader">
					<div class="largeButtons">
						<ul>
							<li><a href="index.php?page=CliqueComments&amp;cliqueID={$cliqueID}{@SID_ARG_2ND}#profileContent" title="{lang}wcf.clique.comment{/lang}"><img src="{icon}guestbookM.png{/icon}" alt="" /> <span>{lang}wcf.clique.comment{/lang}</span></a></li>
						</ul>
					</div>
				</div>
				
				{if $preview|isset}
					<div class="message content">
						<div class="messageInner container-1">
							<div class="messageHeader">
								<h3>{lang}wcf.clique.comment.preview{/lang}</h3>
							</div>
							<div class="messageBody">
								<div>{@$previewText}</div>
							</div>
						</div>
					</div>
				{/if}

				<fieldset>
					<legend>{lang}wcf.clique.comment.singulary{/lang}</legend>

					<div class="editorFrame formElement{if $errorField == 'text'} formError{/if}" id="textDiv">
						<div class="formFieldLabel">
							<label for="text">{lang}wcf.clique.comment.singulary{/lang}</label>
						</div>

						<div class="formField">
							<textarea name="text" id="text" rows="15" cols="40" tabindex="{counter name='tabindex'}">{$text}{$comment.message}</textarea>
							{if $errorField == 'text'}
								<p class="innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
									{if $errorType == 'censoredWordsFound'}{lang}wcf.message.error.censoredWordsFound{/lang}{/if}
								</p>
							{/if}
						</div>

					</div>
					{include file='messageFormTabs'}
				</fieldset>

			</div>
		</div>

		<div class="formSubmit">
			<input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" tabindex="{counter name='tabindex'}" />
			<input type="submit" name="preview" accesskey="p" value="{lang}wcf.global.button.preview{/lang}" tabindex="{counter name='tabindex'}" />
			<input type="reset" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" tabindex="{counter name='tabindex'}" />
			{@SID_INPUT_TAG}
		</div>
	</form>
</div>

{include file='footer' sandbox=false}
</body>
</html>