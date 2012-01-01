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
			<form method="post" action="index.php?form=EcpInsertTourney{@SID_ARG_2ND}" name="dsf">

	<!-- Name des Turniers -->
				<div class="formElement{if $errorField == 'name'} formError{/if}">
					<div class="formFieldLabel">
						<label for="name">{lang}wcf.ecp.tourney.insert.tourney.name{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" name="name" id="name" class="inputText" value="{$name}" />
						{if $errorField == 'name'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
				</div>

		<!-- Kalender -->
				<div class="formGroup{if $errorField == 'date'} formError{/if}">
					<div class="formGroupLabel">
						<label for="deadlineTime">{lang}wcf.ecp.tourney.insert.tourney.tourneydate{/lang}</label>
					</div>
					<div class="formGroupField">
						<fieldset>
							<div class="formField">
								<div class="floatedElement">
									<label for="eventDay">{lang}wcf.global.date.day{/lang}</label>
									{htmlOptions options=$dayOptions selected=$eventDay id=eventDay name=eventDay}
								</div>

								<div class="floatedElement">
									<label for="eventMonth">{lang}wcf.global.date.month{/lang}</label>
									{htmlOptions options=$monthOptions selected=$eventMonth id=eventMonth name=eventMonth}
								</div>

								<div class="floatedElement">
									<label for="eventYear">{lang}wcf.global.date.year{/lang}</label>
									<input id="eventYear" class="inputText fourDigitInput" type="text" name="eventYear" value="{$eventYear}" maxlength="4" />
								</div>

								<div class="floatedElement">
									&nbsp;
								</div>

								<div class="floatedElement">
									<label for="eventHour">{lang}wcf.global.date.hour{/lang}</label>
									{htmlOptions options=$hourOptions selected=$eventHour id=eventHour name=eventHour} :
								</div>

								<div class="floatedElement">
									<label for="eventMinute">{lang}wcf.global.date.minutes{/lang}</label>
									{htmlOptions options=$minuteOptions selected=$eventMinute id=eventMinute name=eventMinute}
								</div>

								<div class="floatedElement">
									&nbsp;
								</div>

								<div class="floatedElement">
									<a id="eventButton"><img src="{@RELATIVE_WCF_DIR}icon/datePickerOptionsM.png" alt="" /></a>
									<div id="eventCalendar" class="inlineCalendar"></div>
									<script type="text/javascript">
										//<![CDATA[
										calendar.init('event');
										//]]>
									</script>
								</div>

								<div class="floatedElement">
									&nbsp;
								</div>
								{if $errorField == 'date'}
									<p class="floatedElement innerError">
										{if $errorType == 'invalid.future'}{lang}wcf.todolist.error.invalid.future{/lang}
											{else}{lang}wcf.global.error.empty{/lang}
										{/if}
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
						{htmlOptions options=$lobbyArray selected=$lobby id=lobby name=lobby}
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
						{htmlOptions options=$artArray values=$artArray selected=$lobby id=art name=art}
						{if $errorField == 'art'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
				</div>

	 <!-- Kalender Eintragung -->
				<div class="formElement{if $errorField == 'calender'} formError{/if}">
					<div class="formFieldLabel">
						<label for="calender">{lang}wcf.ecp.tourney.insert.tourney.calender.table{/lang}</label>
					</div>
					<div class="formField">
						{htmlcheckboxes name="calender" options=$calenderArray selected=1 id=calender}
						{if $errorField == 'calender'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
				</div>

	 <!-- Anzahl -->
	 			<div class="formElement{if $errorField == 'participiants'} formError{/if}">
					<div class="formFieldLabel">
						<label for="participiants">{lang}wcf.ecp.tourney.insert.tourney.participiants{/lang}</label>
					</div>
					<div class="formField">
						{htmlOptions options=$participiantsArray values=$participiantsArray selected=$participiants id=participiants name=participiants}
						{if $errorField == 'participiants'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
				</div>

	 <!-- Offizielle FF Turnier -->
				{if $this->user->getPermission('mod.ecp.canMakeOfficial')}
		 			<div class="formElement{if $errorField == 'officialEvent'} formError{/if}">
						<div class="formFieldLabel">
							<label for="officialEvent">{lang}wcf.ecp.tourney.insert.tourney.officialEvent{/lang}</label>
						</div>
						<div class="formField">
							{htmlOptions options=$officialEventArray values=$officialEventArray selected=$officialEvent id=officialEvent name=officialEvent}
							{if $errorField == 'officialEvent'}
								<p class="innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								</p>
							{/if}
						</div>
					</div>
				{/if}
 <!-- Description -->
				<div class="formElement{if $errorField == 'description'} formError{/if}">
					<div class="formFieldLabel">
						<label for="description">{lang}wcf.ecp.tourney.insert.tourney.description{/lang}</label>
					</div>
					<div class="formField">
						<textarea name="description" id="description" rows="10" cols="10">{$description}</textarea>
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