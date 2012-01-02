{include file="documentHeader"}
<head>
	<title>{lang}wcf.eventbox.title{/lang} - {PAGE_TITLE}</title>

	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabbedPane.class.js"></script>
	{if $canUseBBCodes}{include file="wysiwyg"}{/if}
	{include file='multiQuote'}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='header' sandbox=false}

<div id="main">
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{PAGE_TITLE}</span></a>&raquo;</li>
	</ul>

	<div class="mainHeadline">
		<img src="{icon}radioL.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.eventbox.title{/lang}</h2>
			<p>{lang}wcf.eventbox.title.description{/lang}</p>
		</div>
	</div>

{if $userMessages|isset}{@$userMessages}{/if}
	<div class="mainHeadline">

		<form method="post" action="index.php?form=EventboxEdit{SID_ARG_2ND_NOT_ENCODED}">
			<fieldset>
				<legend>{lang}wcf.eventbox.title{/lang}</legend>

					<div class="formElement{if $errorField == 'name'} formError{/if}">
						<div class="formFieldLabel">
							<label for="name">{lang}wcf.eventbox.deakt.description{/lang}</label>
						</div>
						<div class="formField">
							<input id="event_enable" name="event_enable" value="1" type="checkbox"{if $event_enable_show == 1} checked="checked"{/if}>
						</div>
					</div>

					<div class="formElement{if $errorField == 'name'} formError{/if}">
						<div class="formFieldLabel">
							<label for="name">{lang}wcf.acp.option.event_art.description{/lang}</label>
						</div>
						<div class="formField">
							<label><input name="event_art" value="1" type="radio"{if $event_art_show == 1} checked="checked"{/if} /> Radio</label>
							<label><input name="event_art" value="2" type="radio"{if $event_art_show == 2} checked="checked"{/if} /> Turnier</label>
						</div>
					</div>

					<div id="event_messageDiv" class="formElement">
						<div class="editorFrame formElement" id="textDiv">
							<textarea name="event_message" id="text" rows="15" cols="40" tabindex="{counter name='tabindex'}">{if $event_message_show|isset}{@$event_message_show}{/if}</textarea>
						</div>
						{assign var=showSmilies value=true}
						{assign var=showSettings value=true}
						{assign var=showAttachments value=false}
						{assign var=showPoll value=false}
						{include file="messageFormTabs"}
					</div>
					<div class="formSubmit">
						<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
						<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
					</div>
				</legend>
			</fieldset>
		</form>
	</div>
</div>

{include file='footer' sandbox=false}
</body>
</html>