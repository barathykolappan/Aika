<?php 

$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is POST
if($method == 'POST'){
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);
    $html=file_get_contents("https://www.rottentomatoes.com/m/godfather/");
    $speech=explode("<span class=\"meter-vale superPageFontColor\"><span>",explode("</span>",$html)[0])[1];
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




