<?php 

$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is POST
if($method == 'POST'){
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);

	$text = $json->result->parameters->text;
	$arr= array(
	'Godfather'=>'90',
	'Shawshank redemption'=>'80');
	$score=$arr[$text];

		switch ($score) {
		case '90':
			$speech = "Awesome work by the crew.";
			break;

		case '80':
			$speech = "Worth your tickets.";
			break;

		case 'The dark knight':
			$speech = "Decent movie.";
			break;
		
		default:
			$speech = "Sorry, I didnt get that. Please ask me something else.";
			break;
	}

	$response = new \stdClass();
	$response->speech = $speech;
	$response->displayText = $speech;
	$response->source = "webhook";
	echo json_encode($response);
}
else
{
	echo "Method not allowed";
}

?>