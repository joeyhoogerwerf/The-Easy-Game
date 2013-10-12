<?php
require 'php/main.php';
?>
<!-- THE EASY GAME | CLOSED BETA V.1-->
<!-- Developed by Joey Hoogerwerf -->
<!DOCTYPE html>
<html>
<head>
	<title>The Easy Game | </title>
	<link rel="icon" type="image/png" href="assets/img/fav_S.png">
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/tipsy.css">

	
	<!--Import Recaptcha AJAX-->
	<script src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
	
	<!--Import jQuery + libraries/plugins-->
	<script src="assets/js/jquery.1.7.2_min.js"></script>
	<script src="assets/js/jquery.easing.1.3.js"></script>
	<script src="assets/js/jquery.scrollTo.js"></script>
	<script src="assets/js/jquery.doTimeout.1.0_min.js"></script>
	<script src="assets/js/jquery.shadow.js"></script>
	<script src="assets/js/jquery.animate-textshadow.js"></script>
	<script src="assets/js/jquery.colorAnimation.js"></script>
	<script src="assets/js/jquery.tipsy.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/mustache.js"></script>
	<script src="assets/js/micro.templating.js"></script>
	<script src="assets/js/jquery.animate.css.rotate.scale.js "></script>
	<script src="assets/js/jquery-css-transform.js"></script>
	
	<!--Import Bootstrap-->
	<script src="assets/js/bootstrap.min.js"></script>
	
	<!--Import The Easy Game-->
	<script src="assets/js/classes/TheEasyGame.class.js"></script>
	
<!--My code-->
<script>

</script>

<script>
var uILI = <?php if($user){echo 'true';} else{ echo 'false';}?>;
var userIsInitialized = false;
var isRequestingClick = false;
var spawnTime = 0;
var score = 0;
var inventoryIsFull = false;
var pollStationary = false;
var recaptchaIsActive = false;
var isClickingOnClock = false;
var highsorePollInterval = 5000;

var highscoreIsInitialized = false;

$(function(){//When DOM is ready.
	
	
	var teg = new TheEasyGame();
	teg.initialize();
	
	if(uILI == true){
		pFNC();//When user is logged in first poll for new click.
	}
	
	$('#mainClock').mousemove(CRC).mousedown(function(){//When player clicks screen will drop.
		$.scrollTo($('#buttonBlock').height(), $('#buttonBlock').height(), {easing:'easeOutBounce'});
		clearInterval();
	});
	
	// /window.setInterval('tickClock()', 100);
	
	$('footer').css('left', (($(window).width() / 2) - ($('footer').width() / 2)) + 'px');
	
	$('.blockIn').css('width', $('.blockIn > span').width() + 'px').mouseenter(function(){/*Hackish way to center span.*/console.log('enter live feed');
			
			$('body').append('<p class="crumScore">#1289149 | 09:40:06 | Joyz0r</p>');
			
			
			
			$('.crumScore:last').css({'top' : $(this).offset().top, 'left' : $(this).offset().left, 'color' : GetRandRGBColor()}).animate({'opacity' : 1, 'left' : '+=' + ((Math.random() * 200) - 100) + 'px', 'top' : '+=' + ((Math.random() * 125) + 200) + 'px'}, 400, 'easeInOutElastic', function(){
				//$(this).remove();
			});
			
			
			
			
			$.getJSON('php/get_last_clicked_clicks.php', function(lCCD){console.log('Last clicked click: ' + lCCD);
				
				
				
				$(lCCD).each(function(){
					
					
					
					//SCORE: 1 - 100
					//color = white
					
					//SCORE: 100 - 1.000
					//color = blue
					
					//score 1.000 - 10.000
					//color = red
					
					//score 10.000 - 100.000
					//color = yellow
					
					//score > 100.000
					//color = violet
					
					
					
				});
				
				$('body').append('<p class="crumScore">#1289149 | 09:40:06 | Joyz0r</p>');
				
				
				
			});
	});
	
	$('#highscore > tbody > tr').live('mouseenter', function(){
		$(this).stop().animate({backgroundColor: 'rgb(0, 0, 221)'}, 200);
	});
	
	$('#highscore > tbody > tr').live('mouseleave', function(){
		$(this).stop().animate({backgroundColor: '#000'}, 400);
	});
});

