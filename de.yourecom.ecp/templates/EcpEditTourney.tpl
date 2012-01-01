{include file="documentHeader"}
<head>
	<title>{PAGE_TITLE}</title>
	{include file="headInclude"}
	<link rel="stylesheet" type="text/css" media="screen" href="./style/portal.css" />
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/Suggestion.class.js"></script>
	<script src="{@RELATIVE_WCF_DIR}js/Calendar.class.js" type="text/javascript"></script>
	<script type="text/javascript">
		//<![CDATA[
			var calendar = new Calendar('{$monthList}', '{$weekdayList}', '{$startOfWeek}');
		//]]>
	</script>
</head>
<body>
{include file="header" sandbox=false}
{include file='header_ecp' sandbox=false}
	<div id="main">
	<ul class="breadCrumbs">
		<li>
			<a href="index.php?page=Index{@SID_ARG_2ND}"><img src="icon/indexS.png" alt="" /> <span>{PAGE_TITLE}</span></a> &raquo;
			<a href="index.php?page=ECPNews{@SID_ARG_2ND}"><img src="{icon}ecpS.png{/icon}" alt="" /> <span>{lang}wcf.ecp.title.short{/lang}</span></a> &raquo;
			<a href="index.php?page=ECPTourney{@SID_ARG_2ND}"><img src="{icon}DDiskGoldS.png{/icon}" alt="" /> <span>{lang}wcf.ecp.tourney.tourney{/lang}</span></a> &raquo;
		</li>
	</ul>
	<div class="mainHeadline">
		<img src="{icon}DDiskM.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.ecp.tourney.insert.name{/lang}</h2>
		</div>
	</div>
	 <!-- PN Abfrage -->
	{if $userMessages|isset}{@$userMessages}{/if}

 <!-- Formular -->
  <div class="container-1">
			<div class="containerHead">
				<div class="containerContent">
					<span>{lang}wcf.ecp.tourney.insert.name{/lang}</span>
				</div>
			</div>
			<form method="post" action="index.php?form=EcpEditTourney&eventID={$tourneyList.id}{@SID_ARG_2ND}" name="dsf">

 <!-- Name des Turniers -->
			<div class="formElement{if $errorField == 'name'} formError{/if}">
				<div class="formFieldLabel">
					<label for="name">{lang}wcf.ecp.tourney.insert.tourney.name{/lang}</label>
				</div>
				<div class="formField">
					<input type="text" name="name" id="name" class="inputText" value="{$tourneyList.name}" />
					{if $errorField == 'name'}
						<p class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
						</p>
					{/if}
				</div>
			</div>

 <!-- Kalender -->

			<div class="formGroup{if $errorField == 'deadlineTime'} formError{/if}">
				<div class="formGroupLabel">
					<label for="deadlineTime">{lang}wcf.ecp.tourney.insert.tourney.tourneydate{/lang}</label>
				</div>
				<div class="formGroupField">
					<fieldset>
						<div class="formField">
							<div class="floatedElement">
								<label for="deadlineTimeDay">{lang}wcf.global.date.day{/lang}</label>
								{htmlOptions options=$dayOptions selected=$tourneyList.deadlineTimeDay id=deadlineTimeDay name=deadlineTimeDay}
							</div>
							
							<div class="floatedElement">
								<label for="deadlineTimeMonth">{lang}wcf.global.date.month{/lang}</label>
								{htmlOptions options=$monthOptions selected=$tourneyList.deadlineTimeMonth id=deadlineTimeMonth name=deadlineTimeMonth}
							</div>
							
							<div class="floatedElement">
								<label for="deadlineTimeYear">{lang}wcf.global.date.year{/lang}</label>
								<input id="deadlineTimeYear" class="inputText fourDigitInput" type="text" name="deadlineTimeYear" value="{$tourneyList.deadlineTimeYear}" maxlength="4" />
							</div>
							
							<div class="floatedElement">
								&nbsp;
							</div>
							
							<div class="floatedElement">
								<label for="deadlineTimeHour">{lang}wcf.global.date.hour{/lang}</label>
								{htmlOptions options=$hourOptions selected=$tourneyList.deadlineTimeHour id=deadlineTimeHour name=deadlineTimeHour} :
							</div>
					
							<div class="floatedElement">
								<label for="deadlineTimeMinutes">{lang}wcf.global.date.minutes{/lang}</label>
								{htmlOptions options=$minuteOptions selected=$tourneyList.deadlineTimeMinutes id=deadlineTimeMinutes name=deadlineTimeMinutes}
							</div>
							
							<div class="floatedElement">
								&nbsp;
							</div>
															
							<div class="floatedElement">
								<a id="deadlineTimeButton"><img src="{@RELATIVE_WCF_DIR}icon/datePickerOptionsM.png" alt="" /></a>
								<div id="deadlineTimeCalendar" class="inlineCalendar"></div>
								<script type="text/javascript">
									//<![CDATA[
									calendar.init('deadlineTime');
									//]]>
								</script>
							</div>
							
							<div class="floatedElement">
								&nbsp;
							</div>
							
							{if $errorField == 'deadlineTime'}
								<p class="floatedElement innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
									{if $errorType == 'invalid.future'}{lang}wcf.todolist.error.invalid.future{/lang}{/if}
								</p>
							{/if}
						</div>
					</fieldset>
				</div>
			</div>

 <!-- Lobby -->
			<div class="formElement{if $errorField == 'lobby'} formError{/if}">
				<div class="formFieldLabel">
					<label for="lobby">{lang}wcf.ecp.tourney.insert.tourney.lobby{/lang}</label>
				</div>
				<div class="formField">
					{htmlOptions options=$lobbyArray selected=$tourneyList.lobby id=lobby name=lobby}
					{if $errorField == 'lobby'}
						<p class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
						</p>
					{/if}
				</div>
			</div>

 <!-- Art -->
			<div class="formElement{if $errorField == 'art'} formError{/if}">
				<div class="formFieldLabel">
					<label for="art">{lang}wcf.ecp.tourney.insert.tourney.art{/lang}</label>
				</div>
				<div class="formField">
					{htmlOptions options=$artArray values=$artArray selected=$tourneyList.art id=art name=art}
					{if $errorField == 'art'}
						<p class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
						</p>
					{/if}
				</div>
			</div>

