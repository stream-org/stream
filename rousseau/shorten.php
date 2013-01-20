<?PHP

function shorten($url) {
	
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,"https://www.googleapis.com/urlshortener/v1/url");
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode(array("longUrl"=>$url)));
	curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = curl_exec($ch);
	$response = str_replace("\n", "", $response);
	$response = explode('"id":', $response);
	$response = $response[1];
	$response = explode(",", $response);
	$response = $response[0];
	return substr($response, 2, -1);
}

?>