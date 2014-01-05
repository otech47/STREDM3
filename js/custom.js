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
		acwidget.autocomplete({
			source: autocompleteData
		});
		acwidget.select();
		acwidget.autocomplete("search", "");
	}
	var acwidget = $("#select-combined").autocomplete( {
		minLength: 0,
		position: { my: "left top+10", at: "left top", of: ".autocomplete-wrapper"}
	});
	var resultsList = $("ul#ui-id-1");
	acwidget.click(function() {
		getAllTags();
	});
	// acwidget.autocomplete({open: function() {
			// var resultsList = $("ul#ui-id-1").remove();
			// $(".autocomplete-wrapper").append(resultsList);
		// }
	// }):
	$(".option-button").hover(function(){
		$(this).css("box-shadow","0 0 2px 5px grey inset")
		},
		function ()
		{
		$(this).css("box-shadow","0 0 0 0")
	});
	$(".back-button").hover(function(){
		$(this).css('cursor','default');
		$(this).css("box-shadow","0 0 2px 5px grey")
		},
		function ()
		{
		$(this).css("box-shadow","0 0 0 0")
	});
	$(".random-button").click(function(){
		$(".option-button").css("box-shadow","0 0 0 0");
		$(".option-label").animate({opacity:'0'}, 200, function() {
			$(".select-buttons").css("display","none");
			$(".random-search").css("display","block");
			$(".option-label").css("opacity","1");
			});
	});
	$(".specific-button").click(function(){
		$(".option-button").css("box-shadow","0 0 0 0");
		$(".option-label").animate({opacity:'0'}, 200, function() {
			$(".select-buttons").css("display","none");
			$(".specific-search").css("display","block");
			$(".option-label").css("opacity","1");
			});
	});
	$(".random-back-button").click(function(){
		$(".random-search").animate({opacity:'0'}, 200, function() {
			$(".random-search").css("display","none");
			$(".select-buttons").css("display","block");
			$(".random-search").css("opacity","1");
		});
	});
	$(".specific-back-button").click(function(){
		$(".specific-search").animate({opacity:'0'}, 200, function() {
			$(".specific-search").css("display","none");
			$(".select-buttons").css("display","block");
			$(".specific-search").css("opacity","1");
		});
	});
	$("button.specific-stredm").click(function(){
		mixpanel.track("Specific Stredm Click");
		var specificTimeout = setTimeout(function() {
			mixpanel.track("Specific Stredm for 5 Minutes");
		}, 300000);
		var eventSelection = $("input[id='events']").val();
		var artistSelection = $("input[id='artists']").val();
		var postdata = {
			event:eventSelection,
			artist:artistSelection
			};
		$(".stredming-wrapper").css("display","block");
		$('.scroll-wrapper').animate({scrollTop: $(document).height()}, '1000');
		$(".stredming-player-container").slideDown(100);
		$(".stredming-result").empty();
		window.location.search = "?event="+eventSelection+"&artist="+artistSelection;
		jQuery.ajax({
			type: "POST",
			url: '../scripts/request.php',
			data: postdata,
			success: function(data) 
			{
				var result = data;
				jQuery("div.stredming-result").append("<div class='result'>"+result+"</div>");
				stredm = false;
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
	});
	$("button.random-stredm").click(function(){
		mixpanel.track("Random Stredm Click");
		var randomTimeout = setTimeout(function() {
			mixpanel.track("Random Stredm for 5 Minutes");
		}, 300000);
		var selection = $("input[id='select-combined']").val();
		var postdata = {
			select:selection
			};
		$(".stredming-wrapper").css("display","block");
		$('.scroll-wrapper').animate({scrollTop: $(document).height()}, '1000');
		$(".stredming-player-container").slideDown(100);
		$(".stredming-result").empty();
		jQuery.ajax({
			type: "POST",
			url: '../scripts/requestRandom.php',
			data: postdata,
			success: function(data) 
			{
				var result = data;
				jQuery("div.stredming-result").append("<div class='result'>"+result+"</div>");
				stredm = false;
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
	});
	$("div.stredming-tracklist").click(function(){
		var player = SC.Widget(document.getElementById('current-result'));
		player.pause();
	});
	$("#current-result").ready( function() {
		var player = SC.Widget(document.getElementById('current-result'));
		player.play();
	});

	$(function(){
		  $("#slides").slidesjs({
		    navigation: {
		    	 width: 275,
       			 height: 300
		      	 active: true,
		      	 effect: "slide"
		    }
		  });
		});
});