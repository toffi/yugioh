{include file="documentHeader"}
<head>
	<title>{lang}wcf.ecp.acp.tittle{/lang} - {lang}{PAGE_TITLE}{/lang}</title>

	{include file='headInclude' sandbox=false}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='header' sandbox=false}
{include file='ecpHeader'}

<div id="main">
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
	</ul>

	<div class="mainHeadline">
        <img src="{icon}ecpheader.png{/icon}" alt="" />
		<div class="headlineContainer">
    		<h2>{lang}wcf.ecp.acp.tittle{/lang}</h2>
    		<p>{lang}wcf.ecp.acp.tittle.description{/lang}</p>
		</div>
    </div>
 
	{if $userMessages|isset}{@$userMessages}{/if}

    {if $success|isset}
        <p class="success">{lang}wcf.ecp.acp.eventAdd.success{/lang}</p>
    {/if}
    {if $errorField}
		<p class="error">{lang}wcf.global.form.error{/lang}</p>
	{/if}

    <div class="tabMenu">
        <ul>
            <li><a href="index.php?form=EcpAdminButtonEdit{@SID_ARG_2ND}"><img src="{RELATIVE_WBB_DIR}icon/buttonM.png" alt="" /><span>{lang}wcf.ecp.acp.editbutton{/lang}</span></a></li>
            <li class="activeTabMenu"><a href="index.php?form=EcpEventAdd{@SID_ARG_2ND}"><img src="{icon}addM.png{/icon}" alt="" /><span>{lang}wcf.ecp.acp.newevent{/lang}</span></a></li>
            <li><a href="index.php?form=EcpEventEdit{@SID_ARG_2ND}"><img src="{icon}editM.png{/icon}" alt="" /><span>{lang}wcf.ecp.acp.editevent{/lang}</span></a></li>
        </ul>
    </div>
    <div class="subTabMenu">
     	<div class="containerHead"></div>
    </div>
    <div class="border">
        <div class="layout-2">
    		<div class="container-1 column first">
 	      		<div class="columnInner">
    				<div class="contentBox">

                    	<form method="post" action="index.php?form=EcpEventAdd{SID_ARG_2ND_NOT_ENCODED}">
                    		<fieldset>
                    			<legend>{lang}wcf.ecp.acp.newevent{/lang}</legend>
                                        
                                            {* Eventname *}
                    					<div class="formElement{if $errorField == 'eventname'} formError{/if}">
                    						<div class="formFieldLabel">
                    							<label for="eventname">{lang}wcf.ecp.acp.eventname{/lang}</label>
                    						</div>
                    						<div class="formField">
                                                <input type="text" class="inputText" id="eventname" name="eventname" value="{$eventname}" maxlength="100" />
                    							{if $errorField == 'eventname'}
                    								<p class="innerError">
                    									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                    								</p>
                    							{/if}
                    						</div>
                    						<div class="formFieldDesc">{lang}wcf.ecp.acp.eventname.description{/lang}</div >
                    					</div>
                    
                                            {* Eventabkürzung *}
                    					<div class="formElement{if $errorField == 'nameAbbreviation'} formError{/if}">
                    						<div class="formFieldLabel">
                    							<label for="nameAbbreviation">{lang}wcf.ecp.acp.eventabk{/lang}</label>
                    						</div>
                    						<div class="formField">
                                                <input type="text" class="inputText" id="nameAbbreviation" name="nameAbbreviation" value="{$nameAbbreviation}" maxlength="100" />
                    							{if $errorField == 'nameAbbreviation'}
                    								<p class="innerError">
                    									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                    								</p>
                    							{/if}
                    						</div>
                    						<div class="formFieldDesc">{lang}wcf.ecp.acp.eventabk.description{/lang}</div >
                    					</div>
                    
                                            {* Eventart *}
                    					<div class="formElement{if $errorField == 'eventart'} formError{/if}">
                    						<div class="formFieldLabel">
                    							<label for="eventart">{lang}wcf.ecp.acp.eventart{/lang}</label>
                    						</div>
                    						<div class="formField">
                                                <select size="1" name="eventart" id="eventart">
                                                    {foreach from=$eventArten item=art key=$eventArtID}
                                                        <option value="{$eventArtID}" {if $eventArt == $eventArtID} selected="selected"{/if}>{$art}</option>
                                                    {/foreach}
                                                </select>
                    							{if $errorField == 'eventart'}
                    								<p class="innerError">
                    									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                    								</p>
                    							{/if}
                    						</div>
                    						<div class="formFieldDesc">{lang}wcf.ecp.acp.eventart.description{/lang}</div >
                    					</div>
                    
                                            {* Eventforum *}
                    					<div class="formElement{if $errorField == 'board'} formError{/if}">
                    						<div class="formFieldLabel">
                    							<label for="eventBoard">{lang}wcf.ecp.acp.eventforum{/lang}</label>
                    						</div>
                    						<div class="formField">
                                                <select size="1" name="eventBoard" id="eventBoard">
                                                    {foreach from=$boards item=board key=$key}
                                                        <option value="{$board.boardID}"{if $eventBoard == $board.boardID} selected="selected"{/if}>{lang}{$board.title}{/lang}</option>
                                                    {/foreach}
                                                </select>
                    							{if $errorField == 'eventBoard'}
                    								<p class="innerError">
                    									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                    								</p>
                    							{/if}
                    						</div>
                    						<div class="formFieldDesc">{lang}wcf.ecp.acp.eventforum.description{/lang}</div >
                    					</div>
                    
                                            {* Eventbetreuer *}
                    					<div class="formElement{if $errorField == 'eventbetreuer'} formError{/if}">
                    						<div class="formFieldLabel">
                    							<label for="eventbetreuer">{lang}wcf.ecp.acp.eventbetreuer{/lang}</label>
                    						</div>
                    						<div class="formField">
                                                <select size="1" name="eventbetreuer" id="eventbetreuer">
                                                    {foreach from=$contacts item=contacts}
                                                        <option value="{$contacts.userID}"{if $eventbetreuer == $contacts.userID} selected="selected"{/if}>{$contacts.username}</option>
                                                    {/foreach}
                                                </select>
                    							{if $errorField == 'eventbetreuer'}
                    								<p class="innerError">
                    									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                    								</p>
                    							{/if}
                    						</div>
                    						<div class="formFieldDesc">{lang}wcf.ecp.acp.eventbetreuer.description{/lang}</div >
                    					</div>
                    
                                            {* Regeln *}
                    					<div class="formElement{if $errorField == 'rulesurl'} formError{/if}">
                    						<div class="formFieldLabel">
                    							<label for="rulesurl">{lang}wcf.ecp.acp.rulesurl{/lang}</label>
                    						</div>
                    						<div class="formField">
                                                <input type="text" class="inputText" id="rulesurl" name="rulesurl" value="{$rulesurl}" maxlength="100" />
                    							{if $errorField == 'rulesurl'}
                    								<p class="innerError">
                    									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}
                                                        {elseif $errorType == 'invalidUrl'}{lang}wcf.user.option.error.validationFailed{/lang}
                                                        {/if}
                    								</p>
                    							{/if}
                    						</div>
                    						<div class="formFieldDesc">{lang}wcf.ecp.acp.rulesurl.description{/lang}</div >
                    					</div>
                    
                                            {* Teilnehmerzahl *}
                    					<div class="formElement{if $errorField == 'anzahl'} formError{/if}">
                    						<div class="formFieldLabel">
                    							<label for="anzahl">{lang}wcf.ecp.acp.anzahl{/lang}</label>
                    						</div>
                    						<div class="formField">
                                                <input type="text" class="inputText" id="anzahl" name="anzahl" value="{$anzahl}" maxlength="100" />
                    							{if $errorField == 'anzahl'}
                    								<p class="innerError">
                    									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                    								</p>
                    							{/if}
                    						</div>
                    						<div class="formFieldDesc">{lang}wcf.ecp.acp.anzahl.description{/lang}</div >
                    					</div>

                                            {* Total Ranking *}
                    					<div class="formElement{if $errorField == 'totalRanking'} formError{/if}">
                    						<div class="formFieldLabel">
                    							<label for="totalRanking">{lang}wcf.ecp.totalRanking{/lang}</label>
                    						</div>
                    						<div class="formField">
                                                <input type="checkbox" value="1" name="totalRanking"{if $totalRanking == 1} checked="checked"{/if} />
                    						</div>
                    						<div class="formFieldDesc">{lang}wcf.ecp.acp.anzahl.description{/lang}</div >
                    					</div>
                                    </fieldset>
                    
                    				<div class="formSubmit">
                    					<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
                    					<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
                    				</div>
                    			</form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{include file='footer'}
</body>
</html>