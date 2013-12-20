/* 
 * Create HTML5 elements for IEs sake
 * Reference: http://ejohn.org/blog/html5-shiv/
 * Reference: http://remysharp.com/2009/01/07/html5-enabling-script/
 */

document.createElement("article");
document.createElement("footer");
document.createElement("header");
document.createElement("hgroup");
document.createElement("nav");

jQuery(document).ready(function(){
	jQuery("button.stredm_back_button").click(function(){
		jQuery("div.stredm_panel").slideUp(250, function() {
		jQuery("div.stredming").hide();
		});
	});
	jQuery("button.stredm_panel_button").click(function(){
		var eventselection = jQuery("select[name='event']").val();
		var artistselection = jQuery("select[name='artist']").val();
		var postdata = {
			event:eventselection,
			artist:artistselection
			};
		jQuery("div.featured_header").hide();
		jQuery("div.featured").hide();
		jQuery("div.stredming_container").slideDown(100, function() {
		jQuery("div.stredming_container").css("display:none","-webkit-flex");
		});
		jQuery("div.stredming_results").empty();
		jQuery.ajax({
			type: "POST",
			url: '/templates/siteground-j16-41/scripts/request.php',
			data: postdata,
			success: function(data) 
			{
				var result = data.substring(1, data.length-1);
				jQuery("div.stredming_results").append("<div class='result'>"+result+"</div>");
			}
		});
	});
	jQuery("button.stredming_back").click(function(){
		jQuery("div.stredming_container").slideUp(100, function(){
		jQuery("div.sets_column").show();
		jQuery("div.featured_header").show();
		jQuery("div.featured").show();
		});
	});
	jQuery("div.popular").hover(function(){
		jQuery("div.popular").css("text-decoration","underline");
		},
		function() {
		jQuery("div.popular").css("text-decoration","none");
	});
	jQuery("div.recent").hover(function(){
		jQuery("div.recent").css("text-decoration","underline");
		},
		function() {
		jQuery("div.recent").css("text-decoration","none");
	});
	jQuery("div.recent").click(function(){
		jQuery("div.list").empty();
		jQuery.ajax({
			type: "GET",
			url: '/templates/siteground-j16-41/scripts/recent.php',
			success: function(data) 
			{
				var result = data.substring(1, data.length-1);
				var resultArray = [];
				newResult = result;
				for(var i = 0;i<10;i++)
				{
					tempResult = newResult;
					end = tempResult.indexOf("\/iframe")+8;
					newResult = tempResult.substring(end);
					resultArray[i] = tempResult.substring(0,end);
				}
				for(var i = 0;i<10;i++)
				{
					jQuery("div.list").append("<div class='result'>"+resultArray[i]+"</div>");
				}	
			}
		});
	});
	jQuery("div.popular").click(function(){
		jQuery("div.list").empty();
		jQuery.ajax({
			type: "GET",
			url: '/templates/siteground-j16-41/scripts/popular.php',
			success: function(data) 
			{
				var result = data.substring(1, data.length-1);
				var resultArray = [];
				newResult = result;
				for(var i = 0;i<10;i++)
				{
					tempResult = newResult;
					end = tempResult.indexOf("\/iframe")+8;
					newResult = tempResult.substring(end);
					resultArray[i] = tempResult.substring(0,end);
				}
				for(var i = 0;i<10;i++)
				{
					jQuery("div.list").append("<div class='result'>"+resultArray[i]+"</div>");
				}	
			}
		});
	});
	jQuery.ajax({
		type: "GET",
		url: '/templates/siteground-j16-41/scripts/recent.php',
		success: function(data) 
		{
			var result = data.substring(1, data.length-1);
			var resultArray = [];
			newResult = result;
			for(var i = 0;i<10;i++)
			{
				tempResult = newResult;
				end = tempResult.indexOf("\/iframe")+8;
				newResult = tempResult.substring(end);
				resultArray[i] = tempResult.substring(0,end);
			}
			for(var i = 0;i<10;i++)
			{
				jQuery("div.list").append("<div class='result'>"+resultArray[i]+"</div>");
			}	
		}
	});
});
	