function pFNC(){//Poll a request to check for new clicks.
	
	$.get('php/poll_new_click.php', function(data){
		
		//Parse served JSON-string into working object.
		pollData = $.parseJSON(data);
		
		if(pollData != null){
			console.log('Polled for new click! | Time till next click-spawn: ' + $.parseJSON(data).timeTillNextClickspawn + ' | Inventory is full: ' + $.parseJSON(data).inventoryIsFull + ' | Number of spawned clicks: ' + $.parseJSON(data).nrOfSpawnedClicks + ' | Elapsed time: ' + $.parseJSON(data).elapsedTime);
			
			
			//Calculate/set timer for next poll.
			$.doTimeout('newClick', (pollData.timeTillNextClickspawn * 1000), function(){
				pFNC();
			}, true);
			
			//Calibrate spawn clock.
			$('#spawnTime').text(spawnTime = pollData.timeTillNextClickspawn);
			
			inventoryIsFull = pollData.inventoryIsFull;
			
			if(userIsInitialized){//Check if user is initialized before appending any buttons.
				for(var nrOSCC = 0; nrOSCC < pollData.winningClickTypesArray.length; nrOSCC++){//Loop trough returned dataset with won click types.
					var wCT = pollData.winningClickTypesArray[nrOSCC];//Register winning click type.
					var clickString = wCT.toUpperCase() + ' CLICK';//Capitalize string displayed in button
					if(wCT == "black" || wCT == "grey" || wCT == "white") clickString = "CLICK";//When buttons is [black, grey, white] only dislay CLICK.
					$('#buttonBlock').append('<div style="display: none;" id="' + wCT + '" class="button"><span>' + clickString + '</span></div>').find('.button:last').fadeIn(1000).mouseup(clickHandler);//Prepend button
				}
			}
			
			else initializeUser();
		}
	});
}

function initializeUser(){//Initialize user.
	
	$.getJSON('php/get_user_data.php', function(userData){console.log('Initialized user! | Username: ' + userData.user_data.username);
	
		if(userData != null){
			uD = userData.user_data;/*Minify response-data.*/
			uCD = userData.click_data;/**/
			
			var taoClicks = (parseInt(uD.ao_black_clicks) + parseInt(uD.ao_grey_clicks) + parseInt(uD.ao_super_clicks) + parseInt(uD.ao_ultra_clicks) + parseInt(uD.ao_giga_clicks) + parseInt(uD.ao_hyper_clicks) + parseInt(uD.ao_haze_clicks) + parseInt(uD.ao_white_clicks));//Calculate total amount of clicked_clicks.
			
			score = uD.score;
			
			if(uD.recaptcha_is_active == 1){//CHECK IF RECAPTCHA IS ACTIVE
				recaptchaIsActive = true;
				Recaptcha.create("6LeMBNISAAAAAMMeXegVc0NbWbmOOQc3Ywa2d68f", 'recaptchaBlock', {callback: Recaptcha.focus_response_field});
				setInterval(function(){
					$('#recaptchaBlock').animate({'boxShadowBlur':'55px'}, 500, function(){
						$(this).animate({'boxShadowBlur': '0px'}, 500);
					});
				}, 1000);
			}
			
			window.setInterval('tickSpawnClock()', 1000);
			
			$('#clickStats > tbody').empty();//Remove example-data from clickStats-table.
			$('#buttonBlock').empty();//In case poll_new_click returns a new click before initializing.
			
			$('#right h1').after(Mustache.render('<div id="userInfo"><img id="userPicture" src="https://graph.facebook.com/<?php echo $user;?>/picture" /><span id="username">{{username}} </span></div><p id="scoreBlock"><span id="userRank"></span> | <span>' + addDots(uD.score) + '</span></p>', uD));//Render user info on screen.
			
			if(uCD.length > 1) $(uCD).each(function(i, v){//When user clicked more than 0 clicks -> render HTML-row for each record.
				$('#clickStats > tbody').append(Mustache.render('<tr><td>' + (taoClicks - i) + '</td><td>{{click_id}}</td><td>{{time}}</td><td>{{multiplier}}</td><td>' + addDots(v.click_score) + '</td></tr>', this));
			});
			
			for(var blackC = 0; blackC < uD.ao_black_clicks; blackC++)
				$('#buttonBlock').append('<div id="black" class="button"><span>CLICK</span></div>');
				
			for(var greyC = 0; greyC < uD.ao_grey_clicks; greyC++)
				$('#buttonBlock').append('<div id="grey" class="button"><span>CLICK</span></div>');
				
			for(var superC = 0; superC < uD.ao_blue_clicks; superC++)
				$('#buttonBlock').append('<div id="blue" class="button"><span>BLUE</span></div>');
				
			for(var ultraC = 0; ultraC < uD.ao_strawberry_clicks; ultraC++)
				$('#buttonBlock').append('<div id="strawberry" class="button"><span>STRAWBERRY</span</button>');
				
			for(var gigaC = 0; gigaC < uD.ao_apple_clicks; gigaC++)
				$('#buttonBlock').append('<div id="apple" class="button"><span>APPLE</span></div>');
				
			for(var hyperC = 0; hyperC < uD.ao_sun_clicks; hyperC++)
				$('#buttonBlock').append('<div id="sun" class="button"><span>SUN</span></div>');
				
			for(var hazeC = 0; hazeC < uD.ao_haze_clicks; hazeC++)
				$('#buttonBlock').append('<div id="haze" class="button"><span>HAZE</span></div>');
				
			for(var whiteC = 0; whiteC < uD.ao_white_clicks; whiteC++)
				$('#buttonBlock').append('<div id="white" class="button"><span>CLICK</span></div>');
			
			$('#buttonBlock .button').mouseup(clickHandler);
			
			
			//window.setInterval('renderHighscore()', highsorePollInterval);
			
			userIsInitialized = true;
		}
	});
}