<input type="hidden" name="calender" value="{$tourneyList.calender}" />

 <!-- Anzahl -->
 			<div class="formElement{if $errorField == 'participiants'} formError{/if}">
				<div class="formFieldLabel">
					<label for="participiants">{lang}wcf.ecp.tourney.insert.tourney.participiants{/lang}</label>
				</div>
				<div class="formField">
					{htmlOptions options=$participiantsArray values=$participiantsArray selected=$tourneyList.participants id=participiants name=participiants}
					{if $errorField == 'participiants'}
						<p class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
						</p>
					{/if}
				</div>
			</div>

			{if $this->user->getPermission('mod.ecp.canMakeOfficial')}
 <!-- Offizielle FF Turnier -->
	 			<div class="formElement">
					<div class="formFieldLabel">
						<label for="officialEvent">{lang}wcf.ecp.tourney.insert.tourney.officialEvent{/lang}</label>
					</div>
					<div class="formField">
						{htmlOptions options=$officialEventArray values=$officialEventArray selected=$tourneyList.officialEvent id=officialEvent name=officialEvent}
					</div>
				</div>
			{/if}

 <!-- Description -->
			<div class="formElement{if $errorField == 'description'} formError{/if}">
				<div class="formFieldLabel">
					<label for="description">{lang}wcf.ecp.tourney.insert.tourney.description{/lang}</label>
				</div>
				<div class="formField">
					<textarea name="description" rows="10" cols="10" id="description">{$tourneyList.description}</textarea>
					{if $errorField == 'description'}
						<p class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
						</p>
					{/if}
				</div>
			</div>
 <!-- Submit -->
 		</fieldset>
		<div class="formSubmit">
			<input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
			<input type="reset" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		</div>

		</form>
	</div>
{include file="footer" sandbox=false}
</body>
</html>