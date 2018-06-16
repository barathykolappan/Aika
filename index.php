<?php
include("imdbQuery.php");
$method = $_SERVER['REQUEST_METHOD'];
if($method == 'POST'){
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);
	$text = $json->result->parameters->text;
	$data=array();
	$i = new Imdb();
	$i->getMovieInfo($text);
}
?>