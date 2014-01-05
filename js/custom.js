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
		var autocompleteData = [ "Ultra Music Festival 2013", "Fedde Le Grand", "Above and Beyond", "Beyond Wonderland 2013" ];
		acWidget.autocomplete({
			source: autocompleteData
		});
		acWidget.select();
	}
	function updateResults()
	{
		updateArtists();
		// updateEvents();
		// updateRadiomixes();
		// updateGenres();
		// updateMiscs();
	}
	function updateArtists()
	{
		var postdata = { array:searchArray };
		var artistArray = new Array();
		$.ajax({
			type: "POST",
			url: '../scripts/filterArtists.php',
			data: postdata,
			success: function(data, status)
			{
				alert(status);
			}
		});
	}
	var searchArray = new Array();
	var acWidget = $("#select-combined").autocomplete({
		minLength: 0,
		position: { my: "left top+10", at: "left top", of: ".artist-column"},
	});
	acWidget.click(function() {
		getAllTags();
	});
	acWidget.autocomplete({
		response: function(event, ui) {
			var objectArray = ui.content;
			searchArray = new Array();
			$.each(objectArray, function(index, value) {
				searchArray.push(value.label);
			});
			updateArtists();
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
});