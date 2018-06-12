<?php 
    
	$html=file_get_contents("https://www.rottentomatoes.com/m/godfather/");
    $split=explode("<span class=\"meter-vale superPageFontColor\"><span>",explode("</span>",$html)[0])[1];
	echo $split;
?>




