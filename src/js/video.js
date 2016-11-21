/* global YT, onPlayerReady */
( function( $, window ) {
	/**
	 * Create a script element to load in the YouTube iFrame API and insert it
	 * into the document.
	 */
	var load_youtube = function() {
		var tag = document.createElement( "script" );

		tag.src = "https://www.youtube.com/iframe_api";
		var firstScriptTag = document.getElementsByTagName( "script" )[ 0 ];
		firstScriptTag.parentNode.insertBefore( tag, firstScriptTag );
	};

	/**
	 * Callback function expected by the YouTube Iframe API. Without a function
	 * with this name available in the global space, our use of the YouTube API
	 * does not work.
	 *
	 * Loop through each of the inline YouTube Videos, gather the video information,
	 * and set up objects representing the videos.
	 */
	window.onYouTubeIframeAPIReady = function() {
		$( ".wsuwp-youtube-embed" ).each( function() {
			var video_id = $( this ).data( "video-id" ),
				video_height = $( this ).data( "video-height" ),
				video_width = $( this ).data( "video-width" ),
				video_autoplay = $( this ).data( "video-autoplay" ),
				video_controls = $( this ).data( "video-control" ),
				video_end = $( this ).data( "video-end" ),
				video_loop = $( this ).data( "video-loop" ),
				video_modestbranding = $( this ).data( "video-modestbranding" ),
				video_playsinline = $( this ).data( "video-playsinline" ),
				video_rel = $( this ).data( "video-rel" ),
				video_showinfo = $( this ).data( "video-showinfo" ),
				video_start = $( this ).data( "video-start" );

			new YT.Player( "wsuwp-youtube-video-" + video_id, {
				height: video_height,
				width: video_width,
				videoId: video_id,
				playerVars: {
					autoplay: video_autoplay,
					controls: video_controls,
					end: video_end,
					loop: video_loop,
					modestbranding: video_modestbranding,
					playsinline: video_playsinline,
					rel: video_rel,
					showinfo: video_showinfo,
					start: video_start
				},
				events: {
					"onReady": onPlayerReady
				}
			} );
		} );
	};

	/**
	 * Handles custom behavior when the player is ready.
	 *
	 * @param event
	 */
	window.onPlayerReady = function( event ) {
		var volume = $( event.target.h ).data( "video-volume" );
		if ( "default" === volume ) {
			return;
		} else if ( "mute" === volume ) {
			event.target.mute();
		} else {
			event.target.setVolume( volume );
		}
	};

	/**
	 * Fire any actions that we need to happen once the document is ready.
	 */
	$( document ).ready( function() {
		load_youtube();
	} );
}( jQuery, window ) );
