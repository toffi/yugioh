{include file="documentHeader"}
<head>
	<title>{lang}wcf.guthaben.premium.title{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{include file='header' sandbox=false}

<div id="main">
	
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
		<li><a href="index.php?page=guthabenMain{@SID_ARG_2ND}"><img src="{icon}guthabenMainS.png{/icon}" alt="" /> <span>{lang}wcf.guthaben.pagetitle{/lang}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<img src="{icon}guthabenForbesL.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.guthaben.premium.title{/lang}</h2>
		</div>
	</div>

    {if $userMessages|isset}{@$userMessages}{/if}
    
	{if $errorField}
		<p class="error">{lang}wcf.global.form.error{/lang}</p>
	{/if}

	<div class="tabMenu">
		<ul>
			{foreach from=$parentItems item=item}
				<li{if $item.menuItemLink == $activeParent} class="activeTabMenu"{/if}><a href="index.php?page=guthabenMain&amp;action={$item.menuItemLink}{@SID_ARG_2ND}">{if $item.menuItemIcon}<img src="{$item.menuItemIcon}" alt="" /> {/if}<span>{lang}{@$item.menuItem}{/lang}</span></a></li>
			{/foreach}
		</ul>
	</div>
		
	<div class="subTabMenu">
		<div class="containerHead">
			<p></p>
		</div>
	</div>

    <form method="post" action="index.php?form=GuthabenPremium">
        <div class="border tabMenuContent">
            <div class="container-1">
                {foreach from=$groups item=group}
                    <fieldset>
                        <legend><strong>{lang}{$group.groupName}{/lang}</strong></legend>
                        {lang isMember=$group.isMember}wcf.guthaben.premium.status{/lang}
                        {if $group.groupDescription}
                            <div class="formRadio formGroup" id="groupDescription{$group.groupID}">
                                <div class="formGroupLabel">
                                    <label>{lang}wcf.guthaben.premium.exclusive{/lang}</label>
                                </div>
                                <div class="formGroupField">
                                    <fieldset>
                                        <legend>{lang}wcf.guthaben.premium.exclusive{/lang}</legend>
                                        <div class="formField">{lang}{@$group.groupDescription}{/lang}</div>
                                    </fieldset>
                                </div>
                            </div>
                        {/if}
                        <div class="formRadio formGroup" id="group{$group.groupID}Div">
                            <div class="formGroupLabel">
                                <label>{lang}wcf.guthaben.premium.price{/lang}</label>
                            </div>
                            <div class="formGroupField">
                                <fieldset>
                                    <legend>{lang}wcf.guthaben.premium.price{/lang}</legend>
                                    <div class="formField">
                                        <ul class="formOptionsLong">
                                            <li><label><input name="group{$group.groupID}" checked="checked" value="0" type="radio" /> {lang}wcf.guthaben.premium.notBuy{/lang}</label></li>
                                            {foreach from=$group.groupYears item=money key=years}
                                                {if !$money|empty}<li><label><input name="group{$group.groupID}" value="{$years+1}" type="radio" /> {#$money} {lang}wcf.guthaben.currency{/lang} ({lang}wcf.guthaben.premium.buy{/lang})</label></li>{/if}
                                            {/foreach}
                                        </ul>
                                    </div>
                                    <div class="formFieldDesc">
                                        <p>{lang}wcf.guthaben.premium.runtime{/lang}</p>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </fieldset>
                 {/foreach}
             </div>
        </div>
        <div class="formSubmit">
			<input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
			<input type="reset" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
        </div>
    		
    </form>
</div>

{include file='footer' sandbox=false}
</body>
</html>