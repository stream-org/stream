
$('stream_button').click(function() {
	console.log('got it.')
	console.log($(this).id);		
});

$( document ).on( 'pageinit', function(){
	pic_width = $('.ui-block-a').width();
	pic_height = $('.ui-block-b').width();

	console.log(pic_width);
	console.log(pic_height);
});