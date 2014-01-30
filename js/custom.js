$(document).ready( function() {
	function Queue(arr) 
	{
		var i = 0;
		this.callNext = function() { 
			typeof arr[i] == 'function' && arr[i++]();
		};
	}
	function randomColor() {
    	var choice = Math.floor(Math.random()*8);
    	var color = ["linear-gradient(to bottom, #e4f5fc 0%,#bfe8f9 20%,#9fd8ef 35%,#2ab0ed 100%)", "linear-gradient(to bottom, #f16f5c 13%,#f16f5c 20%,#f16f5c 26%,#f16f5c 36%,#f02f17 66%,#e73827 100%)", "linear-gradient(to bottom, #b3feff 0%,#4ae8f9 57%)", "linear-gradient(to bottom, #e2b1d8 0%,#dd40b6 63%,#de47ac 100%)", "linear-gradient(to bottom, #ffb76b 0%,#ffa73d 24%,#ff7c00 56%,#ff7f04 100%)", "linear-gradient(to bottom, #fcfeea 0%,#fcf944 100%)", "linear-gradient(to bottom, #fcfff4 0%,#dfe5d7 40%,#b3bead 100%)", "linear-gradient(to bottom, #b4e391 0%,#0fff02 51%,#65ed7a 100%)", "linear-gradient(to bottom, #ff5db1 38%,#ef017c 100%)"];
    	return color[choice];
	}
	function searchSet()
	{
		var searchString = window.location.search.substring(1);
		var searchArray = searchString.split('&');
		var temp1 = searchArray[0].split('=');
		var temp2 = searchArray[1].split('=');
		var event = temp1[1];
		var artist = temp2[1];
		event = event.replace(/%20/g, " ");
		artist = artist.replace(/%20/g, " ");
		var postdata = {
			event:event,
			artist:artist
			};
		jQuery.ajax({
			type: "POST",
			url: '../scripts/request.php',
			data: postdata,
			success: function(data) 
			{
				var result = data;
				$(".stredming-wrapper").css("display","block");
				$('.scroll-wrapper').animate({scrollTop: $(document).height()}, '1000');
				$(".stredming-player-container").slideDown(100);
				$(".stredming-result").empty();
				jQuery("div.stredming-result").append("<div class='result'>"+result+"</div>");
				var urlSrc = $("#current-result").attr("src");
				var urlSelection = urlSrc.substring(0, urlSrc.length-31);
				$(".stredming-tracklist").empty();
				var urlpostdata = {url:urlSelection}
				jQuery.ajax({
					type: "POST",
					url: '../scripts/requestTracklist.php',
					data: urlpostdata,
					success: function(data) 
					{
						var result = data;
						jQuery("div.stredming-tracklist").append("<div class='tracklist-result'>"+result+"</div>");
					}
				});
			}
		});
	}
	function getAllTags()
	{
		// Test Code Start
		// var autocompleteData = ["Hardwell", "Calvin Harris", "Deadmau5", "Armin Van Buuren", "Alesso", "Ultra Music Festival 2013", "EDC Las Vegas 2013", "Electric Zoo", "Tomorrowland 2013", "EDC Orlando 2013"];
		// mainACWidget.autocomplete({
		// 	source: autocompleteData
		// });
		// mainACWidget.select();
		// Test Code End

		// Live Code Start
		var autocompleteData = new Array();
		$.ajax({
			type: "GET",
			url: "../scripts/allTags.php",
			async: false,
			dataType: 'json',
			success: function(data)
			{
				$.each(data, function(index,value) {
					autocompleteData[index] = value;
				})
			},
			complete: function() 
			{
				mainACWidget.autocomplete({
					source: autocompleteData
				});
				mainACWidget.select();
			}
		});
		// Live Code End
	}
	function columnCreate(type, tileName)
	{
		var columnType = {title:"Empty", id:"rc-0"};
		if(type == "artist")
		{
			columnType = {title:"Artist", id:"rc-1"};
		}
		else if(type == "event")
		{	
			columnType = {title:"Event", id:"rc-2"};
		}
		else if(type == "radiomix")
		{	
			columnType = {title:"Radio Mix", id:"rc-3"};
		}
		else if(type == "genre")
		{	
			columnType = {title:"Genre", id:"rc-4"};
		}
		else
		{	
			columnType = {title:"Miscellaneous", id:"rc-5"};
		}
		var columnCode = $("<div class='column-wrapper'><div class='autocomplete-column'><h1></h1><div class='back-to-results'><div class='back fa-th fa-2x'></div><div class='back'>Show previous results...</div></div><div class='results-wrapper'><div class='results-container'></div></div></div></div>");
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
		if(type == "artist")
		{
			columnType = {title:"Event", id:"prc-2"};
		}
		else if(type == "event")
		{	
			columnType = {title:"Artist", id:"prc-1"};
		}
		var columnCode = $("<div class='panel-column-wrapper'><div class='panel-autocomplete-column'><div class='panel-results-wrapper'><div class='panel-results-container'></div></div></div></div>");
		columnCode.find(".panel-results-container").attr("id", (columnType.id).toString());
		columnCode.attr("id", type.toString()+"-panel-wrapper");
		columnCode.appendTo(".tiles-wrapper");
		$.each(tileName, function (index, value) {
			var a = value.appendTo("#"+columnType.id);
			a.attr("data-url", urlArray[index]);
			a.click(function(){
				$('.scroll-wrapper').animate({scrollTop: $("div.stredming-wrapper").offset().top-55}, '1000');
				window.setTimeout(function(){
					$("div.player-wrapper").empty();
					$("<div class='player-container'><div class='stredming-result'><iframe width='100%'' height='100%' scrolling='no' frameborder='no' src='"+a.attr('data-url')+"&amp;color=ff6600&amp;auto_play=true&amp;show_artwork=true'></iframe></div></div>").appendTo($("div.player-wrapper"));
				}, 1000);
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
					matchedTags = new Array();
					urlArray = new Array();
					$.ajax({
						type: "POST",
						url: '../scripts/request.php',
						data: {label:result.text()},
						dataType: 'json',
						success: function(data) 
						{
							$.each(data[0], function(index, value) {
								urlArray[index] = value;
							});
							$.each(data[1], function(index, value) {
								matchedTags[index] = value;
							});
						},
						complete: function() 
						{
							var infoACWidget = inputBox.autocomplete({
								minLength: 0,
								delay: 1000,
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
							createPanelResults(activeHeader.text().toLowerCase(), generatePanelTiles());
						}
					});
					// Live Code End

					// Test Code Start
					// matchedTags = ["1","2","3","4","5","6"];
					// var infoACWidget = inputBox.autocomplete({
					// 	minLength: 0,
					// 	source: matchedTags,
					// 	response: function(event, ui) {
					// 		var objectArray = ui.content;
					// 		var selector = "";
					// 		$("ul.ui-autocomplete").remove();
					// 		$.each(objectArray, function(index, value) {
					// 			if(index != 0)
					// 			{
					// 				selector = selector.concat(", ")
					// 			}
					// 			selector = selector.concat("div.panel-result[data-filter='"+value.label+"']");
					// 		});
					// 		panelIsotope.isotope({filter: selector});
					// 	}
					// });
					// createPanelResults(activeHeader.text().toLowerCase(), generatePanelTiles());
					// Test Code End

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
		// Test code start
		// artistTiles = new Array();
		// var isEmpty = true;
		// var artistArray = ["Hardwell","Calvin Harris","Deadmau5","Armin Van Buuren","Alesso"];
		// $.each(searchTiles, function(index, value) {
		// 	if($.inArray(value.text(), artistArray) != -1)
		// 	{
		// 		artistTiles.push(value);
		// 		isEmpty = false;
		// 	}
		// });
		// tiles[0] = artistTiles;
		// return [isEmpty, "artist"];
		// Test code end

		// Live code start
		artistTiles = new Array();
		var isEmpty = true;
		var artistArray = new Array();
		$.ajax({
			type: "GET",
			url: "../scripts/getAllArtists.php",
			async: false,
			dataType: 'json',
			success: function(data)
			{
				$.each(data, function(index,value) {
					artistArray[index] = value;
				})
			},
			complete: function() 
			{
				$.each(searchTiles, function(index, value) {
					if($.inArray(value.text(), artistArray) != -1)
					{
						artistTiles.push(value);
						isEmpty = false;
					}
				});
				tiles[0] = artistTiles;
			}
		});
		return [isEmpty, "artist"];
		// Live code end
	}
	function generateEventTiles()
	{
		// test code start
		// eventTiles = new Array();
		// var isEmpty = true;
		// var eventArray = ["Ultra Music Festival 2013", "EDC Las Vegas 2013", "Electric Zoo", "Tomorrowland 2013", "EDC Orlando 2013"];
		// $.each(searchTiles, function(index, value) {
		// 	if($.inArray(value.text(), eventArray) != -1)
		// 	{
		// 		eventTiles.push(value);
		// 		isEmpty = false;
		// 	}
		// });
		// tiles[1] = eventTiles;
		// return [isEmpty, "event"];
		// Test code end

		// live code start

		eventTiles = new Array();
		var isEmpty = true;
		var eventArray = new Array();
		$.ajax({
			type: "GET",
			url: "../scripts/getAllEvents.php",
			async: false,
			dataType: 'json',
			success: function(data)
			{
				$.each(data, function(index,value) {
					eventArray[index] = value;
				})
			},
			complete: function() 
			{
				$.each(searchTiles, function(index, value) {
					if($.inArray(value.text(), eventArray) != -1)
					{
						eventTiles.push(value);
						isEmpty = false;
					}
				});
				tiles[1] = eventTiles;
			}
		});
		return [isEmpty, "event"];

		// live code end
	}
	var mainACWidget = $("#main-search").autocomplete({
		minLength: 0,
		delay: 300
	});
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
	var panelIsotope = null;
	mainACWidget.click(function() {
		getAllTags();
	});
	mainACWidget.autocomplete({
		search: function(event, ui) {
			var loader = $("<div class='loader-container'><div class='loader'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div><div class='loader-text'>Searching...</div></div>");
			loader.appendTo(".autocomplete-container");
		},
		response: function(event, ui) {
			$("ul.ui-autocomplete").remove();
			var objectArray = ui.content;
			if(backspaceDetect.keyCode == 8 && $("#main-search").val().length == 0)
			{
				$(".loader-container").remove();
				$(".column-wrapper").css("margin-left", "-500px");
				$(".tile-selection-wrapper").remove();
				return;
			}
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
	$("#main-search").keyup( function(e) {
		backspaceDetect = e;
	});
	$("input#main-search").bind('blur', function(){
		if($("#main-search").val().length == 0)
		{
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
	$("#random-set").click(function() {
		$.ajax({
			url: '../scripts/requestRandom.php',
			dataType: 'json',
			success: function(data)
			{
				$('.scroll-wrapper').animate({scrollTop: $("div.stredming-wrapper").offset().top-55}, '800');
				$("div.player-wrapper").empty();
				$("<div class='player-container'><div class='stredming-result'>"+data+"</div></div>").appendTo($("div.player-wrapper"));
			}
		});
	});
});