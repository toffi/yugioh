<fieldset>
    <legend>{lang}wcf.acp.group.premium{/lang}</legend>
    <div class="formElement{if $errorType.groupDescription|isset} formError{/if}" id="groupDescriptionDiv">
        	<div class="formFieldLabel">
        		<label for="groupDescription">{lang}wcf.acp.group.groupDescription{/lang}</label>
        	</div>
        	<div class="formField">
        		<textarea class="inputText" id="groupDescription" name="groupDescription" cols="100" rows="3">{if $groupDescription|isset}{$groupDescription}{/if}</textarea>
        	</div>
        	<div class="formFieldDesc" id="groupDescriptionHelpMessage">
        		<p>{lang}wcf.acp.group.groupDescription.description{/lang}</p>
        	</div>
    </div>

    <div class="formElement{if $errorType.groupYears|isset} formError{/if}" id="groupYearsDiv">
        {section name=i start=1 loop=GUTHABEN_PREMIUM_YEARS+1 step=1}
        	<div class="formFieldLabel">
        		<label for="groupYears{$i}">{lang year=$i}wcf.acp.group.groupYears{/lang}</label>
        	</div>
        	<div class="formField">
                {assign var=arrayKey value=$i-1}
        		<input type="text" class="inputText" id="groupYears{$i}" name="groupYears[]" value="{if $groupYears.$arrayKey|isset}{$groupYears.$arrayKey}{else}0{/if}" />
        	</div>
<<<<<<< HEAD
        	<div class="formFieldDesc" id="groupYears{$i}HelpMessage"
=======
        	<div class="formFieldDesc" id="groupYears{$i}HelpMessage">
>>>>>>> bbb65990f6f07764a9378f24986dac434ad6fb1f
        		<p>{lang}wcf.acp.group.groupYears.description{/lang}</p>
        	</div>
         {/section}
    </div>
</fieldset>