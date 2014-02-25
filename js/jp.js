	<script>
	$(document).ready(function() {
		$('#stredm-audio').jPlayer({
		 ready: function () {
		 $(this).jPlayer("setMedia", {
			mp3:"/uploads/3b15c8d20a48b4a488db06807abaeb075f59fe79.mp3"
		 });
		 },
		 swfPath: '/jplayer',
		 solution: 'html, flash',
		 supplied: 'mp3',
		 preload: 'metadata',
		 volume: 0.8,
		 muted: false,
		 backgroundColor: '#000000',
		 cssSelectorAncestor: '.player-container',
		 cssSelector: {
		  // videoPlay: '.jp-video-play',
		  play: '.set-play-button',
		  // pause: '.set-play-button',
		  // stop: '.jp-stop',
		  seekBar: '.set-player-wrapper',
		  playBar: '.player-seek',
		  // volumeBar: '.jp-volume-bar',
		  // volumeBarValue: '.jp-volume-bar-value',
		  // volumeMax: '.jp-volume-max',
		  // playbackRateBar: '.jp-playback-rate-bar',
		  // playbackRateBarValue: '.jp-playback-rate-bar-value',
		  currentTime: '.tracklist-text',
		  // duration: '.jp-duration',
		  // fullScreen: '.jp-full-screen',
		  // restoreScreen: '.jp-restore-screen',
		  // repeat: '.jp-repeat',
		  // repeatOff: '.jp-repeat-off',
		  // gui: '.jp-gui',
		  // noSolution: '.jp-no-solution'
		 },
		 errorAlerts: true,
		 warningAlerts: true
		});
		$('.set-play-button').click(function() {
			$('.set-play-button').hide();
			$('.set-pause-button').show();
        	$('#stredm-audio').jPlayer('play');
	    });
	    $('.set-pause-button').click(function() {
			$('.set-pause-button').hide();
			$('.set-play-button').show();
	        $('#stredm-audio').jPlayer('pause');
	    });
	    // $('#stopButton').click(function() {
	    //     $('#stredm-audio').jPlayer('stop');
	    // });
		console.log("start jplayer");

		

status.formatType = 'mp3'
Browser canPlay('audio/mpeg; codecs="mp3"')

status.src = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3'

status.media = {
 mp3: http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3
};

status.videoWidth = 'undefined' | status.videoHeight = 'undefined'
status.width = '0px' | status.height = '0px'

htmlElement.audio.canPlayType = function
This instance is using the constructor options:
$('#jquery_jplayer_1').jPlayer({
 swfPath: '../js',
 solution: 'html, flash',
 supplied: 'mp3',
 preload: 'metadata',
 volume: 0.8,
 muted: false,
 backgroundColor: '#000000',
 cssSelectorAncestor: '#jp_container_1',
 cssSelector: {
  videoPlay: '.jp-video-play',
  play: '.jp-play',
  pause: '.jp-pause',
  stop: '.jp-stop',
  seekBar: '.jp-seek-bar',
  playBar: '.jp-play-bar',
  mute: '.jp-mute',
  unmute: '.jp-unmute',
  volumeBar: '.jp-volume-bar',
  volumeBarValue: '.jp-volume-bar-value',
  volumeMax: '.jp-volume-max',
  playbackRateBar: '.jp-playback-rate-bar',
  playbackRateBarValue: '.jp-playback-rate-bar-value',
  currentTime: '.jp-current-time',
  duration: '.jp-duration',
  fullScreen: '.jp-full-screen',
  restoreScreen: '.jp-restore-screen',
  repeat: '.jp-repeat',
  repeatOff: '.jp-repeat-off',
  gui: '.jp-gui',
  noSolution: '.jp-no-solution'
 },
 errorAlerts: false,
 warningAlerts: false
});
	});
	</script>