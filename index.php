<?php 

$method = $_SERVER['REQUEST_METHOD'];

if($method == 'POST'){
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);

	$Movie = $json->result->parameters->Movie;

	$html=filr_get_contents("www.rottentomatoes.com/m/"+$Movie+"/");
    $split=explode("<span class=\"meter-value superPageFontColor\"><span>",explode("</span>",$page)[0])[1];
	$Summ=explode("Critics Consensus:</span>",explode("</p>",$page)[0])[1];
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
	
	$speech=$Summ+"  The bottomline is "+$Rev
	$response = new \stdClass();
	$response->speech = $speech;
	$response->displayText = $speech;
	$response->source = "webhook";
	echo json_encode($response);
}
else
{
	echo "We just hit a glitch.";
}

?>