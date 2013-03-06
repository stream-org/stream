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

// Example: 
// http://75.101.134.112/stream/1.0/api/feedback.php?user_phone=8477226071&user_feedback=eyyyy&feedback_category=poop


include('dependencies.php');


// grabbing the arguments
if(empty($_POST))
{

	$user_phone = $_GET['user_phone'];
	$user_feedback = $_GET['user_feedback'];
	$feedback_category = $_GET['feedback_category'];

}

if(empty($_GET))
{
	$user_phone = $_POST['user_phone'];
	$user_feedback = $_POST['user_feedback'];
	$feedback_category = $_POST['feedback_category'];
	
}

$output = array();
$user_phone = standardizePhone($user_phone);

$feedback_result = mysql_query("INSERT INTO Feedback (Phone, Content, Category) VALUES ('$user_phone', '$user_feedback', '$feedback_category')");

$output['user_phone'] = $user_phone;
$output['user_feedback'] = $user_feedback;
$output['feedback_category'] = $feedback_category;


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