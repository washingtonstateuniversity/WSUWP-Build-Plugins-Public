(function($){
	$('#' + window.wsu_video_background.id).videoBG({
		mp4: window.wsu_video_background.mp4,
		ogv: window.wsu_video_background.ogv,
		webm: window.wsu_video_background.webm,
		poster: window.wsu_video_background.poster,
		scale: window.wsu_video_background.scale,
		zIndex: window.wsu_video_background.zIndex
	});
}(jQuery));