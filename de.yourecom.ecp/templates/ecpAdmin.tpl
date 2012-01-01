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
    
        <div class="tabMenu">
            <ul>
                <li class="activeTabMenu"><a href="index.php?form=EcpAdminButtonEdit{@SID_ARG_2ND}"><img src="{RELATIVE_WBB_DIR}icon/buttonM.png" alt="" /><span>{lang}wcf.ecp.acp.editbutton{/lang}</span></a></li>
                <li><a href="index.php?form=EcpEventAdd{@SID_ARG_2ND}"><img src="{icon}addM.png{/icon}" alt="" /><span>{lang}wcf.ecp.acp.newevent{/lang}</span></a></li>
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
                            <form method="post" action="index.php?form=ecpAdminButtonEdit{SID_ARG_2ND_NOT_ENCODED}">
                            
                    		      <fieldset>
                    					<legend>{lang}wcf.ecp.acp.editbutton{/lang}</legend>
                                        
                                        {section name=counter start=0 loop=5}
                        					<div class="formElement">
                        						<div class="formFieldLabel">
                        							<label for="button{$counter}">{lang count=$counter}wcf.ecp.acp.button{/lang}</label>
                        						</div>
                        						<div class="formField">
                        							<select size="1" name="button[]" id="button{$counter}">
                                                        <option value="0" selected="selected">---</option>
                                                        {foreach from=$events item=event}
                                                            <option value="{$event.eventID}"{if $headerButtons.$counter.eventID|isset && $event.eventID == $headerButtons.$counter.eventID} selected="selected"{/if}>{$event.eventName}</option>
                                                        {/foreach}
                                                    </select>
                        						</div>
                        						<div class="formFieldDesc">{lang}wcf.ecp.acp.button.description{/lang}</div >
                        					</div>
                                        {/section}
                                        
                    				</fieldset>

                				<div class="formSubmit">
                
                					<input accesskey="s" value="Absenden" type="submit" />
                					<input accesskey="r" value="Zurücksetzen" type="reset" />
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