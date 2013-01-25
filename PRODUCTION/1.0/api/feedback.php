<?PHP

include('dependencies.php');

$user_phone = $_GET['user_phone'];
$user_feedback = $_GET['user_feedback'];
$feedback_category = $_GET['feedback_category'];
$output = array();

// $feedback_result = mysql_query("INSERT INTO Feedback (Phone, Content, Category) VALUES ('$user_phone', '$user_feedback', '$feedback_category')");

$output['user_phone'] = $user_phone;
$output['user_feedback'] = $user_feedback;
$output['feedback_category'] = $feedback_category;


// echo $user_feedback;

echo "<br>";

// echo substr($user_feedback, 3, strlen($user_feedback));

$string_length = strlen($user_feedback);

$number_of_messages = floor($string_length/2) + 1;

while($number_of_messages > 0)
{
	$feedback_segment = substr($user_feedback, 0, 2);
	$user_feedback = substr($user_feedback, 2, strlen($user_feedback));

	echo $feedback_segment;
	echo "<br>";

	$number_of_messages = $number_of_messages - 1;
}



// if($feedback_result)
// {
// 	$output['status'] = "ok";
// }

// else 
// {
// 	$output['status'] = "error";
// 	$output['error_descriptipn'] = "Stream was not created.";
// }

// echo json_encode($output);

?>