<?PHP

require_once('class.googlevoice.php');

function googlevoice_text($inviteePhone,$textString)
{

$gv = new GoogleVoice("streamapp1@gmail.com", "ninjas1158!");
$gv->sms($inviteePhone, $textString);
echo $gv->status; 

}


?>