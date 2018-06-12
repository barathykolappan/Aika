<?php
// Process only when method is POST
if($method == 'POST'){
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);

	$text = $json->result->parameters->text;

	$array = array(
    "Shawshank redemption" =>90,
	"Godfather" =>80,
	"The Dark Knight" =>70);
    $split=$array[$text];
	switch ($text) {
		case 90:
			$speech = "Awesome work by the crew.";
			break;

		case 80:
			$speech = "Worth your tickets.";
			break;

		case 70:
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