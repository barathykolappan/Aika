<?php
include("imdbQuery.php");
$method = $_SERVER['REQUEST_METHOD'];
// Process only when method is POST
if($method == 'POST'){
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);
	$text = $json->result->parameters->text;
	$data=array();
	$i = new Imdb();
	$i->getMovieInfo($text);
}

?>