{include file="documentHeader"}
<head>
	<title>{lang}wcf.ecp.acp.tittle{/lang} - {lang}{PAGE_TITLE}{/lang}</title>

	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/AjaxRequest.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/Suggestion.class.js"></script>
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
            <li><a href="index.php?form=EcpEventAdd{@SID_ARG_2ND}"><img src="{icon}addM.png{/icon}" alt="" /><span>{lang}wcf.ecp.acp.newevent{/lang}</span></a></li>
            <li class="activeTabMenu"><a href="index.php?form=EcpEventEdit{@SID_ARG_2ND}"><img src="{icon}editM.png{/icon}" alt="" /><span>{lang}wcf.ecp.acp.editevent{/lang}</span></a></li>
        </ul>
    </div>
    <div class="subTabMenu">
     	<div class="containerHead">
            <ul>
                {foreach from=$events item=event}
                    <li{if $event.eventID == $eventID} class="activeSubTabMenu"{/if}><a href="index.php?form=EcpEventEdit&amp;eventID={$event.eventID}{@SID_ARG_2ND}"><span>{$event.eventName}</span></a></li>
                {/foreach}
            </ul> 
        </div>
    </div>
    <div class="border">
        <div class="layout-2">
    		<div class="container-1 column first">
 	      		<div class="columnInner">
    				<div class="contentBox">

                        {if !$eventID|empty}
                            <div class="largeButtons">
                                {if $status < 3 && $anzahl == $gamers|count}
            						<ul><li><a href="index.php?action=EcpEventStart&amp;eventID={$eventID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.ecp.admin.startevent{/lang}"><img src="{RELATIVE_WBB_DIR}icon/startM.png" alt="" /><span>{lang}wcf.ecp.admin.startevent{/lang}</span></a></li></ul>
                                {/if}
                            </div>
                        	<form method="post" action="index.php?form=EcpEventEdit&amp;eventID={$eventID}{SID_ARG_2ND}">
                        		<fieldset>
                        			<legend>{lang}wcf.ecp.acp.editevent{/lang} <a href="index.php?action=EcpTourneyDelete&amp;eventID={$eventID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.ecp.acp.delete.sure{/lang}')"><img src="{icon}deleteS.png{/icon}" alt="{lang}wcf.ecp.acp.delete{/lang}" /></a></legend>
                                            
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
                                            {* In der Anmeldephase*}
                                            {if $status < 3}
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
                                            {/if}
                        
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
                                            {* In der Anmeldephase*}
                                            {if $status < 3}
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
                                            {/if}

                                                {* Total Ranking *}
                        					<div class="formElement{if $errorField == 'totalRanking'} formError{/if}">
                        						<div class="formFieldLabel">
                        							<label for="totalRanking">{lang}wcf.ecp.totalRanking{/lang}</label>
                        						</div>
                        						<div class="formField">
                                                    <input type="checkbox" value="1" name="totalRanking" id="totalRanking"{if $totalRanking == 1} checked="checked"{/if} />
                        						</div>
                        						<div class="formFieldDesc">{lang}wcf.ecp.acp.anzahl.description{/lang}</div >
                        					</div>
                                        </fieldset>

                                {* Spielernamen *}
                                {* In der Anmeldephase*}
                                {if $status < 3}
                            		<fieldset>
                            			<legend>{lang}wcf.ecp.acp.gamer.insert{/lang}</legend>
    
                                        {section name=gamerNr loop=$anzahl step=1 start=0}
                                			<div class="formElement{if $errorField == 'gamer'|concat:$gamerNr} formError{/if}">
                                                <div class="formFieldLabel">
                                					<label for="gamer{$gamerNr}">{#$gamerNr+1}. {lang}wcf.ecp.acp.gamer.username{/lang}{$errorField|truncate:5}</label>
                                				</div>
                                				<div class="formField">
                                                    <input type="text" class="inputText" id="gamer{$gamerNr}" name="gamer[]" value="{if $gamers.$gamerNr|isset}{$gamers.$gamerNr.username}{/if}" maxlength="100" />
                                                    <script type="text/javascript">
            												suggestion.setSource('index.php?page=PublicUserSuggest{SID_ARG_2ND}');
            												suggestion.init('gamer{$gamerNr}');
                                                    </script>
                                    				{if $errorField == "gamer"|concat:$gamerNr}
                                    					<p class="innerError">
                                    						{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}
                                                            {elseif $errorType == 'notExist'}{lang}wcf.ecp.acp.gamer.username.notExist{/lang}
                                                            {elseif $errorType == 'double'}{lang}wcf.ecp.acp.gamer.username.double{/lang}{/if}
                                    					</p>
                                    				{/if}
                   					            </div>
                                            </div>
                                        {/section}
                                    </fieldset>
                                {/if}

                                {* Spieler austauschen *}
                                {* In der Spielphase *}
                                {if $status == 3}
                            		<fieldset>
                            			<legend>{lang}wcf.ecp.admin.changeplayer{/lang}</legend>
                                            <div class="formElement">
                                                <div class="formField">
                                                
                                                    {append var=countGamers value=$gamers|count}
                                                    {append var=lastGameday value=$countGamers*2-2}
                                                    <div class="border">
                                                    <table class="tableList">
                        								<thead>
                        									<tr class="tableHead">
                        										<th><span class="emptyHead">Name</span></th>
                        										<th><span class="emptyHead">Austauschen mit</span></th>
                        										<th><span class="emptyHead">Austauschen ab</span></th>
                        										<th><span class="emptyHead">Spiele +/-</span></th>
                       											<th><span class="emptyHead">S +/-</span></th>
                       											<th><span class="emptyHead">N +/-</span></th>
                                                                <th><span class="emptyHead">Punkte +/-</span></th>
                        								    </tr>
                        								</thead>
                        								<tbody>
                                                            {foreach from=$gamers item=gamer key=$key}
                                                                <tr class="container-{cycle values='1,2'}">
                                                                    <th>{$gamer.username}</th>
                                                                    <th><input type="text" name="username[{$gamer.userID}]" id="username{$key}" />
                                                                        <script type="text/javascript">
                                												suggestion.setSource('index.php?page=PublicUserSuggest{SID_ARG_2ND}');
                                												suggestion.init('username{$key}');
                                                                        </script>
                                                                    </th>
                                                                    <th>
                                                                        <select size="1" name="gameday[{$gamer.userID}]">
                                                                            {section name=ab loop=$lastGameday+1 step=1 start=0}
                                                                        	   <option value="{$ab}">{$ab}</option>
                                                                            {/section}
                                                                        </select>
                                                                    </th>
                                                                    <th><input type="text" size="2" maxlength="2" name="games[{$gamer.userID}]" /></th>
                                                                    <th><input type="text" size="2" maxlength="2" name="wins[{$gamer.userID}]" /></th>
                                                                    <th><input type="text" size="2" maxlength="2" name="looses[{$gamer.userID}]" /></th>
                                                                    <th><input type="text" size="2" maxlength="2" name="points[{$gamer.userID}]" /></th>
                                                                </tr>
                                                            {/foreach}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {* {foreach from=$gamers item=gamer}
                                            <div class="formElement">
                                                <div class="formField">
                                                    <a href="index.php?action=EcpKickUser&amp;eventID={$eventID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang username=$gamer.username}wcf.ecp.acp.user.delete.sure{/lang}')"><img src="{icon}deleteS.png{/icon}" alt="{lang}wcf.ecp.acp.delete{/lang}" /></a> {$gamer.username}
                                                </div>
                                            </div>
                                        {/foreach}*}
                                    </fieldset>
                               {/if}
                                
                                {* Absenden *}
                        		<div class="formSubmit">
                        			<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
                        			<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
                        		</div>
                            </form>
                        {else}
                            <span>{lang}wcf.ecp.acp.eventEdit.noSelected{/lang}</span>
                        {/if}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{include file='footer'}
</body>
</html>