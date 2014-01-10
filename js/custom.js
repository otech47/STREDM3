$(document).ready( function() {
	function Queue(arr) 
	{
		var i = 0;
		this.callNext = function() { 
			typeof arr[i] == 'function' && arr[i++]();
		};
	}
	function randomColor() {
    	var letters = '0123456789ABCDEF'.split('');
    	var color = '#';
    	for (var i = 0; i < 6; i++ ) {
     	   color += letters[Math.round(Math.random() * 15)];
    	}
    	return color;
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
		var autocompleteData = [ "Ultra Music Festival 2013", "Fedde Le Grand", "Above and Beyond", "Beyond Wonderland 2013", "Tomorrowland 2013", "EDC Las Vegas 2013", "Hardwell", "Dimitri Vegas and Like Mike", "Calvin Harris" ];
		mainACWidget.autocomplete({
			source: autocompleteData
		});
		mainACWidget.select();
	}
	function getMatchedTags(label)
	{
		var results = ["Hardwell", "Calvin Harris", "Deadmau5", "Swedish House Mafia"];
		return results;
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
		var columnCode = $("<div class='column-wrapper'><div class='autocomplete-column'><h1></h1><div class='results-wrapper'><div class='results-container'></div></div></div></div>");
		columnCode.find("h1").append((columnType.title).toString());
		columnCode.find(".results-container").attr("id", (columnType.id).toString());
		columnCode.attr("id", type.toString()+"-wrapper");
		columnCode.attr("style", "margin-left: -500px");
		columnCode.appendTo(".autocomplete-container");
		$.each(tileName, function (index, value) {
			var a = value.appendTo("#"+columnType.id).addClass("result");
			a.css("background-color", randomColor())
			a.mouseenter(function() {
				openPanel(a);
			});
		});
		$(".results-container").isotope({
			itemSelector : ".result",
			layoutMode : "masonry"
	})
	}
	function createPanelResults(type, tileName)
	{
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
		if(animationIsActive == false && !panelOpen)
		{
			$(".tile-selection-wrapper").remove();
			animationIsActive = true;
			activeColumn.css("width","25%");
			activeColumn.siblings(".column-wrapper").css("margin-left","-800px");
			result.siblings().fadeOut(100);
			result.css("height", "80%");
			infoPanel.appendTo(".autocomplete-container");
			infoPanel.find(".info-panel").css("background-color", result.css("background-color"));
			window.setTimeout(function () {
				infoPanel.css("width","100%");
				window.setTimeout(function () {
					panelOpen = true;
					var inputBox = $("<input class='ui-autocomplete-input' id='info-search' placeholder='Type to filter results'>").appendTo(".search-container");
					$("#q").blur();
					animationIsActive = false;
					inputBox.slideDown(100);
					inputBox.focus();
					matchedTags = getMatchedTags(result.text());
					var infoACWidget = inputBox.autocomplete({
						minLength: 0,
						source: matchedTags
					});
					infoACWidget.click(function() {
						alert(matchedTags);
					});
					createPanelResults(activeColumn.find("h1").text().toLowerCase(), generatePanelTiles(matchedTags));
				},300);
			},300);
		}
		activeColumn.find("h1").mouseenter(function() {
			result.css("height", "30%");
			result.siblings().fadeIn(100);
			panelOpen = false;
		});
		// infoACWidget.click(function() {
		// 	alert("read");
		// 	getAllTags();
		// });
		// infoACWidget.autocomplete({
		// 	response: function(event, ui) {
		// 		var objectArray = ui.content;
		// 		$("#ui-id-2").remove();
		// 		$.each(objectArray, function(index, value) {
		// 			searchTiles.push($("<div class='panel-result'><p>"+	value.label+"</p></div>"));
		// 			searchTiles[index] = searchTiles[index].wrap("<div></div>");
		// 		});
		// 		$(".panel-results-container").empty();
		// 		$(".tiles-wrapper").empty();
		// 		updatePanelResults();
		// 	}
		// });
	// 	var infoTiles = {"type":$(
	// 	columnCreate(type, 
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
			$(".column-wrapper").css("margin-left","0");
		},1);
		// var test = $('.results-container').isotope({
			// layoutMode : "masonry"
		// });
		// updateRadiomixes();
		// updateGenres();
		// updateMiscs();
	}
	function generatePanelTiles(tags)
	{
		var panelTiles = new Array();
		$.each(tags, function(index, value) {
			panelTiles[index] = $("<div class='panel-result'><p>"+value+"</p></div>");
		})
		return panelTiles;
	}
	function generateArtistTiles()
	{
		artistTiles = new Array();
		var isEmpty = true;
		// $.ajax({
			// type: "GET",
			// url: "scripts/getAllArtists.php",
			// success: function(data)
			// {
				// artistArray = data;
			// }
		// });
		var artistArray = ["Above and Beyond", "Fedde Le Grand", "Hardwell", "Dimitri Vegas and Like Mike", "Calvin Harris" ];
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
		// $.ajax({
			// type: "GET",
			// url: "scripts/getAllArtists.php",
			// success: function(data)
			// {
				// artistArray = data;
			// }
		// });
		var eventArray = ["Ultra Music Festival 2013", "Beyond Wonderland 2013", "Tomorrowland 2013", "EDC Las Vegas 2013"];
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
	var stredm = function ()
	{
		$(".stredming-wrapper").css("display","block");
		$('.scroll-wrapper').animate({scrollTop: $(document).height()}, "1000");
	};
	var mainACWidget = $("#q").autocomplete({
		minLength: 0
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
	mainACWidget.click(function() {
		getAllTags();
	});
	mainACWidget.autocomplete({
		response: function(event, ui) {
			var objectArray = ui.content;
			if(backspaceDetect.keyCode == 8 && $("#q").val().length == 0)
			{
				$(".column-wrapper").css("margin-left", "-500px");
				$(".tile-selection-wrapper").remove();
				return;
			}
			$("#ui-id-1").remove();
			searchTiles = new Array();
			$.each(objectArray, function(index, value) {
				searchTiles.push($("<div class='result'><p>"+value.label+"</p></div>"));
			});
			$(".results-container").empty();
			$(".autocomplete-container").empty();
			updateResults();
		}
	});
	$("#q").keyup( function(e) {
		backspaceDetect = e;
	});
	$("#search-button").mouseenter(function(){
		$("#q").css("margin-right", "200px");
	});
	$("input#q").bind('blur', function(){
		if($("#q").val().length == 0)
		{
			$("#q").css("margin-right", "-400px");
		}
	});
});