<?php
//things to look at/do -- what is breaking
//1. create stream for champagne and shackles
//    a. delete all panda/turkey code so that the catchall
//      grabs anything users send for keywords [album, picture, etc.]
//    b. create collections: 'champagnePictures', 'champagneUsers'
//2. create new website that iterates through the 'champagnePictures' collection
//    a. create PHP file that does this -> look through panda.php for example [
//        should be exactly the same but champagne instead of panda etc.]
//3. make this work
//3. make this work: invite => just sends invite to user
//3. make this work: register => uploads to 'champagneUsers' collection
//4. make this work: megaphone => iterates through 'champagneUsers' and sends to all

//** turkey/panda logic is not catching the texts initially - 
//   need to figure out why this is happening

// //connecting to mongo
// //CHANNINGIZHER
// $token = exec("meteor mongo -U rousseau.meteor.com");
// //executes the "meteor mongo -U rousseau.meteor.com" 
// //command in terminal and saves the output [a token] 
// //in the variable $token - this gives the key to the 
// //mongo database

// $mongo = new Mongo($token);
// //creates a new Mongo connection -- the pointer is 
// //saved as the variable $mongo

// $db = $mongo->rousseau_meteor_com;
// //points to the specific database 'rousseau_meteor_com'

// $collectionPictures = $db->pictures;
// //looks in the 'pictures' collection in the 
// //'rousseau_meteor_com' database [datatables are 'connections'
// //in Mongo]

// $collectionFood = $db->food;
// //points to the 'food' collection

// $collectionUsers = $db->Users;
// //points to the 'Users' collection

// $collectionPandaUsers = $db->pandaUsers;
// $collectionPandaPictures = $db->pandaPictures;
// $collectionTurkeyUsers = $db->turkeyUsers;
// $collectionTurkeyPictures = $db->turkeyPictures;

include "connection.php";

//extracting data from the XML object
$rawMessage = (string) file_get_contents('php://input');
$simpleXML = simplexml_load_string($rawMessage);
$image = (string)$simpleXML->images->image;
$phone = (string)$simpleXML->msisdn;
$message = (string)$simpleXML->message;
$streamID = 1;

//putting the message in an array, so we can tell which command key the user typed in 
$message = trim($message);
$messageArray = explode(" ", $message);
$messageArray[0] = strtolower($messageArray[0]);

//function for sending a text

function sendText($phoneNumber, $textString){
  $textString = urlencode($textString);
  $phoneNumber = intval($phoneNumber);
  $url = 'https://api.mogreet.com/moms/transaction.send?client_id=1316&token=dbd7557a6a9d09ab13fda4b5337bc9c7&campaign_id=28420&to=' . $phoneNumber . '&message=' . $textString . '&format=json';
  $ch = curl_init($url);
  $response = curl_exec($ch);
  curl_close($ch);
}

if($message = 'album'){
  sendText($phone, "bit.ly/VncO3N");
} 

