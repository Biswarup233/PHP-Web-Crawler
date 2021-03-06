<title>Webnet Official Crawler</title>
<meta name="viewport" content="width=device-width, initial-scale=1">	
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<center>
<br><br><br>
<h2>Webnet Official Crawler</h2>
<form action="" method="GET">
<input type="text" name="q" size="50" placeholder="Enter URL / LINK with http://">
</form>
<p>&bull; Sites without keywords wont be displayed</p>
<p>&bull; Sites with https wont be displayed</p>
<p>&bull; All links crawled will get inserted to database automatically</p>
<p>&bull; To fix site without keywords shall be applied manually from Database</p>
</center>



<?php
error_reporting(0);
$start = $_GET["q"];

$already_crawled = array();
$crawling = array();

function get_details($s_link) {


	$options = array('http'=>array('method'=>"GET", 'headers'=>"User-Agent: howBot/0.1\n"));
	
	$context = stream_context_create($options);

	$doc = new DOMDocument();
	
	@$doc->loadHTML(@file_get_contents($s_link, false, $context));

	
	$s_title = $doc->getElementsByTagName("title");
	
	$s_title = $s_title->item(0)->nodeValue;
	
	$s_des = "";
	$s_key = "";
	
	$metas = $doc->getElementsByTagName("meta");
	
	for ($i = 0; $i < $metas->length; $i++) {
		$meta = $metas->item($i);
		
		if (strtolower($meta->getAttribute("name")) == "description")
			$s_des = $meta->getAttribute("content");
		if (strtolower($meta->getAttribute("name")) == "keywords")
			$s_key = $meta->getAttribute("content");

	}
	
	echo $s_title ;?><br>
	<?php echo $s_link; ?><br>
	<?php echo $s_des; ?><br>
	<?php echo $s_key; ?><br><br><br><hr>
	<?php
$host="localhost";
 $username="username";
 $password="password";
 $databasename="id3475686_search";
 $connect=mysqli_connect($host,$username,$password);
 $db=mysqli_select_db($connect,$databasename);

 mysqli_query($connect, "insert into website(site_title, site_link, site_key, site_des) values('$s_title', '$s_link', '$s_key', '$s_des')");
 
}

function follow_links($url) {
	
	global $already_crawled;
	global $crawling;

	$options = array('http'=>array('method'=>"GET", 'headers'=>"User-Agent: howBot/0.1\n"));

	$context = stream_context_create($options);

	$doc = new DOMDocument();

	@$doc->loadHTML(@file_get_contents($url, false, $context));

	$linklist = $doc->getElementsByTagName("a");

	foreach ($linklist as $link) {
		$l =  $link->getAttribute("href");
		
		if (substr($l, 0, 1) == "/" && substr($l, 0, 2) != "//") {
			$l = parse_url($url)["scheme"]."://".parse_url($url)["host"].$l;
		} else if (substr($l, 0, 2) == "//") {
			$l = parse_url($url)["scheme"].":".$l;
		} else if (substr($l, 0, 2) == "./") {
			$l = parse_url($url)["scheme"]."://".parse_url($url)["host"].dirname(parse_url($url)["path"]).substr($l, 1);
		} else if (substr($l, 0, 1) == "#") {
			$l = parse_url($url)["scheme"]."://".parse_url($url)["host"].parse_url($url)["path"].$l;
		} else if (substr($l, 0, 3) == "../") {
			$l = parse_url($url)["scheme"]."://".parse_url($url)["host"]."/".$l;
		} else if (substr($l, 0, 11) == "javascript:") {
			continue;
		} else if (substr($l, 0, 5) != "https" && substr($l, 0, 4) != "http") {
			$l = parse_url($url)["scheme"]."://".parse_url($url)["host"]."/".$l;
		}
	
		if (!in_array($l, $already_crawled)) {
				$already_crawled[] = $l;
				$crawling[] = $l;
				
				echo get_details($l)."\n";
		}

	}
	
	array_shift($crawling);
	
	foreach ($crawling as $site) {
		follow_links($site);
	}

}

follow_links($start);
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>$(document).ready(function(){ 	$('body').find('img[src$="https://cdn.rawgit.com/000webhost/logo/e9bd13f7/footer-powered-by-000webhost-white2.png"]').remove();    }); </script>
<script>window.onload = () => {    let el = document.querySelector('[alt="www.000webhost.com"]').parentNode.parentNode;    el.parentNode.removeChild(el);}</script>