function clickHandler(){//Clickhandler
	
	/*WHEN RECAPTCHA IS NOT ACTIVE*/
	if(!recaptchaIsActive){
		var b = $(this);
		var wArray = new Array('Good luck!', 'You can do it!');
		
		if(isRequestingClick == false){//When there is no click-request pending.
			
			//good luck
			$.post('php/post_click.php', {type:b.attr('id')}, function(data){//Instantly post click while animation is running.
				postClickData = $.parseJSON(data);//Parse that shit.
				
				if(inventoryIsFull == true)
					pFNC();	//>>>>>>>>>>POLL FOR NEW CLICK<<<<<<<<<<
					
					var interval1 = window.setInterval(function(){
					
					if(step1 == false){-
						window.clearInterval(interval1);//Stop interval.
						$('body').append('<p id="clickScore">' + postClickData.score + '</p>');//Append new invisible flash-score.
						$('#ajaxSpinner').animate({opacity: 0}, 200, function(){//Fade out AJAX-loader.
							$('#clickScore').css({'top': ($(this).offset().top) - 1, 'left': ($(this).offset().left - ($('#clickScore').width() / 2 - 9)), 'textShadow':'#fff 0px 0px 0px'}).animate({'opacity': 1.0, 'textShadow':'#ffff00 0px 0px 50px'}, 100, function(){//Position flash-score @ AJAX-spinner, set TS -> fadeIn, TSexplosion ->
								$(this).delay(500).animate({opacity:0}, 200, function(){//Remove clickscore.
									$(this).remove();
								});
								
								b.delay(500).slideUp(250, function(){//Delay 500, Slide up button -> destruct, flag animation/request complete, reset cursor.
									b.remove();
									isRequestingClick = false;
									$('#buttonBlock .button').css('cursor', 'pointer');
								});
							});
							
							score = postClickData.newScore;
							
							$('#scoreBlock > span:nth-child(2)').html(addDots(postClickData.newScore));//Rerender user-score.
							
							$('#clickStats > tbody').prepend('<tr style="display:none;"><td>' + postClickData.tao_clicks + '</td><td>' + postClickData.id_last_inserted + '</td><td>' + postClickData.time + '</td><td>' + postClickData.multiplier + '</td><td>' + postClickData.score + '</td></tr>').find('tr:first').fadeIn(100);//Append new score @ user-taboe.
						});
					}
				}, 50);
			});
			
			isRequestingClick = true;//
			
			var step1 = true;//Flag boolean for async POST.
			
			$('#mainClock').animate({'textShadow':'#000 0px 0px 50px'}, 300, function(){//>>>>>>>>>>FX<<<<<<<<<< | BLACK SHADOW IMPLOSION
				$(this).animate({'textShadow':'#000 0px 0px 0px'}, 200);
			});
			
			CRC();//>>>>>FX<<<<< [CLOCK RANDOM COLOR]
			
			b.css({'padding': '0px', 'font-size' : '6pt', 'border': '1px solid #fff', 'margin-bottom': '60px'}).html(wArray[Math.round(Math.random() * 1)])//1.Reset css | 2. append wish.	
			$('#buttonBlock .button').css('cursor', 'wait');//Set cursos @ waiting-modus.
			b.unbind('click').animate({'font-size': '0px', 'boxShadowBlur': '100px', 'height': '0px', 'margin-bottom': '60px'}, 200, function(){//Unbind click -> start animation.
				$(this).animate({'width': '0px', 'opacity': 0}, 300, 'easeInExpo', function(){//Chain to next animation.
					step1 = false;//Finished step 1 -> score is allowed to show its face.
					$('#ajaxSpinner').css({'top': ($(this).offset().top + 2) + 'px','left': ($(this).offset().left + ($(this).width() / 2) - 8)}).animate({opacity: 1}, 200);//Render AJAX-spinner.
				});
			});
			
			/*START HAZE ANIMATION*/
			if(b.attr('id') == 'haze'){
				$('body').animate({backgroundColor: b.css('background-color')}, 300, function(){
					$('body').animate({rotate: (Math.random() * 10) + 'deg'}, 200, 'easeOutBounce', function(){
						$(this).animate({rotate: (Math.random() * 10) + '3deg'}, 400, 'easeOutElastic');
					});
					
					$(this).animate({backgroundColor: 'rgb(255, 0, 0)'}, 100, function(){
						$(this).animate({backgroundColor: 'rgb(0, 255, 0)'}, 200, function(){
							$(this).animate({backgroundColor: 'rgb(0, 0, 255)'}, 100);
							$(this).animate({backgroundColor: GetRandRGBColor()}, 2000, 'easeOutElastic', function(){
								$(this).animate({backgroundColor: GetRandRGBColor()}, 1000, 'easeOutElastic');
								$(this).slideUp(1000, 'easeOutElastic', function(){
									window.location.reload();
								});
							});
							$('.button').each(function(){
								$(this).animate({'boxShadowBlur': (Math.random() * 120) + 'px', 'height': '+=' + (Math.random() * 400) + 'px', 'width':'+=' + (Math.random() * 600) + 'px'}, (Math.random() * 5000), 'easeInBounce');
							});
						});
					});
				});
			}
			
			/*START STRAWBERRY ANIMATION*/
			else if(b.attr('id') == 'strawberry'){
				$('#strwbrr').css('display', 'block').fadeIn(500).position($(b).position());
				
				$('body').animate({backgroundColor: b.find('span').css('background-color')}, 0, function(){
					$(this).animate({backgroundColor: '#000'}, 400);
					$('#strwbrr').delay(200).fadeOut(300);
				});
			}
			
			/*START WHITE ANIMATION*/
			else if(b.attr('id') == 'white'){
				$('#whiteGif').css('display', 'block').fadeIn(500);
				window.scrollTo(0);
				$('body').animate({backgroundColor: b.css('background-color')}, 0, function(){
					$(this).animate({backgroundColor: '#000'}, 400);
					$('#whiteGif').delay(200).fadeOut(300);
				});
			}
			
			/*START DEFAULT ANIMATION*/
			else{
				$('body').animate({backgroundColor: b.css('background-color')}, 0, function(){
					$(this).animate({backgroundColor: '#000'}, 600);
				});
			}
		}
	}
	
	
	/*WHEN RECAPTCHA IS ACTIVE*/
	else{
		
		if(Recaptcha.get_response() == "")
			alert('Please fill in the security question. \r\rYou get a little bonus! :)');
			
		else{
			console.log('something filled in!');
			
			$.post('php/recaptcha/verify.php', {c:Recaptcha.get_challenge(), r:Recaptcha.get_response()}, function(data){
				
				console.log('challenge: ' + data);
				
				if(data == 1){
					console.log('cool');
					$('#recaptchaBlock').fadeOut(200);
					recaptchaIsActive = false;
				}
				
				else{
					console.log('wrang');
				}
			})
			
		}
	}
}