if($message = 'stream'){
mysql_query("INSERT INTO StreamActivity (StreamID, Phone, Picture)
  VALUES ('$streamID', '$phone', '$image')");
}





//album logic
// if($messageArray[0] == 'album'){
//   sendText($phone, "http://www.sumanvenkat.com");
// }

// //invite logic
// elseif($messageArray[0] == 'invite'){
//   $welcomeString = "Hey! Welcome to Event Space!";
//   $inviteNumber = $messageArray[1];
//   sendText($inviteNumber, $welcomeString);
// }

// //register logic
// elseif($messageArray[0] == 'register'){
// 	$first = $messageArray[1];
//   // takes the second string in the array [which should be the 
//   //the person's first name] and saves it

//   $last = $messageArray[2];
//   $arg = array('number'=>$phone, 'first'=>$first, 'last'=>$last);
//   $collectionUsers->insert($arg);
//   $replyString = "That's it! You're all signed up.";
//   sendText($phone, $replyString);
// }

// //megaphone logic
// elseif($messageArray[0] == 'megaphone'){
// 	unset($messageArray[0]);
// 	$messageEncoded = implode(" ", $messageArray);
// 	$user_array = $collectionUsers->find();
// 	foreach($user_array as $user){
// 		if ($user['first']=='Rousseau'){
//       sendText($user['number'], $messageEncoded);
//       //^this was just for testing purposes so that all megaphones
//       //would be sent to Rousseau - we actually want to send this to 
//       //everyone who would want to receive a megaphone
//       //...BE CAREFUL with this - if the users hit Stop once, we're done!
//       //i.e., we don't want to annoy them...
//     }
//     else {
//     	continue;
//     }
//   }
// }

// //food logic
// elseif($messageArray[0] == 'food' && ($phone=='16508420492')){
// 	date_default_timezone_set('UTC');
// 	$date = date('l jS \of F Y h:i:s A');
// 	$time = $time();
// 	$phoneID = array('number'=>$phone);
// 	$userRow = $collectionUsers->findOne(array('number'=>$phone));
//   $userName = $userRow["first"] . " " . $userRow["last"];
//   $arg = array('link'=>(string)$image, 'iden'=>$time, 'number'=>$phone, 'name'=>$userName, 'date'=>$date);
// 	$collectionFood->insert($arg);
//   $photoReceived = "Your photo has been received. Thanks!";
//   sendText($phone, $photoReceived);
// }

// //panda logic 
// elseif($messageArray[0] == 'panda'){
//   if($messageArray[1] == 'album')
//   {
//     $albumString = "bit.ly/UngNZO";
//     sendText($phone, $albumString);
//   } 
//   elseif($messageArray[1] == 'megaphone')
//   {
//     unset($messageArray[0]);
//     unset($messageArray[1]);
//     $messageEncoded = implode(" ", $messageArray);
//     $pandaArray = $collectionPandaUsers->find();
//     foreach($pandaArray as $panda){
//       sendText($panda['number'], $messageEncoded);
//     }
//   } 
//   elseif ($messageArray[1] == 'invite')
//   {
//     $welcomeString = "Welcome to the Canyon Spring Space! Reply with 'panda register your_first_name your_last_name' to get signed up!";
//     $inviteNumber = $messageArray[2];
//     sendText($inviteNumber, $welcomeString);
//   } 
//   elseif ($messageArray[1] == 'register') 
//   {
//     $first = $messageArray[2];
//     $last = $messageArray[3];
//     $arg = array('number'=>$phone, 'first'=>$first, 'last'=>$last);
//     $collectionPandaUsers->insert($arg);
//     $replyString = "That's it! You're all signed up. Reply with the word 'panda' and a photo to upload a photo to your eventspace! bit.ly/UngNZO  G";
//     sendText($phone, $replyString);
//     $rousseauNotification = $first . " " . $last . " just signed up!";
//     sendText("6508420492", $rousseauNotification);
//   } 
//   else 
//   {
//     $time = time();
//     $phoneID = array('number'=>$phone);
//     $pandaRow = $collectionPandaUsers->findOne(array('number'=>$phone));
//     $pandaUserName = $pandaRow["first"] . " " . $pandaRow["last"];
//     $arg = array('link'=>(string)$image, 'iden'=>$time, 'number'=>$phone, 'name'=>$pandaUserName);
//     $collectionPandaPictures->insert($arg);
//     $pandaArray = $collectionPandaUsers->find();
//     $pandaNotification = $pandaUserName . " just uploaded a photo! bit.ly/UngNZO";
//     foreach($pandaArray as $panda){
//       sendText($panda['number'], $pandaNotification);
//     }
//   }
// }

// elseif($messageArray[0] == 'turkey'){
//   if($messageArray[1] == 'album')
//   {
//     $albumString = "bit.ly/WAUFiO";
//     sendText($phone, $albumString);
//   } 
//   elseif($messageArray[1] == 'megaphone')
//   {
//     unset($messageArray[0]);
//     unset($messageArray[1]);
//     $messageEncoded = implode(" ", $messageArray);
//     $turkeyArray = $collectionTurkeyUsers->find();
//     foreach($turkeyArray as $turkey){
//       sendText($tukey['number'], $messageEncoded);
//     }
//   } 
//   elseif ($messageArray[1] == 'invite')
//   {
//     $welcomeString = "Welcome to the Thanksgiving Space! Reply with 'turkey register your_first_name your_last_name' to get signed up!";
//     $inviteNumber = $messageArray[2];
//     sendText($inviteNumber, $welcomeString);
//   } 
//   elseif ($messageArray[1] == 'register') 
//   {
//     $first = $messageArray[2];
//     $last = $messageArray[3];
//     $arg = array('number'=>$phone, 'first'=>$first, 'last'=>$last);
//     $collectionTurkeyUsers->insert($arg);
//     $replyString = "That's it! You're all signed up. Reply with the word 'turkey' and a photo to upload a photo to your eventspace! bit.ly/WAUFiO";
//     $rousseauNotification = $first . " " . $last . " just signed up!";  
//     sendText($phone, $replyString);
//     sendText("6508420492", $rousseauNotification);
//   } 
//   else 
//   {
//     $time = time();
//     $phoneID = array('number'=>$phone);
//     $turkeyRow = $collectionTurkeyUsers->findOne(array('number'=>$phone));
//     $turkeyUserName = $turkeyRow["first"] . " " . $turkeyRow["last"];
//     $arg = array('link'=>(string)$image, 'iden'=>$time, 'number'=>$phone, 'name'=>$turkeyUserName);
//     $collectionTurkeyPictures->insert($arg);
//     $turkeyArray = $collectionTurkeyUsers->find();
//     $turkeyNotification = "Your photo has been succesfully uploaded! bit.ly/WAUFiO";
//     $turkeyRousseau = "A photo has just been uploaded! bit.ly/WAUFiO";
//     sendText($phone, $turkeyNotification);
//     sendText("6508420492", $turkeyRousseau);
//     // foreach($turkeyArray as $turkey){
//     //   sendText($turkey['number'], $turkeyNotification);
//     // }
//   }
// }

// //picture logic - catchall for anything missed by 'turkey' and 'panda'
// else {
//     $time = time();
//     $phoneID = array('number'=>$phone);
//     $turkeyRow = $collectionTurkeyUsers->findOne(array('number'=>$phone));
//     $turkeyUserName = $turkeyRow["first"] . " " . $turkeyRow["last"];
//     $arg = array('link'=>(string)$image, 'iden'=>$time, 'number'=>$phone, 'name'=>$turkeyUserName);
//     $collectionTurkeyPictures->insert($arg);
//     $turkeyArray = $collectionTurkeyUsers->find();
//     $turkeyNotification = "Your photo has been succesfully uploaded! bit.ly/WAUFiO";
//     $turkeyRousseau = "A photo has just been uploaded! bit.ly/WAUFiO";
//     sendText($phone, $turkeyNotification);
//     sendText("6508420492", $turkeyRousseau);
// }
?>