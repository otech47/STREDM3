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
		columnCode.find("h1").append((columnType.title).toString());
		columnCode.find(".results-container").attr("id", (columnType.id).toString());
		columnCode.attr("id", type.toString()+"-wrapper");
		columnCode.attr("style", "margin-left: -500px");
		$(".loader-container").remove();
		columnCode.appendTo(".autocomplete-container");
		$.each(tileName, function (index, value) {
			var a = value.appendTo("#"+columnType.id).addClass("result");
			a.css("background-image", randomColor());
			a.click(function() {
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
				// console.log(data);
				var playerTitle = valueArray[index].artist + " - " + valueArray[index].event;
				$('#jp_player_title').text(playerTitle);
				$('#jquery_jplayer_1').jPlayer("setMedia", {
					mp3: "uploads/"+a.attr('data-url')
				});
				$('#thumbnail').css('background-image', "url('../uploads/"+a.attr('data-img')+"')");
				$('.duration').show();
				$('#jquery_jplayer_1').jPlayer('play');
				$('.scroll-wrapper').scrollTo("div.stredming-wrapper", 500);
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
			result.css("box-shadow","0 0 3px 7px white");
			result.siblings().css("box-shadow","0 0 1px 1px");
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
							console.log(data);
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
				result.css("box-shadow","0 0 1px 1px");
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
			$(".loader-container").remove();
			$(".column-wrapper").css("margin-left","0");
		},1);
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
	getAllTags();
	var mainACWidget = $("#main-search").autocomplete({
		minLength: 1,
		delay: 100
	});
	mainACWidget.autocomplete({
		source: autocompleteTags
	});
	mainACWidget.select();
	mainACWidget.autocomplete({
		search: function(event, ui) {
			var loader = $("<div class='loader-container'><div class='loader'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div><div class='loader-text'>Loading...</div></div>");
			loader.appendTo(".autocomplete-container");
		},
		response: function(event, ui) {
			$("ul.ui-autocomplete").remove();
			var objectArray = ui.content;
			searchTiles = new Array();
			$.each(objectArray, function(index, value) {
				searchTiles.push($("<div class='result'><p>"+value.label+"</p></div>"));
			});
			$(".results-container").empty();
			$(".column-wrapper").remove();
			$(".tile-selection-wrapper").remove();
			updateResults();
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
	$("input#main-search").bind('blur', function(){
		if($("#main-search").val().length == 0) {
			$("#main-search").css("margin-right", "-400px");
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
		mainACWidget.autocomplete("option","minLength",0);
		mainACWidget.autocomplete("search","");
		mainACWidget.autocomplete("option","minLength",1);
	});
	$("#random-set").click(function() {
		$.ajax({
			url: '../scripts/requestRandom.php',
			dataType: 'json',
			success: function(data)
			{
				// console.log(data);
				// console.log(data.is_radiomix);
				var title = data.artist + " - " + data.event;
				$('#jp_player_title').text(title);
				$('#jquery_jplayer_1').jPlayer("setMedia", {
					mp3: "uploads/"+data.songURL
				});
				$('#thumbnail').css('background-image', "url('../uploads/"+data.imageURL+"')");
				$('.duration').show();
				$('#jquery_jplayer_1').jPlayer('play');
				$('.scroll-wrapper').scrollTo("div.stredming-wrapper", 500);
 				mixpanel.track("Random Set Play");
 				var timer = $.timer(function() {
					mixpanel.track("Random Set Played for 5 Minutes");
				});
				$("#jquery_jplayer_1").bind($.jPlayer.event.pause, function(event) {
					timer.pause();
				});
				$("#jquery_jplayer_1").bind($.jPlayer.event.play, function(event) {
					timer.once(300000);
				});
			}
		});
	});
	$(document).mousemove(function() {
		$(".panel-results-container").css("overflow-y","scroll");
	});
	$(".navmenu:nth-child(1)").click(function(){
		$(".scroll-wrapper").scrollTo("#section-1", 500);
	});
	$(".navmenu:nth-child(2)").click(function(){
		$(".scroll-wrapper").scrollTo("#section-2", 500);
	});
	$(".navmenu:nth-child(3)").click(function(){
		$(".scroll-wrapper").scrollTo("#section-3", 500);
	});
	$(".navmenu:nth-child(4)").click(function(){
		$(".scroll-wrapper").scrollTo("#section-4", 500);
	});
});