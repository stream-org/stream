<?PHP

// require 'class.phpmailer.php'; 

require 'class.googlevoice.php';

function nonMogreetText($inviteePhone,$textString)
{

$gv = new GoogleVoice("streamapp1@gmail.com", "ninjas1158!");
$gv->sms($inviteePhone, $textString);
echo $gv->status; 

}


?>