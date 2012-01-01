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
        <li><a href="index.php?page=ECPNews{@SID_ARG_2ND}"><img src="{RELATIVE_WBB_DIR}icon/ecpS.png" alt="" /> <span>{lang}wcf.ecp.title.short{/lang}</span></a> &raquo;</li>
        <li><a href="index.php?form=EcpAdminDetail&eventID={$eventID}&akt={$akt}{@SID_ARG_2ND}"><img src="{icon}editS.png{/icon}" alt="" /> <span>{lang}wcf.ecp.round.ergebedit{/lang}</span></a> &raquo;</li>
	</ul>

	<div class="mainHeadline">
        <img src="{icon}editM.png{/icon}" alt="" />
		<div class="headlineContainer">
    		<h2>{lang}wcf.ecp.round.ergebedit{/lang}</h2>
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

    <div class="border" style="width: 50%; margin: 0px auto;">
       	<form method="post" action="index.php?form=EcpAdminDetail&amp;eventID={$eventID}&amp;akt={$akt}{SID_ARG_2ND_NOT_ENCODED}">
                        
            <table class="tableList">
                <thead>
                    <tr class="tableHead">
                        <th><span class="emptyHead">{lang}wcf.ecp.round.home{/lang}</span></th>
                        <th><span class="emptyHead"></th>
                        <th><span class="emptyHead">{lang}wcf.ecp.round.guest{/lang}</span></th>
                        <th><span class="emptyHead"></span></th>
                   </tr>
                </thead>
                <tbody>
                    {foreach from=$gamers item=gamer}
                        <tr class="container-{cycle values='1,2'}">
                        	<td>{if $gamer.userID1 != 0}<a href="index.php?page=User&amp;userID={$gamer.userID1}{@SID_ARG_2ND}" title="{lang username=$gamer.gamer1}wcf.user.viewProfile{/lang}">{@$gamer.gamer1}</a>{else}{lang}wcf.ecp.round.freilos{/lang}{/if}</td>
                        	<td>:</td>
                        	<td>{if $gamer.userID2 != 0}<a href="index.php?page=User&amp;userID={$gamer.userID2}{@SID_ARG_2ND}" title="{lang username=$gamer.gamer2}wcf.user.viewProfile{/lang}">{@$gamer.gamer2}</a>{else}{lang}wcf.ecp.round.freilos{/lang}{/if}</td>
                            <th><input type="text" value="{$gamer.scoreID1}" size="2" maxlength="1" name="score[{$gamer.userID1}]" /> : <input type="text" value="{$gamer.scoreID2}" size="2" maxlength="1" name="score[{$gamer.userID2}]" /></th>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
                    
            <div class="formSubmit">
            	<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
      			<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
       		</div>
      	</form>
    </div>
</div>
{include file='footer'}
</body>
</html>