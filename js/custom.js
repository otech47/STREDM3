$(document).ready( function() {
	mixpanel.track("Page View");
	$('.duration').hide();
	function Queue(arr) 
	{
		var i = 0;
		this.callNext = function() { 
			typeof arr[i] == 'function' && arr[i++]();
		};
	}
	function animateWelcomeBar() {
		if($("html").width() > 568)
		{
			$(".welcome-holder").css("padding","0vh 10vw");
			$(".welcome-wrapper").css("padding","0vh 0vw");
			$(".welcome-container").hide();
			$(".welcome-holder").css("width","");
			$(".welcome-holder").css("height","7vh");
			$(".welcome-options-container").css("width","80vw");
			$(".welcome-options-container").css("align-items","center");
			$(".options-item-wrapper").css("height","7vh");
			$(".welcome-button").css("height","7vh");
			$(".welcome-button").css("font-size","4vh");
			$(".welcome-holder").css("position","fixed");
		}
	}
	function randomColor() {
    	var choice = Math.floor(Math.random()*8);
    	var color = ["linear-gradient(to bottom, #e4f5fc 0%,#bfe8f9 20%,#9fd8ef 35%,#2ab0ed 100%)", 
    	"linear-gradient(to bottom, #f16f5c 13%,#f16f5c 20%,#f16f5c 26%,#f16f5c 36%,#f02f17 66%,#e73827 100%)", 
    	"linear-gradient(to bottom, #b3feff 0%,#4ae8f9 57%)", 
    	"linear-gradient(to bottom, #e2b1d8 0%,#dd40b6 63%,#de47ac 100%)", 
    	"linear-gradient(to bottom, #ffb76b 0%,#ffa73d 24%,#ff7c00 56%,#ff7f04 100%)", 
    	"linear-gradient(to bottom, #fcfeea 0%,#fcf944 100%)", 
    	"linear-gradient(to bottom, #fcfff4 0%,#dfe5d7 40%,#b3bead 100%)", 
    	"linear-gradient(to bottom, #b4e391 0%,#0fff02 51%,#65ed7a 100%)", 
    	"linear-gradient(to bottom, #ff5db1 38%,#ef017c 100%)"];
    	return color[choice];
	}
	function getAllTags()
	{
		$.ajax({
			type: "GET",
			url: "../scripts/allTags.php",
			async: false,
			dataType: 'json',
			success: function(data)
			{
				$.each(data[0], function(index,value) {
					artistArray[index] = value;
					autocompleteTags.push(value);
				})
				$.each(data[1], function(index,value) {
					eventArray[index] = value;
					autocompleteTags.push(value);
				})
				$.each(data[2], function(index,value) {
					radiomixArray[index] = value;
					autocompleteTags.push(value);
				})
				$.each(data[3], function(index,value) {
					genreArray[index] = value;
					autocompleteTags.push(value);
				})
			}
		});
	}
	function columnCreate(type, tileName)
	{
		var backgroundColors = {artist:"rgba(16,75,255,.7)", event:"rgba(226, 79, 79, .7)", radiomix:"rgba(73, 215, 99, .7)", genre:"rgba(103, 73, 215, .7)"}
		var columnType = {title:"Empty", id:"rc-0"};
		if(type == "artist") {
			columnType = {title:"Artist", id:"rc-1"};
		} else if(type == "event") {	
			columnType = {title:"Event", id:"rc-2"};
		} else if(type == "radiomix") {	
			columnType = {title:"Radio Mix", id:"rc-3"};
		} else if(type == "genre") {	
			columnType = {title:"Genre", id:"rc-4"};
		} else {	
			columnType = {title:"Miscellaneous", id:"rc-5"};
		}
		var columnCode = $("<div class='column-wrapper'><div class='autocomplete-column'>" + 
			"<h1></h1><div class='back-to-results'><div class='back fa-th fa-2x'></div>" + 
			"<div class='back-text'>  Show previous results...</div></div>" + 
			"<div class='results-wrapper'><div class='results-container'></div></div></div></div>");
		columnCode.find("h1").append((columnType.title).toString()).click(function() {
			console.log(this);
			$(this).siblings(".results-wrapper").css("display","block");
			$(this).siblings(".results-wrapper").css("height","67.5vh");	
		});
		columnCode.find(".results-container").attr("id", (columnType.id).toString());
		columnCode.attr("id", type.toString()+"-wrapper");
		columnCode.attr("style", "margin-left: -200vw");
		columnCode.appendTo(".autocomplete-container");
		$.each(tileName, function (index, value) {
			var a = value.appendTo("#"+columnType.id).addClass("result");
			a.css("background-color", backgroundColors[type]);
			a.click(function() {
				if(type == "artist")
				{
					var link = "?artist="+a.text();
				}
				if(type == "event" || type == "radiomix")
				{
					var link = "?event="+a.text();
				}
				if(type == "genre")
				{
					var link = "?genre="+a.text();
				}
				window.history.replaceState("string", "Title", link);
				openPanel(a);
			});
		});
		var resultsIsotope = $(".results-container");
		resultsIsotope.isotope({
			itemSelector : ".result",
			resizable: false,
			layoutMode : "cellsByRow",
			cellsByRow: {
    			columnWidth: resultsIsotope.width()/2,
    			rowHeight: resultsIsotope.height()/3
  			}
		});
		$(window).smartresize(function(){
			resultsIsotope.isotope({
				cellsByRow: {
    				columnWidth: resultsIsotope.width()/2,
    				rowHeight: resultsIsotope.height()/3
    			}
    		});
		});
	}
	function createPanelResults(type, tileName)
	{
		$(".tiles-wrapper").empty();
		var columnType = {title:"Empty", id:"prc-0"};
		if(type == "artist-wrapper") {
			columnType = {title:"Artist", id:"prc-1"};
		} else if(type == "event-wrapper") {	
			columnType = {title:"Event", id:"prc-2"};
		} else if(type == "radiomix-wrapper") {	
			columnType = {title:"Radio Mix", id:"prc-3"};
		} else if(type == "genre-wrapper") {	
			columnType = {title:"Genre", id:"prc-4"};
		}
		var columnCode = $("<div class='panel-results-wrapper'><div class='panel-results-container'></div></div>");
		columnCode.find(".panel-results-container").attr("id", (columnType.id).toString());
		columnCode.attr("id", type.toString()+"-panel");
		columnCode.appendTo(".tiles-wrapper");
		$.each(tileName, function (index, value) {
			var a = value.appendTo("#"+columnType.id);
			a.attr("data-url", urlArray[index]);
			a.attr("data-img", imgArray[index]);
			a.click(function(){
				var link = "?artist="+valueArray[index].artist+"&event="+valueArray[index].event;
				window.history.replaceState("string", "Title", link);
				var playerTitle = valueArray[index].artist + " - " + valueArray[index].event;
				$('#jp_player_title').text(playerTitle);
				$('#jquery_jplayer_1').jPlayer("setMedia", {
					mp3: "uploads/"+a.attr('data-url')
				});
				$('#thumbnail').css('background-image', "url('../uploads/"+a.attr('data-img')+"')");
				$('.duration').show();
				$('#jquery_jplayer_1').jPlayer('play');
				$(".fb-share-button").attr("data-href", "http://stredm.com/"+link);
				FB.XFBML.parse();
				$('.scroll-wrapper').scrollTo("div.stredming-wrapper", 500, {offset:-($("body").height()*.07)});
				mixpanel.track("Specific Set Play");
				var timer = $.timer(function() {
					mixpanel.track("Specific Set Played for 5 Minutes");
				}, 5000, false);
				timer.once(300000);
			});
		});
		panelIsotope = $(".panel-results-container").isotope({
			itemSelector : ".panel-result",
			resizesContainer: false,
			layoutMode : "straightDown",
			getSortData : {
				label : function ( $elem ) {
      				return $elem.text();
      			}
      		},
      		sortBy: "label"
    	});
		window.setTimeout(function() {
			$(".panel-results-container").css("margin-top","0px");
		}, 100);
	}
	function playSet()
	{
		var urlString = window.location.search.replace(/%20/g, " ").replace(/\+/g, " ");
		console.log(urlString);
		var regex = /&(?=\S)/;
		urlString = urlString.substring(1);
		var nvPairs = urlString.split(regex);
		var name = new Array();
		var value = new Array();
		var choice;
		for(i = 0 ; i < nvPairs.length; i++)
		{
			var nvPair = nvPairs[i].split("=");
			name[i] = nvPair[0];
			value[i] = nvPair[1];
		}
		if (nvPairs.length == 1)
		{
			$.ajax({
				type: "POST",
				url: '../scripts/request.php',
				data: {label:value[0]},
				dataType: 'json',
				success: function(data)
				{
					mainACWidget.autocomplete("search", value[0]);
					window.setTimeout(function() {
						$("div.result").click();
					}, 500)
					// resultToClick = $("div.result p:contains('"+value[0]+"')");
					// console.log(value[0]);
					// var i = 0;
					// while(resultToClick.text() != value[0])
					// {
					// 	console.log(resultToClick);
					// 	console.log(value[0]);
					// 	resultToClick = $("div.result p:contains('"+value[0]+"')");
					// 	i++;
					// 	if(i >= 1000)
					// 	{
					// 		break;
					// 	}
					// }
				}
			});
		}
		if (nvPairs.length == 2)
		{
			$.ajax({
				type: "POST",
				url: '../scripts/request.php',
				data: {label:value[0]},
				dataType: 'json',
				success: function(data)
				{
					$.each(data, function(index, value2) {
						console.log(value2);
						if(value2.event == value[1])
						{
							choice = index;
						}
						else if(value2.artist == value[1])
						{
							choice = index;
						}
					});
					console.log(choice);
					var playerTitle = data[choice].artist + " - " + data[choice].event;
					$('#jp_player_title').text(playerTitle);
					$('#jquery_jplayer_1').jPlayer("setMedia", {
						mp3: "uploads/"+data[choice].songURL
					});
					$('#thumbnail').css('background-image', "url('../uploads/"+data[choice].imageURL+"')");
					$('.duration').show();
					$('#jquery_jplayer_1').jPlayer('play');
					$(".fb-share-button").attr("data-href", urlstring);
					FB.XFBML.parse();
					var offset = $(".welcome-holder").height();
					$('.scroll-wrapper').scrollTo("div.stredming-wrapper", 500, {offset:-offset});
					mixpanel.track("Specific Set Play");
					var timer = $.timer(function() {
						mixpanel.track("Specific Set Played for 5 Minutes");
					}, 5000, false);
					timer.once(300000);
				}
			});
		}
	}
	function openPanel(result) 
	{
		var activeColumn = result.parent().parent().parent().parent();
		var infoPanel = $("<div>").append("<div class='info-panel-container'><div class='info-panel'><div class='ui-widget search-container'></div><div class='tiles-wrapper'></div></div></div>");
		infoPanel.addClass("tile-selection-wrapper");
		if(animationIsActive == false)
		{
			$(".tile-selection-wrapper").remove();
			animationIsActive = true;
			activeHeader = activeColumn.find("h1");
			activeColumn.siblings(".column-wrapper").css({"opacity":"0","max-width":"0px"});
			result.siblings().css("box-shadow","");
			result.css("box-shadow","0 0 3px 7px white inset");
			activeHeader.css("opacity","0");
			window.setTimeout(function(){
				activeHeader.hide();
				$(".back-to-results").show().hover(function(){
					$(".back-to-results").css("box-shadow","0 0 3px 2px inset");
				}, function(){
					$(".back-to-results").css("box-shadow","0 0 0 0 inset");
				});
				activeColumn.siblings(".column-wrapper").hide();
			}, 300);
			infoPanel.appendTo(".autocomplete-container");
			infoPanel.find(".info-panel").css("background-image", result.css("background-image"));
			window.setTimeout(function () {
				window.setTimeout(function () {
					panelOpen = true;
					var inputBox = $("<input class='ui-autocomplete-input' id='info-search' placeholder='Type to filter results'>").appendTo(".search-container");
					$("#main-search").blur();
					animationIsActive = false;
					inputBox.slideDown(100);
					inputBox.focus();
					// Live Code Start
					matchedTags = [];
					urlArray = [];
					imgArray = [];
					$.ajax({
						type: "POST",
						url: '../scripts/request.php',
						data: {label:result.text()},
						dataType: 'json',
						success: function(data) 
						{
							$.each(data, function(index, value) {
								valueArray[index] = value;
								urlArray[index] = value.songURL;
								imgArray[index] = value.imageURL;
								var title = "";
								if(activeColumn.attr("id") == "artist-wrapper") {
									title = value.event;
								} else if(activeColumn.attr("id") == "genre-wrapper") {
									title = value.artist + " - " + value.event;
								} else {
									title = value.artist;
								}
								matchedTags[index] = title;
							});
						},
						complete: function() 
						{
							var infoACWidget = inputBox.autocomplete({
								minLength: 0,
								messages: {
									noResults: 'No results found',
									results: function() {}
								},
								delay: 100,
								source: matchedTags,
								response: function(event, ui) {
									var objectArray = ui.content;
									var selector = "";
									$("ul.ui-autocomplete").remove();
									$.each(objectArray, function(index, value) {
										if(index != 0)
										{
											selector = selector.concat(", ")
										}
										selector = selector.concat("div.panel-result[data-filter='"+value.label+"']");
									});
									panelIsotope.isotope({filter: selector});
								}
							});
							infoACWidget.click(function() {
								closePanel();
							});
							createPanelResults(activeColumn.attr("id"), generatePanelTiles());
						}
					});
					// Live Code End

				},300);
			},300);
		}
		activeColumn.find(".back-to-results").click(function() {
			if(animationIsActive == false)
			{
				result.css("box-shadow","");
				$(".results-container").empty();
				$(".autocomplete-container").empty();
				updateResults();
			}
		});
	}
	function closePanel()
	{
		$(".tile-selection-wrapper").css("width", "0%");
	}
	function animateColumns()
	{
		$(".column-wrapper").css("margin-left","0");
	}
	function updateResults()
	{
		var panelOpen = false;
		var g = generateArtistTiles();
		if(!(g[0]))
		{
			columnCreate(g[1], artistTiles);
		}
		g = generateEventTiles();
		if(!(g[0]))
		{
			columnCreate(g[1], eventTiles);
		}
		g = generateRadiomixTiles();
		if(!(g[0]))
		{
			columnCreate(g[1], radiomixTiles);
		}
		g = generateGenreTiles();
		if(!(g[0]))
		{
			columnCreate(g[1], genreTiles);
		}
		window.setTimeout(function() {
			$(".column-wrapper").css("margin-left","0");
		},100);
	}
	function generatePanelTiles()
	{
		var panelTiles = new Array();
		$.each(matchedTags, function(index, value) {
			panelTiles.push($("<div class='panel-result' data-filter='"+value+"'><p>"+value+"</p></div>"));
		});
		return panelTiles;
	}
	function generateArtistTiles()
	{
		artistTiles = new Array();
		var isEmpty = true;
		$.each(searchTiles, function(index, value) {
			if($.inArray(value.text(), artistArray) != -1)
			{
				artistTiles.push(value);
				isEmpty = false;
			}
		});
		tiles[0] = artistTiles;
		return [isEmpty, "artist"];
	}
	function generateEventTiles()
	{
		eventTiles = new Array();
		var isEmpty = true;
		$.each(searchTiles, function(index, value) {
			if($.inArray(value.text(), eventArray) != -1)
			{
				eventTiles.push(value);
				isEmpty = false;
			}
		});
		tiles[1] = eventTiles;
		return [isEmpty, "event"];
	}
	function generateRadiomixTiles() {
		radiomixTiles = new Array();
		var isEmpty = true;
		$.each(searchTiles, function(index, value) {
			if($.inArray(value.text(), radiomixArray) != -1)
			{
				radiomixTiles.push(value);
				isEmpty = false;
			}
		});
		tiles[2] = radiomixTiles;
		return [isEmpty, "radiomix"];
	}
	function generateGenreTiles() {
		genreTiles = new Array();
		var isEmpty = true;
		$.each(searchTiles, function(index, value) {
			if($.inArray(value.text(), genreArray) != -1)
			{
				genreTiles.push(value);
				isEmpty = false;
			}
		});
		tiles[3] = genreTiles;
		return [isEmpty, "genre"];
	}
	var backspaceDetect;
	var searchTiles = new Array();
	var tiles = new Array();
	var artistTiles = new Array();
	var eventTiles = new Array();
	var radiomixTiles = new Array();
	var genreTiles = new Array();
	var miscTiles = new Array();
	var animationIsActive = false;
	var panelOpen = false;
	var urlArray = new Array();
	var matchedTags = new Array();
	var valueArray = new Array();
	var panelIsotope = null;
	var artistArray = new Array()
	var eventArray = new Array()
	var radiomixArray = new Array()
	var genreArray = new Array()
	var autocompleteTags = new Array();
	var pageLoaded = false;
	var screenWidth = $("html").width();
	getAllTags();
	var mainACWidget = $("#main-search").autocomplete({
		minLength: 1,
		delay: 100
	});
	mainACWidget.autocomplete({
		source: autocompleteTags,
		messages: {
			noResults: 'No results found',
			results: function() {}
		}
	});
	mainACWidget.autocomplete({
		search: function(event, ui) {
			$(".autocomplete-container").css("margin-left","0");
		},
		response: function(event, ui) {
			animateWelcomeBar();
			window.setTimeout(function() {
				$("ul.ui-autocomplete").remove();
				$(".ui-helper-hidden-accessible").remove();
				var objectArray = ui.content;
				searchTiles = new Array();
				$.each(objectArray, function(index, value) {
					searchTiles.push($("<div class='result'><p>"+value.label+"</p></div>"));
				});
				$(".column-wrapper").css("margin-left","-200vw")
				window.setTimeout(function() {
					$(".results-container").empty();
					$(".column-wrapper").remove();
					$(".tile-selection-wrapper").remove();
					updateResults();
				}, 200);
			}, 100);	
		}
	});
	$("#main-search").keyup(function(e) {
		key = e.keyCode || e.charCode;
		if((key == 8 || key == 46) && mainACWidget.val() == "")
		{
			mainACWidget.autocomplete("option","minLength",0);
			mainACWidget.autocomplete("search","");
			mainACWidget.autocomplete("option","minLength",1);
		}
	});
	$('#nav').onePageNav({
	    currentClass: 'current',
	    changeHash: true,
		easing: 'swing',
		filter: '',
		scrollSpeed: 750,
		scrollOffset: 0,
		scrollThreshold: 1.5,
		begin: false,
		end: false,
		scrollChange: false
	 });
	$("#aftermovie").click(function() {
		$("#f1_card").toggleClass("flipped");
	});
	$("#browse").click(function() {
		if(screenWidth < 568)
		{
			$('.scroll-wrapper').scrollTo(".autocomplete-wrapper", 500);
		}
		else
		{
			$('.scroll-wrapper').scrollTo(".home-page-wrapper", 500);
		}
		animateWelcomeBar();
		window.setTimeout(function() {
			mainACWidget.autocomplete("option","minLength",0);
			mainACWidget.autocomplete("search","");
			mainACWidget.autocomplete("option","minLength",1);
		}, 300);
	});
	$("#random-set").click(function() {
		$.ajax({
			url: '../scripts/requestRandom.php',
			dataType: 'json',
			success: function(data)
			{
				var link = "?artist="+data.artist+"&event="+data.event;
				window.history.replaceState("string", "Title", link);
				var title = data.artist + " - " + data.event;
				$('#jp_player_title').text(title);
				$('#jquery_jplayer_1').jPlayer("setMedia", {
					mp3: "uploads/"+data.songURL
				});
				$('#thumbnail').css('background-image', "url('../uploads/"+data.imageURL+"')");
				$('.duration').show();
				$('#jquery_jplayer_1').jPlayer('play');
				$(".fb-share-button").attr("data-href", "http://stredm.com/"+link);
				FB.XFBML.parse();
				var offset = $(".welcome-holder").height();
				$('.scroll-wrapper').scrollTo("div.stredming-wrapper", 500, {offset:-offset});
 				mixpanel.track("Random Set Play");
 				var timer = $.timer(function() {
						mixpanel.track("Random Set Played for 5 Minutes");
				}, 5000, false);
				timer.once(300000);
			}
		});
	});
	$(document).mousemove(function() {
		$(".panel-results-container").css("overflow-y","scroll");
	});
	$(".navmenu:nth-child(1)").click(function(){
		$(".scroll-wrapper").scrollTo("#section-1", 500, {offset:-($("body").height()*.07)});
	});
	$(".navmenu:nth-child(2)").click(function(){
		$(".scroll-wrapper").scrollTo("#section-2", 500, {offset:-($("body").height()*.07)});
	});
	$(".navmenu:nth-child(3)").click(function(){
		$(".scroll-wrapper").scrollTo("#section-3", 500, {offset:-($("body").height()*.07)});
	});
	$(".navmenu:nth-child(4)").click(function(){
		$(".scroll-wrapper").scrollTo("#section-4", 500, {offset:-($("body").height()*.07)});
	});
	if((window.location.search.length > 0) && (pageLoaded == false))
	{
		playSet();
	}
	$("#logo-container").click(function() {
		location.reload();
	});
	$("#main-header").click(function() {
		location.reload();
	});
	pageLoaded = true;
	mainACWidget.click(function()
	{
		$('.scroll-wrapper').scrollTo(".home-page-wrapper", 500);
	});
	$(".scroll-wrapper").on("scroll", function() {
		if($(".scroll-wrapper").scrollTop() >= $(".welcome-wrapper").innerHeight()-$(".welcome-wrapper").height())
		{
			animateWelcomeBar();
		}
	});
});