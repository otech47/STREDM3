$(document).ready( function() {
	function Queue(arr) 
	{
		var i = 0;
		this.callNext = function() { 
			typeof arr[i] == 'function' && arr[i++]();
		};
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
		acWidget.autocomplete({
			source: autocompleteData
		});
		acWidget.select();
	}
	function columnCreate(type, tileName)
	{
		var columnType;
		var results = new Array();
		if(type == "artist")
			columnType = {title:"Artist", id:"rc-1"};
		if(type == "event")
			columnType = {title:"Event", id:"rc-2"};
		if(type == "radiomix")
			columnType = {title:"Radio Mix", id:"rc-3"};
		if(type == "genre")
			columnType = {title:"Genre", id:"rc-4"};
		if(type == "misc")
			columnType = {title:"Miscellaneous", id:"5"};
		// function f1() {$("#artist-wrapper").css("padding-top", "0");}
		// function f2() {$("#event-wrapper").css("padding-top", "0");}
		// function f3() {$("#radiomix-wrapper").css("padding-top", "0");}
		// function f4() {$("#genre-wrapper").css("padding-top", "0");}
		// function f5() {$("#misc-wrapper").css("padding-top", "0");}
		// var queue = new Queue([f1, f2, f3, f4, f5]);
		// queue.callNext();
		// queue.callNext();
		// queue.callNext();
		// queue.callNext();
		// queue.callNext();
		var columnCode = $("<div>").append("<div class='autocomplete-column'><h1>" +columnType.title+ "</h1><div class='results-wrapper'><div class='results-container' id='"+columnType.id+"'></div></div></div></div>");
		columnCode.addClass("column-wrapper");
		columnCode.attr("id", type+"-wrapper");
		columnCode.attr("style", "margin-left: -500px");
		columnCode = columnCode.appendTo(".autocomplete-container");
		$.each(tileName, function (index, value) {
			var a = value.appendTo("#"+columnType.id);
			a.addClass("result");
			openPanel(a);
		});
		return results;
	}
	function openPanel(result) 
	{
		var activeColumn = result.parent().parent().parent().parent();
		var infoPanel = $("<div>").append("<div class='info-panel'></div>");
		infoPanel.addClass("tile-selection-wrapper");
		result.mouseenter(function() {
			if(animationIsActive == false)
			{
				animationIsActive = true;
				activeColumn.siblings(".column-wrapper").css("margin-left","-600px");
				result.css("height", "80%");
				infoPanel.appendTo(".autocomplete-container");
				window.setTimeout(function () {
					infoPanel.css("width","100%");
					animationIsActive = false;
				},300);
			}
		});
		activeColumn.find("h1").mouseenter(function() {
			result.css("height", "30%");
		});
	}
	function animateColumns()
	{
		$(".column-wrapper").css("margin-left","0");
	}
	function updateResults()
	{
		columnCodeArray = new Array();
		resultsCodeArray = new Array();
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
		// if(generateEventTiles()[0]);
			// columnCreate(eventTiles);
		// var test = $('.results-container').isotope({
			// layoutMode : "masonry"
		// });
		// alert("aaa");
		// updateRadiomixes();
		// updateGenres();
		// updateMiscs();
	}
	function generateArtistTiles()
	{
		var artistArray = new Array();
		var isEmpty = true;
		// $.ajax({
			// type: "GET",
			// url: "scripts/getAllArtists.php",
			// success: function(data)
			// {
				// artistArray = data;
			// }
		// });
		artistArray = ["Above and Beyond", "Fedde Le Grand", "Hardwell", "Dimitri Vegas and Like Mike", "Calvin Harris" ];
		artistTiles = new Array();
		$.each(searchArray, function(index, value) {
			if($.inArray(value, artistArray) != -1)
			{
				artistTiles.push(tiles[index]);
				isEmpty = false;
			}
		});
		return [isEmpty, "artist"];
	}
	function generateEventTiles()
	{
		var eventArray = new Array();
		var isEmpty = true;
		// $.ajax({
			// type: "GET",
			// url: "scripts/getAllArtists.php",
			// success: function(data)
			// {
				// artistArray = data;
			// }
		// });
		eventArray = ["Ultra Music Festival 2013", "Beyond Wonderland 2013", "Tomorrowland 2013", "EDC Las Vegas 2013"];
		eventTiles = new Array();
		$.each(searchArray, function(index, value) {
			if($.inArray(value, eventArray) != -1)
			{
				eventTiles.push(tiles[index]);
				isEmpty = false;
			}
		});
		return [isEmpty, "event"];
	}
	var stredm = function ()
	{
		$(".stredming-wrapper").css("display","block");
		$('.scroll-wrapper').animate({scrollTop: $(document).height()}, "1000");
	};
	var searchArray = new Array();
	var acWidget = $("#select-combined").autocomplete({
		minLength: 0,
	});
	var backspaceDetect;
	var tiles = new Array();
	var artistTiles = new Array();
	var eventTiles = new Array();
	var radiomixTiles = new Array();
	var genreTiles = new Array();
	var miscTiles = new Array();
	var column;
	var currentResult;
	var columnCodeArray;
	var resultsCodeArray;
	var animationIsActive = false;
	acWidget.click(function() {
		getAllTags();
	});
	acWidget.autocomplete({
		response: function(event, ui) {
			var objectArray = ui.content;
			if(backspaceDetect.keyCode == 8 && $("#select-combined").val().length == 0)
			{
				$(".column-wrapper").css("margin-left", "-500px");
				$(".tile-selection-wrapper").remove();
				return;
			}
			$("#ui-id-1").remove();
			searchArray = new Array();
			$.each(objectArray, function(index, value) {
				searchArray.push(value.label);
			});
			tiles = new Array();
			$.each(searchArray, function (index, value) {
				tiles.push($("<div>").append("<p>"+value+"</p>"));
			});
			$(".results-container").empty();
			$(".autocomplete-container").empty();
			updateResults();
		}
	});
	$("#select-combined").keyup( function(e) {
		backspaceDetect = e;
	});
	$(".main-search-wrapper").mouseenter(function() {
		acWidget.autocomplete("search");
	});



	$("#search-button").mouseenter(function(){
		$("#q").css("margin-right", "200px");
	});
	$("form.navbar-form").mouseleave(function(){
		$("#q").css("margin-right", "-400px");
	});



	$("#search-button").mouseenter(function(){
		$("#q").css("margin-right", "200px");
	});
	$("form.navbar-form").mouseleave(function(){
		$("#q").css("margin-right", "-400px");
	});



});