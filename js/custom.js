$(document).ready( function() {
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
		var autocompleteData = [ "Ultra Music Festival 2013", "Fedde Le Grand", "Above and Beyond", "Beyond Wonderland 2013", "Tomorrowland 2013", "EDC Las Vegas", "Hardwell", "Dimitri Vegas and Like Mike", "Calvin Harris" ];
		acWidget.autocomplete({
			source: autocompleteData
		});
		acWidget.select();
	}
	function updateResults()
	{
		$(".results-container").empty();
		$(".buffer").addClass("slideUp");
		updateArtists();
		updateEvents();
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
				$("<div class='result'>").append(value).appendTo("#rc-1");
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
		eventArray = ["Ultra Music Festival 2013", "Beyond Wonderland 2013", "Tomorrowland 2013", "EDC Las Vegas"];
		var arrayToAdd = new Array();
		$.each(searchArray, function(index, value) {
			if($.inArray(value, eventArray) != -1)
			{
				$("<div class='result'>").append(value).appendTo("#rc-2");
			}
		});
	}
	var searchArray = new Array();
	var acWidget = $("#select-combined").autocomplete({
		minLength: 0,
	});
	acWidget.click(function() {
		getAllTags();
	});
	acWidget.autocomplete({
		response: function(event, ui) {
			var objectArray = ui.content;
			if(document.getElementById("select-combined").val() == "")
			{
				
			}
			$("#ui-id-1").remove();
			searchArray = new Array();
			$.each(objectArray, function(index, value) {
				searchArray.push(value.label);
			});
			updateResults();
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
<<<<<<< HEAD

	$(function(){
		  $("#slides").slidesjs({
		    	 width: 275,
       			 height: 300,
		  });
		});
	
	$(function(){
  $("#slides").slidesjs({
    navigation: {
      active: true,
      effect: "slide"
    }
  });
});
=======
>>>>>>> d97dba50fdabac7a2056eb4da39c75e399c6c884
});