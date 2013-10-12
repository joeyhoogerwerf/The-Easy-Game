/*Your looking at The Easy Game's JavaScript core that provides single-pageness + eyecandy shizle.*/
function TheEasyGame(){
	/*FIELDS*/
	this.hsPI = 5000;//Highscore poll interval
	this.mCTS = 100;//mainClockTickingSpeed
	
	this.initialize = function(){
		console.log('Initializing The Easy Game..');
		this.renderHighscore();
		window.setInterval(this.renderHighscore, this.hsPI);
		window.setInterval(this.tickClock, this.mCTS);
		this.renderJonaKruimels();
		
		
		//FOOTER MAGNET?!?!?!?
		/*$(window).mousemove(function(e){
			console.log('xpos: ' + e.pageX);
			$('footer p:first').stop().animate({'left':e.pageX}, 500);
		});*/
		
		
		//INFADEN VAN REGEL WORDT LAATSTE CODE @ INITIALIZE ZODAT DE USER WEET DAT HET SPEL HELEMAAL READY IS!
		$('#logo').delay(1000).fadeIn(1000);
	}
}

TheEasyGame.prototype.initializeUserElements = function initializeUserElements(){
	
	
	
}

TheEasyGame.prototype.pollForNewClick = function pollForNewClick(){
	
	
	
}

TheEasyGame.prototype.postClick = function postClick(){
	
	
	
}

TheEasyGame.prototype.renderHighscore = function renderHighscore(){
	var lH1O = $('#left h1').offset();//Capture offset of <highscore/> to place highscore-loader perfect.
	$('#highscoreLoader').css({'top': (lH1O.top + 8) + 'px', 'left' : (lH1O.left + $('#left h1').width() + 5) + 'px'}).fadeIn(300, function(){
		$.getJSON('../../../php/api/highscore', function(highscoreData){//Send request to get highscore data -> fetching..
			$('#highscoreLoader').hide();//..done! Hide loader.
			$('#highscore > tbody').empty();//Empty	 highscore-body.
			$(".tooltip").remove();
			$('#userRank').text('#' + highscoreData.rank);//Refresh user rank.
			$(highscoreData.users).each(function(rNr){//Render row for each user in highscore & show tooltip @ #1 ranked player.
				$('#highscore > tbody').append(Mustache.render('<tr data-fb_id="{{fb_id}}" rel="uzer" data-original-title="<img id=\'userPicture\' src=\'https://graph.facebook.com/{{fb_id}}/picture\' />"><td>' + (rNr + 1) + '</td><td>{{username}}</td><td>' + addDots(this.score) + '</td></tr>', this));
			});
			$('#highscore > tbody > tr').tooltip({'placement':'left'});//Declare tooltips for users in highscore.
			
			$.doTimeout(500, function(){
				$('#highscore > tbody > tr:first').tooltip('show');//Render tooltip foor #1.
			})
		});
	});
}

TheEasyGame.prototype.tickClock = function tickClock(){
	/*This function will:
	-fetch UTC-globaltime
	-rerender corresponding span
	-rerender document title*/
	var d = new Date();	//Get zulu time.
	var h = d.getUTCHours(), m = d.getUTCMinutes(), s = d.getUTCSeconds();
	$('#mainClock').html(x(h) + '<span class="colon-separator">:</span>' + x(m) + '<span class="colon-separator">:</span>' + x(s));//Render HTML-clock.
	document.title = 'The Easy Game | ' + x(h) + ':' + x(m) + ':' + x(s);//Update title of document with current time.
	function x(t){//Ensure time looks ok.
		t < 10 ? t = '0' + t.toString() : t;
		return t;
	}
}

TheEasyGame.prototype.tickSpawnClock = function tickSpawnClock(){
	
	
	
}


//jonakruimels als objct benaderen
//dan kan je fixen dat er crumbs tussen zitten die heel snel en irritand bewegen?	
TheEasyGame.prototype.renderJonaKruimels = function(){	
	var jKRand = (15 + (Math.random() * 15));
	setInterval(function(){
		if($('#jonaKruimels strong').length > jKRand){
			$('#jonaKruimels strong').each(function(){
				$(this).animate({'top': '+=' + ((Math.random() * 50) - 25) + 'px', 'left': '+=' + ((Math.random() * 50) - 25) + 'px'}, (250 + Math.round(Math.random() * 250)), 'easeInBack');
			});
		}
		else{
			$('#jonaKruimels').append('<strong>.</strong>').find('strong:last').css({'font-size': (6 + Math.round(Math.random() * 3)) + 'pt' ,'margin-left': (Math.round(Math.random() * 5)) + 'px','color':getRandRGBColor(), 'top':(5 + Math.round(Math.random() * 10)) + 'px'}).animate({'top': '0px', 'opacity': 1}, 200, 'easeOutElastic');
		}
	}, 200);
}

function getRandRGBColor(){//Get random RGB-color.
	return 'rgb(' + (Math.round(Math.random() * 255)) + ', ' + (Math.round(Math.random() * 255)) + ', ' + (Math.round(Math.random() * 255)) + ')';
}
