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
	if($split<10)
		$Rev="This defines Garbage";
	else if($split>=10 && $split<20)
		$Rev="Couldn't be Worse, a Rubbish piece.";
	else if($split>=20 && $split<30)
		$Rev="Series of mistakes, unattended.";
	else if($split>=30 && $split<40)
		$Rev="Not worth your tickets.";
	else if($split>=40 && $split<50)
		$Rev="This is a Cat on the Wall, you decide!";
	else if($split>=50 && $split<60)
		$Rev="A Fair piece, which could've been better.";
	else if($split>=60 && $split<70)
		$Rev="A Decent movie, that could be worth your time.";
	else if($split>=70 && $split<80)
		$Rev="Bang on! This is going to be worth your tickets.";
	else if($split>=80 && $split<90)
		$Rev="Goodness. An Awesome work by the crew. Dont miss it.";
	else if($split>=90 && $split<100)
		$Rev="Wow. An absolute Masterpiece!";
	else if($split==100)
		$Rev="You dare not miss this movie.";

	$response = new \stdClass();
	$response->speech = $Rev;
	$response->displayText = $Rev;
	$response->source = "webhook";
	echo json_encode($response);
}
else
{
	echo "Method not allowed";
}

?>