function tickClock(){//Tick main clock 1 second.
	
	//Get zulu time.
	var d = new Date();
	var h = d.getUTCHours(), m = d.getUTCMinutes(), s = d.getUTCSeconds();
	
	//Create mask.
	var mask = x(h) + '<span class="colon-separator">:</span>' + x(m) + '<span class="colon-separator">:</span>' + x(s);
	
	var otherMask = x(h) + ':' + x(m) + ':' + x(s);
	
	//Append mask @ HTML-clock.
	$('#mainClock').html(mask);
	
	//if(otherMask == "22:01:10")
		//$('#mainClock').css({'-webkit-box-shadow':'0px 50px 30px #ffffff'}).animate({'boxShadowBlur': '150px'}, 600);
		
	
	//Update title of document.
	document.title = 'The Easy Game | ' + otherMask;
	
	//Ensure time looks ok.
	function x(t){
		t < 10 ? t = '0' + t.toString() : t;	
		return t;
	}
}

function tickSpawnClock(){//Tick spawn clock.
	
	spawnTime--;
	
	
	if(inventoryIsFull == true){
		$('#waitingmessage-block').html(tmpl('maximum_waitingmessage', {'_aoClickturnsMax':11}));
	}
	
	else{
		if(spawnTime <= 0){
			$('#spawntime').animate({'opacity': 0}, 100);
			$('#nicerdicer').css({'bottom': $('#spawntime').position().bottom + 'px', 'left': ($('#spawntime').position().left - 1) + 'px'}).fadeIn(300);
		}

		else{
			$('#waitingmessage-block').html(tmpl('normal_waitingmessage', {'_spawnTime':spawnTime}));
			
			if(spawnTime == 1){
				$('#spawntime').css({'color':'#ffff00', 'textShadow':'#fff 0px 0px 0px'}).animate({'textShadow': $('#spawntime').css('color') + ' ' + '0px 0px 60px'}, 300, function(){
					$(this).css('textShadow','#fff 0px 0px 0px');
				});
				$('#waitingMessage1 span:nth-child(3)').html('second');
			}
			
			else if(spawnTime > 1 && spawnTime <= 5){
				 $('#spawntime').css({'color':'#00ff00', 'textShadow':'#fff 0px 0px 0px'}).animate({'textShadow': $('#spawntime').css('color') + ' 0px 0px 60px'}, 300, function(){
					$(this).css('textShadow','#fff 0px 0px 0px');
				});
			}
			
			else if(spawnTime > 5 && spawnTime <= 10){
				$('#spawntime').css({'color':'#ff0000', 'textShadow':'#fff 0px 0px 0px'});
			}
			
			else if(spawnTime > 10) $('#spawntime').css('color','#0000aa');
		}
	}
}

