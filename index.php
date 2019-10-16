<html>
<head>
	<title>StarWars Test</title>
	<style>
	.logo {
	  display: block;
	  text-indent: -9999px;
	  width: 100px;
	  height: 82px;
	  background: url(kiwi.svg);
	  background-size: 100px 82px;
	}
	#tried{
		background-color: #FFD700;
		color: #696969;
		width: 25%;
		height: 10%;
		font-size: 20px;
		font-weight: bold;
		text-align: center;
	}
	.qest p{
		margin: 5%;
		font-weight: bold;
		font-size: 16px;
	}
	.ans p{
		margin: 5%;
		color: yellow;
		font-weight: bold;
		font-size: 16px;
	}
	.apicalls{
		display: none;
	}
	.btn:hover
	{

	}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<head>
<body style="background-color:black;text-align:center;color:white;>
	<div class="container">
		<img src="https://upload.wikimedia.org/wikipedia/commons/6/6c/Star_Wars_Logo.svg">
		<div class="trybutton" id="try">
			<a  href="javascript:toggleDiv()" class="btn" id="tried">Do. or Do Not. There is no try</a>
		</div>
		<div class="apicalls" id='apicalls'>
			<div class="block">
				<div class="qest" id="qest">
					<p>Which of all StarWars movies has the longest opening crawl</p>
				</div>
				<div class="ans" id="ans">
					<p><?php $crawl = getopeningcrawl(); echo $crawl['title'].'('.$crawl['crawls'].')'  ?></p>
				</div>
			</div>
			<div class="block">
				<div class="qest" id="qest">
					<p>What character (person) appeared in the most of StarWars films?</p>
				</div>
				<div class="ans" id="ans">
					<p><?php $film = mostfilms('people/'); echo $film['name'].'('.$film['films'].')' ?></p>
				</div>
			</div>
			<div class="block">
				<div class="qest" id="qest">
					<p>What species appeared in the most number of StarWars films?</p>
				</div>
				<div class="ans" id="ans">
					<p><?php $film = mostfilms('species/'); echo $film['name'].'('.$film['films'].')' ?></p>
				</div>
			</div>
			<div class="block">
				<div class="qest" id="qest">
					<p>What planet in StarWars universe provided largest number of vehicle pilots?</p>
				</div>
				<div class="ans" id="ans">
					<p><?php echo getplanet(); ?></p>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		function toggleDiv() {
        $("#apicalls").toggle(); 
   		 }
	</script>
</body>
</html>
<?php 
function getopeningcrawl()
{
	$url = "https://swapi.co/api/films/";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 2000);
	$result=curl_exec($ch);
	curl_close($ch);
	$data = json_decode($result,true);
	$films = $data['results'];
	$fdata = array();
	$i=0;
	// print_r($films);
	foreach($films as $film)
	{
		$fdata[$i]['title'] = $film['title'];
		$fdata[$i]['crawls'] = strlen($film['opening_crawl']);
	}
	$temp = array();
	foreach ($fdata as $item) {
	    foreach ($item as $key => $value) {
	        $temp[$key] = max(
	                isset($temp[$key]) ? $temp[$key] : $value,
	                $value);
	    }
	}
	return $temp ;
}

function mostfilms($app)
{
	$url = "https://swapi.co/api/".$app;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	curl_close($ch);
	$data = json_decode($result);
	$i = 0;
	$mfilms = array();
	foreach ($data->results as $people)
	{
		$mfilms[$i]['name'] = $people->name;
		$mfilms[$i]['films'] = count($people->films);
		$i++;
	}
	// print_r($mfilms); 
	$temp = array();
	foreach ($mfilms as $item) {
	    foreach ($item as $key => $value) {
	        $temp[$key] = max(
	                isset($temp[$key]) ? $temp[$key] : $value,
	                $value);
	    }
	}
	return $temp ;
}
function getplanet()
{
	$url = "https://swapi.co/api/starships/";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	curl_close($ch);
	$data = json_decode($result);
	$i=0;
	foreach ($data->results as $people)
	{
		$mfilms[$i]['name'] = $people->name;
		$mfilms[$i]['count'] = count($people->pilots);
		$mfilms[$i]['pilots'] = $people->pilots;
		$i++;
	}
	// print_r($mfilms); 
	$temp = array();
	foreach ($mfilms as $item) {
	    foreach ($item as $key => $value) {
	        $temp[$key] = max(
	                isset($temp[$key]) ? $temp[$key] : $value,
	                $value);
	    }
	}
	$pilot = array();
	$j=0;
	foreach($temp['pilots'] as $pilots)
	{
		$pilot[$j] = getpilotinfo($pilots);
		$j++;
	}
	$temp['pilots'] = $pilot;
	print_r(json_encode($temp));
}

function getpilotinfo($pilot)
{
	$url = $pilot;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	curl_close($ch);
	$data = json_decode($result);
	$pilot = array();
	$pilot['name'] = $data->name;
	$k=0;
	foreach($data->species as $spec)
	{
		$species[$k] =getspecies($spec);
		$k++;
	}
	$pilot['species'] = $species;
	return $pilot;
}
function getspecies($species)
{
	$url = $species;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	curl_close($ch);
	$data = json_decode($result);
	return  $data->name;
}
?>