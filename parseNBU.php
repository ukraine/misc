<?

$dates = "

20110204T09:10:00
20110412T10:01:00

";

set_time_limit('500');

header("Content-Type: text/html; charset=utf-8");

$url = "http://www.bank.gov.ua/control/uk/curmetal/currency/search?formType=searchFormDate&time_step=daily&date=";

foreach(explode("\n",trim($dates)) as $key=>$val) {

	$search = array("\n","  ");
	$replace = array(" ","");

	$uri = $url.date("d.m.Y", strtotime($val));

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $uri);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$content = str_replace($search,$replace, strip_tags(curl_exec($ch)));
	preg_match('/USD(.*)Долар США(.*?)978(.*)EUR/s',$content,$match);
	curl_close($ch);

	echo "\n<br>$uri\t" . date("d.m.Y h:s", strtotime($val)) . "\t	".trim($match['2'])/100;

	flush();
	
	sleep(rand("1","2"));
}