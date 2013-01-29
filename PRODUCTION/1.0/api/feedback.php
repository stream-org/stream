<?PHP
// Inserts Feedback from a user

//input::
//	user_feedback
//	user_phone
// 	feedback_category

//output:: 
//	user_feedback
//	user_phone
// 	feedback_category


include('dependencies.php');


// grabbing the arguments
if(empty($_POST))
{

	$user_phone = $_GET['user_phone'];
	$user_feedback = $_GET['user_feedback'];
	$feedback_category = $_GET['feedback_category'];
	$output = array();

}

if(empty($_GET))
{
	$user_phone = $_POST['user_phone'];
	$user_feedback = $_POST['user_feedback'];
	$feedback_category = $_POST['feedback_category'];
	$output = array();
}

$user_phone = standardizePhone($user_phone);

$feedback_result = mysql_query("INSERT INTO Feedback (Phone, Content, Category) VALUES ('$user_phone', '$user_feedback', '$feedback_category')");

$output['user_phone'] = $user_phone;
$output['user_feedback'] = $user_feedback;
$output['feedback_category'] = $feedback_category;


// // echo $user_feedback;

// echo "<br>";

// // echo substr($user_feedback, 3, strlen($user_feedback));

// $string_length = strlen($user_feedback);

// $number_of_messages = floor($string_length/2) + 1;

// while($number_of_messages > 0)
// {
// 	$feedback_segment = substr($user_feedback, 0, 2);
// 	$user_feedback = substr($user_feedback, 2, strlen($user_feedback));

// 	echo $feedback_segment;
// 	echo "<br>";

// 	$number_of_messages = $number_of_messages - 1;
// }



if($feedback_result)
{
	$output['status'] = "ok";
}

else 
{
	$output['status'] = "error";
	$output['error_description'] = "feedback not recorded.";
}

echo json_encode($output);

?>