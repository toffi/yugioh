{include file="documentHeader"}
<head>
	<title>{lang}wcf.clique.edit{/lang} - {lang}{PAGE_TITLE}{/lang}</title>

	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabbedPane.class.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		var INLINE_IMAGE_MAX_WIDTH = {@INLINE_IMAGE_MAX_WIDTH};
		//]]>
	</script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ImageResizer.class.js"></script>
	{if $canUseBBCodes}{include file="wysiwyg"}{/if}
	{include file='multiQuote'}
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

	<div class="mainHeadline">
		<img src="{icon}cliqueEditL.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.clique.edit{/lang}</h2>
			<p>{lang}wcf.clique.edit.description{/lang}</p>
		</div>
	</div>

	{if $userMessages|isset}{@$userMessages}{/if}

	{if $errorField}
		<p class="error">{lang}wcf.global.form.error{/lang}</p>
	{/if}

	<div class="tabMenu">
		<ul>
			<li><a href="index.php?page=CliqueDetail&amp;cliqueID={$cliqueID}{@SID_ARG_2ND}"><img src="{icon}backM.png{/icon}" alt="" /> <span>{lang}wcf.clique.general.back{/lang}</span></a></li>
			{if $cliquePermissions->getCliquePermission('canEditClique') || $this->user->getPermission('mod.clique.general.canEditEveryClique')}<li class="activeTabMenu"><a href="index.php?form=CliqueEdit&amp;cliqueID={$cliqueID}{@SID_ARG_2ND}"><img src="{icon}cliqueEditM.png{/icon}" alt="" /> <span>{lang}wcf.clique.edit{/lang}</span></a></li>{/if}
			{if $cliquePermissions->getCliquePermission('canEditRights') || $cliquePermissions->getCliquePermission('canInviteUsers') || $cliquePermissions->getCliquePermission('canAttendInvites') || $this->user->getPermission('mod.clique.general.canAdministrateEveryClique')}<li><a href="index.php?form=CliqueAdministrate&amp;cliqueID={$cliqueID}{@SID_ARG_2ND}"><img src="{icon}cogM.png{/icon}" alt="" /> <span>{lang}wcf.clique.administrate{/lang}</span></a></li>{/if}
		</ul>
	</div>
	<div class="subTabMenu">
		<div class="containerHead">
		</div>
	</div>

	<form method="post" action="index.php?form=CliqueEdit&amp;cliqueID={$cliqueID}{SID_ARG_2ND_NOT_ENCODED}" enctype="multipart/form-data">
		<div class="border tabMenuContent">
			<div class="container-1">

				<fieldset>
					<legend>{lang}wcf.clique.add.general{/lang}</legend>
					<div class="formElement{if $errorField == 'name'} formError{/if}">
						<div class="formFieldLabel">
							<label for="name">{lang}wcf.clique.add.name{/lang}</label>
						</div>
						<div class="formField">
							<input type="text" class="inputText" id="name" name="name" value="{$name}" />
							{if $errorField == 'name'}
								<p class="innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}
										{elseif $errorType == 'stringTooLong'}{lang}wcf.clique.add.name.error{/lang}.
									{/if}
								</p>
							{/if}
						</div>
						<div class="formFieldDesc">{lang}wcf.clique.add.name.description{/lang}</div >
					</div>

					<div class="formElement{if $errorField == 'status'} formError{/if}">
						<div class="formFieldLabel">
							<label for="status0">{lang}wcf.clique.add.status{/lang}</label>
						</div>
						<div class="formField">
							{foreach from=$statusArray item=statusLoop key=key}
								<input name="status" type="radio" id="status{$key}" value="{$key}"{if $status == $key} checked="checked"{/if} /> {$statusLoop}<br />
							{/foreach}
							{if $errorField == 'status'}
								<p class="innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								</p>
							{/if}
						</div>
						<div class="formFieldDesc">{lang}wcf.clique.add.status.description{/lang}</div >
					</div>

					{if $categoriesArray|count > 0}
						<div class="formElement{if $errorField == 'categories'} formError{/if}">
							<div class="formFieldLabel">
								<label for="categories">{lang}wcf.clique.add.categories{/lang}</label>
							</div>
							<div class="formField">
							<select name="categorie">
								{foreach from=$categoriesArray item=categorie}
										<option value="{$categorie.categoryID}"{if $categorie.categoryID == $categorieID} selected{/if}>{$categorie.category}</option>
								{/foreach}
							</select>
								{if $errorField == 'categories'}
									<p class="innerError">
										{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
									</p>
								{/if}
							</div>
							<div class="formFieldDesc">{lang}wcf.clique.add.categories.description{/lang}</div >
						</div>
					{/if}
				</fieldset>

				<fieldset>
					<legend>{lang}wcf.clique.add.picture{/lang}</legend>
					<div class="formElement{if $errorField == 'upload'} formError{/if}">
						<div class="formFieldLabel">
							<label for="upload">{lang}wcf.clique.add.picture.upload{/lang}</label>
						</div>
						<div class="formField">
							<input name="upload" id="upload" size="20" type="file"  class="inputText" />
						</div>
						<div class="formFieldDesc">{lang}wcf.clique.add.picture.upload.description{/lang}</div >
						{if $errorField == 'upload'}
							<div class="innerError">
								{if $errorType|is_array}
									{foreach from=$errorType item=error}
										<p>
											{if $error.errorType == 'illegalExtension'}{lang}wcf.clique.add.error.illegalExtension{/lang}{/if}
											{if $error.errorType == 'tooLarge'}{lang}wcf.clique.add.error.tooLarge{/lang}{/if}
											{if $error.errorType == 'badImage'}{lang}wcf.clique.add.error.badImage{/lang}{/if}
										</p>
									{/foreach}
								{elseif $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</div>
						{/if}
					</div>

					<div class="formElement{if $errorField == 'pictureURL'} formError{/if}" id="pictureURLDiv">
						<div class="formFieldLabel">
							<label for="pictureURL">{lang}wcf.clique.add.picture.download{/lang}</label>
						</div>
						<div class="formField">
							<input type="text" class="inputText" name="pictureURL" value="{$pictureURL}" id="pictureURL" />
							{if $errorField == 'pictureURL'}
								<p class="innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
									{if $errorType == 'downloadFailed'}{lang}wcf.user.avatar.error.avatarURL.downloadFailed{/lang}{/if}
									{if $errorType == 'badAvatar'}{lang}wcf.user.avatar.error.badAvatar{/lang}{/if}
									{if $errorType == 'notAllowedExtension'}{lang}wcf.user.avatar.error.notAllowedExtension{/lang}{/if}
									{if $errorType == 'tooLarge'}{lang}wcf.user.avatar.error.tooLarge{/lang}{/if}
									{if $errorType == 'copyFailed'}{lang}wcf.user.avatar.error.copyFailed{/lang}{/if}
								</p>
							{/if}
						</div>
					</div>
				</fieldset>


				<fieldset>
                    <legend>{lang}wcf.clique.add.description{/lang}</legend>
					<div class="formElement">
						<div class="formElement">
							<div class="formFieldLabel">
								<label for="shortDescription">{lang}wcf.clique.add.shortDescription{/lang}</label>
							</div>
							<div class="formField">
								<input name="shortDescription" type="text" id="shortDescription" class="inputText" value="{$shortDescription}" />
							</div>
							<div class="formFieldDesc">{lang}wcf.clique.add.shortDescription.description{/lang}</div >
						</div>

						<div class="editorFrame formElement{if $errorField == 'text'} formError{/if}" id="textDiv">
							<textarea name="text" id="text" rows="15" cols="40" tabindex="{counter name='tabindex'}">{$text}</textarea>
							{if $errorField == 'text'}
								<p class="innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
									{if $errorType == 'censoredWordsFound'}{lang}wcf.message.error.censoredWordsFound{/lang}{/if}
								</p>
							{/if}
						</div>
						{include file='messageFormTabs'}
					</div>
				</fieldset>
			</div>
		</div>

		<div class="formSubmit">
			<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
			<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		</div>
	</form>
	</div>
{include file='footer'}
</body>
</html>