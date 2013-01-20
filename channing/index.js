//mah library


function printValue(argument) 
{
	console.log(argument);
}

	//get latest pic
		function get_latest_pic() {
			var latest_pic = ""; 
			$.getJSON("http://75.101.134.112/stream/1.0/api/populate_user_streams.php?viewer_phone=16508420492",function(data){
	           		var pic_url = data["Streams"][0]["picture_latest"]["picture_tinyurl"];
	           		printValue(pic_url);
	           });

			// console.log(latest_pic);
		};


$( document ).on( 'pageinit', function(){
	if(/(iPhone|iPod|iPad)/i.test(navigator.userAgent)) { 
	    if(/OS [6]_\d(_\d)? like Mac OS X/i.test(navigator.userAgent)) {  
	        
	  //       var latest_pic = get_latest_pic();
	  //       console.log(latest_pic);

	  //       $('#router').append(
			// 	'<div data-role="content"> <div id="download_app"> <div class="ui-grid-a"> <div class="ui-block-a"> <img src="' + latest_pic + '"> </div> </div> <a href="" data-role="button" data-theme="oj" id="download_app_btn"> Download the App! </a> </div> </div>'
			// 	);
			// $('#router').trigger('create');

	    }else{    	

	    	//route to mobile web layer	

	    };

	}else{

		// var latest_pic = 
		get_latest_pic();
		
        // console.log(latest_pic);

  //       $('#router').append(
		// 	'<div data-role="content"> <div id="download_app"> <div class="ui-grid-a"> <div class="ui-block-a"> <img src="' + latest_pic + '"> </div> </div> <a href="" data-role="button" data-theme="oj" id="download_app_btn"> Download the App! </a> </div> </div>'
		// 	);
		// $('#router').trigger('create');

		// get_latest_pic()

		// $('#router').append(
		// 		'<div data-role="content"> <div id="intro_buttons"> <a href="#sign_up" data-role="button" data-theme="oj" id="sign_up_btn">SIGN UP</a> <a href="#sign_in" data-role="button" data-theme="blk" id="sign_in_btn">SIGN IN</a> </div> </div>'
		// 		);
		// $('#router').trigger('create');
	};
});

$( document ).on('pagebeforechange', function (e, data) {
	console.log(e);

	var target = data.toPage;
	console.log(target);
});