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
	function columnUp()
	{
		function f1() {$("#artist-wrapper").css("padding-top", "0");}
		function f2() {$("#event-wrapper").css("padding-top", "0");}
		function f3() {$("#radiomix-wrapper").css("padding-top", "0");}
		function f4() {$("#genre-wrapper").css("padding-top", "0");}
		function f5() {$("#misc-wrapper").css("padding-top", "0");}
		var queue = new Queue([f1, f2, f3, f4, f5]);
		queue.callNext();
		queue.callNext();
		queue.callNext();
		queue.callNext();
		queue.callNext();
	}
	function updateResults()
	{
		updateArtists();
		// alert("e");
		updateEvents();
		// var test = $('.results-container').isotope({
			// layoutMode : "masonry"
		// });
		// alert("aaa");
		// updateRadiomixes();
		// updateGenres();
		// updateMiscs();
	}
	function updateArtists()
	{
		var artistArray = new Array();
		// $.ajax({
			// type: "GET",
			// url: "scripts/getAllArtists.php",
			// success: function(data)
			// {
				// artistArray = data;
			// }
		// });
		artistArray = ["Above and Beyond", "Fedde Le Grand", "Hardwell", "Dimitri Vegas and Like Mike", "Calvin Harris" ];
		var arrayToAdd = new Array();
		$.each(searchArray, function(index, value) {
			if($.inArray(value, artistArray) != -1)
			{
				tiles[index] =(tiles[index]).appendTo("#rc-1");
				(tiles[index]).addClass("result");
				(tiles[index]).css("height","40%");
			}
		});
	}
	function updateEvents()
	{
		var eventArray = new Array();
		// $.ajax({
			// type: "GET",
			// url: "scripts/getAllArtists.php",
			// success: function(data)
			// {
				// artistArray = data;
			// }
		// });
		eventArray = ["Ultra Music Festival 2013", "Beyond Wonderland 2013", "Tomorrowland 2013", "EDC Las Vegas 2013"];
		var arrayToAdd = new Array();
		$.each(searchArray, function(index, value) {
			if($.inArray(value, eventArray) != -1)
			{
				tiles[index] = (tiles[index]).appendTo("#rc-2");
				(tiles[index]).addClass("result");
				(tiles[index]).css("height","40%");
			}
		});
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
	var column;
	var currentResult;
	acWidget.click(function() {
		getAllTags();
	});
	acWidget.autocomplete({
		response: function(event, ui) {
			var objectArray = ui.content;
			if(backspaceDetect.keyCode == 8 && $("#select-combined").val().length == 0)
			{
				$(".column-wrapper").css("padding-top", "400px");
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
			columnUp();
			updateResults();
		}
	});
	$("#select-combined").keyup( function(e) {
		backspaceDetect = e;
	});
	$("div.results-container").delegate(".result", "click", stredm);
	$("div.results-container").delegate(".result", "mouseenter", function() {
		currentResult = $(this);
		column = $(this).parent().parent().parent().parent();
		// column.children(".autocomplete-column").fadeOut(500, function (){
			// $(".tile-selection-wrapper").fadeIn();
		// });
		column.css("float","left");
		var columnWrappers = $(".column-wrapper");
		$.each(columnWrappers, function(index,value) {
			if(value.id != column.attr('id'))
			{
				$(document.getElementById(value.id)).css("display","none");
			}
		});
		column.css("width","100%");
	});
	$("div.autocomplete-wrapper").on("mouseleave", function() {
		if(column.width()/column.parent().width()*100 == "100") 
		{
			$(".column-wrapper").css("padding-top", "400px");
			// $(".tile-selection-wrapper").fadeOut(200, function (){
				// column.children(".autocomplete-column").fadeIn();
			// });
			column.css("float","none");
			column.css("width","19.5%");
			window.setTimeout(function() {
				var columnWrappers = $(".column-wrapper");
				$.each(columnWrappers, function(index,value) {
					$(document.getElementById(value.id)).css("display","inline-block");
				});
				columnUp();
			},500);
		}
		else
		{
			window.setTimeout(function() {
				$(".column-wrapper").css("padding-top", "400px");
				column.css("float","none");
				column.css("width","19.5%");
				window.setTimeout(function() {
					var columnWrappers = $(".column-wrapper");
					$.each(columnWrappers, function(index,value) {
						$(document.getElementById(value.id)).css("display","inline-block");
					});
					columnUp();
				},500);
			}, 500);
		}
	});
	$("div.stredming-tracklist").click(function(){
		var player = SC.Widget(document.getElementById('current-result'));
		player.pause();
	});
	$("#current-result").ready( function() {
		var player = SC.Widget(document.getElementById('current-result'));
		player.play();
	});



	$("#search-button").mouseenter(function(){
		$("#q").css("margin-right", "200px");
	});
	$("form.navbar-form").mouseleave(function(){
		$("#q").css("margin-right", "-400px");
	});



});