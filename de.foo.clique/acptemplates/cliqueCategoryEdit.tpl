{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/smileyAddL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.clique.categorie.edit{/lang}</h2>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset && $success > 0}
	<p class="success">{lang}wcf.acp.clique.categorie.success{/lang}</p>
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=CliqueCategoryList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/smileyM.png" alt="" title="{lang}wcf.acp.clique.categorie.show{/lang}" /> <span>{lang}wcf.acp.clique.categorie.show{/lang}</span></a></li></ul>
	</div>
</div>
<form method="post" action="index.php?form=CliqueCategoryEdit&amp;categoryID={$categoryID}">
	<div class="border content">
		<div class="container-1">
			<fieldset>
				<legend>{lang}wcf.acp.clique.categorie.general{/lang}</legend>

				<div class="formElement{if $errorField == 'category'} formError{/if}" id="categoryDiv">
					<div class="formFieldLabel">
						<label for="category">{lang}wcf.acp.clique.categorie{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="category" id="category" value="{$category}" />
						{if $errorField == 'category'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="categoryHelpMessage">
						{lang}wcf.acp.clique.categorie.description{/lang}
					</div>
				</div>

				<div class="formElement" id="showOrderDiv">
					<div class="formFieldLabel">
						<label for="showOrder">{lang}wcf.acp.clique.categorie.showorder{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="showOrder" id="showOrder" value="{@$showOrder}" />
					</div>
					<div class="formFieldDesc hidden" id="showOrderHelpMessage">
						{lang}wcf.acp.clique.categorie.showorder.description{/lang}
					</div>
				</div>

			</fieldset>

		</div>
	</div>

	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
 		{@SID_INPUT_TAG}
 	</div>
</form>

{include file='footer'}