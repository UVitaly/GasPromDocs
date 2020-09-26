(function()
{
	var video = document.getElementById('video'),
	    canvas  = document.getElementById('canvas'),
	    context = canvas.getContext('2d'),
	    photo = document.getElementById('photo'),
	    vedorUrl = window.URL || window.webkitURL;

	navigator.GetMedia = navigator.getUserMedia ||
	                     navigator.webkitUserMedia ||
	                     navigator.mozGetUserMedia ||
	                     navigator.mozGetUserMedia;

	navigator.GetMedia({
		video: true,
		audio: false
	},function(stream)
	{
		video.src = vedorUrl.createObjectURL();
		vide.play();
	},function(error)
	{
		// An error occured
		// error.code
	});
	document.getElementById('capture').addEventListener('click', function()
		{
			context.drawImage(video, 0, 0, 400, 300);
			photo.setAtribute('src', canvas.toDataURL('image/png'));
		}
	);
})();