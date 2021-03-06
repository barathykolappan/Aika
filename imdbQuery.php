<?php 
///////////////////////////////////////////////////////
//Aika 1.0
//A Digital Movie Critic
//Devised and created by Barathy A
//////////////////////////////////////////////////////
class Imdb
{
  // Get movie information by Movie Title.
  // This method searches the given title on Google, Bing or Ask to get the best possible match.
  public function getMovieInfo($title, $getExtraInfo = true)
  {
    $imdbId = $this->getIMDbIdFromSearch(trim($title));
    if($imdbId === NULL){
      $arr = array();
      $arr['error'] = "No Title found in Search Results!";
      return $arr;
    }
    return $this->getMovieInfoById($imdbId, $getExtraInfo);
  }
  // Get movie information by IMDb Id.
  public function getMovieInfoById($imdbId, $getExtraInfo = true)
  {
    $arr = array();
    $imdbUrl = "https://www.imdb.com/title/" . trim($imdbId) . "/";
    return $this->scrapeMovieInfo($imdbUrl, $getExtraInfo);
  }
  // Scrape movie information from IMDb page and return results in an array.
  private function scrapeMovieInfo($imdbUrl, $getExtraInfo = true)
  {
    $arr = array();
    $html = $this->geturl("${imdbUrl}reference");
    $title_id = $this->match('/<link rel="canonical" href="https:\/\/www.imdb.com\/title\/(tt\d+)\/reference" \/>/ms', $html, 1);
    if(empty($title_id) || !preg_match("/tt\d+/i", $title_id)) {
      $arr['error'] = "No Title found on IMDb!";
      return $arr;
    }
    $arr['title'] = str_replace('"', '', trim($this->match('/<title>(IMDb \- )*(.*?) \(.*?<\/title>/ms', $html, 2)));
    $arr['rating'] = $this->match('/<span class="ipl-rating-star__rating">(\d.\d)<\/span>/ms', $html, 1);
	$arr['directors'] = $this->match_all_key_value('/<a href="\/name\/(nm\d+).*?>(.*?)<\/a>/ms', $this->match('/Directed by.*?<\/h4>.*?<table.*?>(.*?)<\/table>/ms', $html, 1));
	$dir=implode(" ",$arr['directors']);
	if($dir=="")
	{
		$arr['producers'] = $this->match_all_key_value('/<a href="\/name\/(nm\d+).*?>(.*?)<\/a>/ms', $this->match('/Produced by.*?<\/h4>.*?<table.*?>(.*?)<\/table>/ms', $html, 1));
		$dir=implode(" ",$arr['producers']);
	}
	if($dir=="")
	{
		$arr['stars'] = $this->match_all_key_value('/<a href="\/name\/(nm\d+).*?>(.*?)<\/a>/ms', $this->match('/Stars:.*?<ul.*?>(.*?)<\/ul>/ms', $html, 1));
		$dir=implode(" ",$arr['stars']);
	}
    if(strpos($html,'TV Series') OR strpos($html,'TV Mini Series'))
	{
	$ssplit=explode("<section class=\"titlereference-section-overview\">",explode("<div class=\"titlereference-overview-section\">",$html)[0])[1];
	$srplit=explode("<hr>",explode("<\/div>",$ssplit)[0])[1];
	$split=explode("<div>",explode(".",$srplit)[0])[1];
	}
	else
	{	
	$srplit=explode("<section class=\"titlereference-section-overview\">",explode("<div class=\"titlereference-overview-section\">",$html)[0])[1];
	if(strpos($srplit, '<a'))
	$split=explode("<div>",explode("<a",$srplit)[0])[1];
	else
	$split=explode("<div>",explode("</div>",$srplit)[0])[1];
	}  
	$score=$arr['rating']*10;
	if($score==100)
		$speech="An absolute Masterpiece!, Dare not miss it.";
		elseif($score>=90 AND $score<=99)
		$speech = "Awesome here, Awesome there, Awesome everywhere. Should be worth your while.";
	    elseif($score>75 AND $score<=90)
		$speech="This is a fine piece. A definite worth for your tickets.";
		elseif($score>70 AND $score<=75)
		$speech="A Decent flick, which is an one time watch.";
		elseif($score>60 AND $score<=70)
		$speech="A Fair storyline, that could have been better.";
		elseif($score>50 AND $score<=60)
		$speech="A Satisfactory flick, that has just passed the line.";
		elseif($score>40 AND $score<=50)
		$speech="This piece is a Cat on the Wall, you decide.";
		elseif($score>30 AND $score<=40)
		$speech="Series of errors implemented in the best way.";
		elseif($score>20 AND $score<=30)
		$speech="Would you like to burn the money you would spend on this piece? Charity, honey.";
		elseif($score>10 AND $score<=20)
		$speech="Absolute garbage, it is. If I were you, I would rather work out Calculus.";
		elseif($score>0 AND $score<=10)
		$speech="Please. No. I only speak ethical language.";
		else
		$speech="Relative perspectives. It's upto you to decide.";
	$com=$arr['title']." by ".$dir." narrates, \n".$split	."\n\nAika's verdict is, ".$speech."\n\nOne more?";
	$response = new \stdClass();
	$response->speech = $com;
	$response->displayText = $com;
	$response->source = "webhook";
	echo json_encode($response);
  }
  private function getIMDbIdFromSearch($title, $engine = "google"){
    switch ($engine) {
      case "google":  $nextEngine = "bing";  break;
      case "bing":    $nextEngine = "ask";   break;
      case "ask":     $nextEngine = "yahoo";   break;
	  case "yahoo":     $nextEngine = "aol";   break;
	  case "aol":     $nextEngine = "yippy";   break;
	  case "yippy":     $nextEngine = "duckduckgo";   break;
	  case "aol":     $nextEngine = "yippy";   break;
	  case "yippy":     $nextEngine = "lycos";   break;
      case FALSE:     return NULL;
      default:        return NULL;
    }
    $url = "http://www.${engine}.com/search?q=imdb+" . rawurlencode($title);
    $ids = $this->match_all('/<a.*?href="https?:\/\/www.imdb.com\/title\/(tt\d+).*?".*?>.*?<\/a>/ms', $this->geturl($url), 1);
    if (!isset($ids[0]) || empty($ids[0])) //if search failed
      return $this->getIMDbIdFromSearch($title, $nextEngine); //move to next search engine
    else
      return $ids[0]; //return first IMDb result
  }
  private function geturl($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $ip=rand(0,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip"));
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/".rand(3,5).".".rand(0,3)." (Windows NT ".rand(3,5).".".rand(0,2)."; rv:2.0.1) Gecko/20100101 Firefox/".rand(3,5).".0.1");
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
  }
  private function match_all_key_value($regex, $str, $keyIndex = 1, $valueIndex = 2){
    $arr = array();
    preg_match_all($regex, $str, $matches, PREG_SET_ORDER);
    foreach($matches as $m){
      $arr[$m[$keyIndex]] = $m[$valueIndex];
    }
    return $arr;
  }
  private function match_all($regex, $str, $i = 0){
    if(preg_match_all($regex, $str, $matches) === false)
      return false;
    else
      return $matches[$i];
  }
  private function match($regex, $str, $i = 0){
    if(preg_match($regex, $str, $match) == 1)
      return $match[$i];
    else
      return false;
  }
}
?>