//mah library


// function printValue(argument) 
// {
// 	return argument;
// }

	//get latest pic
		function get_latest_pic() {
			var api_base = "http://75.101.134.112/stream/1.0/api/populate_user_streams.php?viewer_phone=";
			var api_param = "16508420492";
			var api_call = api_base + api_param;

			console.log(api_call);
			pic_url_array = []; 

			$.ajax({
				type: 'GET',
				url: api_call,
				dataType: "json",
				success: function(data){
					console.log(data);
	           		pic_url = data["streams"][0]["picture_latest"]["picture_tinyurl"];
	           		},
           		data: {},
           		async: false
	       		});

			return pic_url_array;
		};

	// decode URL 
		function getQueryParams(qs) {
		    qs = qs.split("+").join(" ");

		    var params = {}, tokens,
		        re = /[?&]?([^=]+)=([^&]*)/g;

		    while (tokens = re.exec(qs)) {
		        params[decodeURIComponent(tokens[1])]
		            = decodeURIComponent(tokens[2]);
		    }

		    return params;
		}

$( document ).on( 'pageinit', function(){

	var query = getQueryParams(document.location.search);
	console.log(query);

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


		var latest_pic = get_latest_pic();

        $('#router').append(
			'<div data-role="content"> <div id="download_app"> <div class="ui-grid-a"> <div class="ui-block-a"> <img src="' + latest_pic + '"> </div> </div> <a href="" data-role="button" data-theme="oj" id="download_app_btn"> Download the App! </a> </div> </div>'
			);
		$('#router').trigger('create');

	};
});

$( document ).on('pagebeforechange', function (e, data) {
	console.log(e);

	var target = data.toPage;
	console.log(target);
});