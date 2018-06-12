<?php 

$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is POST
if($method == 'POST'){
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);

	$text = $json->result->parameters->text;

	$array = array(
    "Shawshank redemption" => int(92),
    "The Shawshank redemption" => int(92),
	"Godfather" => int(92),
	"The Godfather" => int(92),
	"The Dark Knight" => int(90),
	"Dark Knight" => int(90),
	"12 Angry Men" => int(89),
	"Twelve Angry Men" => int(89),
	"Schindler's List " => int(89),
	"Pulp Fiction" => int(89),
	"The Good, the Bad and the Ugly" => int(88),
	"Fight Club" => int(88),
	"Forrest Gump" => int(87),
	"Inception" => int(87),
	"Goodfellas" => int(87),
	"Seven" => int(86),
	"The Silence of the Lambs" => int(86),
	"Silence of the Lambs" => int(86),
	"The Usual Suspects" => int(86),
	"Usual Suspects" => int(86),
	"Interstellar" => int(85),
	"Psycho" => int(85),
	"The Prestige" => int(85),
	"Prestige" => int(85)
);
    $split=int($array[$text]);
	$response = new \stdClass();
	$response->speech = $split;
	$response->displayText = $split;
	$response->source = "webhook";
	echo json_encode($response);
}
else
{
	echo "Method not allowed";
}

?>