function addDots(nStr){//Add dots.
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + '.' + '$2');
	}
	return x1 + x2;
}

function GetRandRGBColor(){//Get random RGB-color.
	return 'rgb(' + (Math.round(Math.random() * 255)) + ', ' + (Math.round(Math.random() * 255)) + ', ' + (Math.round(Math.random() * 255)) + ')';
}

function CRC(){//Clock random color.
	$('#mainClock').animate({'borderColor': 'rgb(' + Math.round(Math.random() * 255) + ', ' + Math.round(Math.random() * 255) + ', ' + Math.round(Math.random() * 255) + ')'}, 300);
}
</script>
</head>
<body>
<div id="wrapper">
	<div id="left">
	<div id="highscoreWrap">
		<h1 class="bold">HIGHSCORE</h1>
		
		<table id="highscore">
			<thead>
				<tr>
					<th>#</th>
					<th>USERNAME</th>
					<th>SCORE</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	</div>
	<div id="right">
	<div id="yourAccountWrap">
		<h1 class="bold">WELCOME</h1>
		<?php if(!$user){?>
		<fb:login-button id="loginBtnFB" size="large" registration-url="http://127.0.0.1/teg/register.php"></fb:login-button>
		<?php }?>
		
		<table id="clickStats">
			<thead>
				<tr>
					<th>#</th>
					<th>CLICK_ID</th>
					<th>TIME</th>
					<!--<th>TYPE</th>-->
					<th style="color:#0000ff;">X</th>
					<th>SCORE</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>0</td>
					<td>0</td>
					<td>00:00:00</td>
					<!--<td>HAZE CLICK</td>-->
					<td>0</td>
					<td>0</td>
				</tr>
			</tbody>
		</table>
		<?php if(!$user){?>
		<div id="homeText">
			<p><strong>The Easy Game </strong> is a very <strong>simple</strong>,  yet <strong>addictive</strong> mini <strong>text-based browsergame</strong>.</p>
			<p>Gain <strong>massive</strong> <strong style="color:#00cc00;">scores</strong> by clicking <strong>buttons</strong> at the right <strong>moment</strong>. E.g. <strong>multiply</strong> your <strong>score </strong>by <strong style="color:#0000ff;">X</strong> when the <strong style="text-decoration: underline;">clock</strong> shows: <span id="exampleTargetTimes"><strong>11:11:11</strong>, <strong>12:34:56</strong>, <strong>11:12:13</strong>, 18:<strong>00:00</strong>, 17:5<strong>1:23</strong>, 23:<strong>08:08</strong>, 04:15:<strong>33</strong></span> <strong>etc</strong><span id="jonaKruimels"></span></p>
			
			<p class="homeText"><strong>Register</strong> with <strong>Facebook</strong> to play.</span>
			<p></p>
			<p class="homeText">Have <strong>fun!</strong> ^_^</p>
		</div>
		<?php }?>
	</div>
	</div>
	<div id="center">
			<span id="mainClock" class="unslctbl">00:00:00</span>
			<div id="recaptchaBlock"></div>
			<img id="whiteGif" src="assets/img/white.gif" id="highscoreLoader" />
			<img id="strwbrr" src="assets/img/strwbrr.png" />
		<div id="buttonBlock">
		<!--MAGIC-->
		</div>
		
			<div id="waitingmessage-block"></div>
		<div class="blockOut">
			<div class="blockIn">
				<span id="liveFeed">LIVE FEED</span>
			</div>
			<img src="assets/img/arr_down.png" />
		</div>
		
		<script type="text/html" id="normal_waitingmessage">
			<p id="waitingMessage1">You get a new click in <span id="spawntime"><b><%= _spawnTime%></b></span><img id="nicerdicer" src="assets/img/nicerdicer_w.png" /> <span>seconds</span>!</p>
		</script>
		
		<script type="text/html" id="maximum_waitingmessage">
			<p id="waitingMessage2"><span id="maximum">MAXIMUM OF <%= _aoClickturnsMax%> CLICKS</span></p>
		</script>
		
		<script type="text/html" id="new_waitingmessage">
			<p id="waitingMessage3"><span id="new-player">Welcome to the easy game!</span></p>
		</script>
		
		<script type="text/html" id="hyper_waitingmessage">
			<p id="waitingMessage4"><span id="new-player">IN HYPERMODE</span></p>
		</script>
		
		<script type="text/html" id="not-connected_waitingmessage">
			<p id="waitingMessage5"><span id="new-player">NO CONNECTION<!--evt. kruisje ofzo of internetstekker uit ding logo naaj$--></span></p>
		</script>
	</div>
	<div class="clearFix"></div>
	<footer>
		<p><span>&copy;</span> 2012 <strong>T</strong>he <strong>E</strong>asy <strong>G</strong>ame</p>
		<p id="lLetters"><STRONG>your IP</STRONG> <span style="text-shadow: 0px 0px 1px #00bb00; font-weight: 900; color:#00bb00;"><?php echo $_SERVER["REMOTE_ADDR"]; ?></span> <span class="pipe">|</span> version <span style="font-weight: 900;">1.0</span> <span class="pipe">|</span> running since <span style="font-weight: 900;">10</span> days <span class="pipe">|</span> <span style="font-weight: 900;">2704</span> members <span class="pipe">|</span> <span style="font-weight: 900;">608</span> online players <span class="pipe">|</span> <span style="font-weight: 900;">39579</span> clicked clicks</p>
		<img src="assets/img/theeasygame_logo.png" id="logo" />
	</footer>
</div>
<div id="fb-root"></div>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId: '<?php echo $facebook->getAppID() ?>',
      cookie: true,
      xfbml: true,
      oauth: true
    });
	
	
	FB.Event.subscribe('auth.login', function(response) {
		window.location.reload();
	});
  };

  (function() {
    var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol +
      '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
  }());
</script>

<img id="ajaxSpinner" src="assets/img/ajax-loader.gif" />
<img src="assets/img/highscore_loader.gif" id="highscoreLoader" />

<div id="foobar"></div>
</body>
</html>