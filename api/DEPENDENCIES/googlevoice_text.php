<?PHP

require_once('class.googlevoice.php');
require_once('format_phone_numbers.php');

function googlevoice_text($inviteePhone,$textString)
{

	$phone = standardizePhone($inviteePhone);
	$gv = new GoogleVoice("streamapp1@gmail.com", "ninjas1158!");
	$gv->sms($phone, $textString);
	
}


?>