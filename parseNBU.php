<?

$dates = "

20.07.2015 11:47
06.08.2015 09:18
20.08.2015 16:52
06.10.2015 14:08

";

set_time_limit('500');

header("Content-Type: text/html; charset=utf-8");

define("BASE_URL", "http://www.bank.gov.ua/control/uk/curmetal/currency/search?formType=searchFormDate&time_step=daily&date=");

switch($_GET['currency']) {

	default:
		$curr = "USD";
		$pregMatchSearch = '/USD(.*)Долар США(.*?)978(.*)EUR/s';
		break;
	case "EUR":
		$curr = "EUR";
		$pregMatchSearch = '/EUR(.*)Євро(.*?)352(.*)ISK/s';
		break;

}

function getCurrencyExchangeRates($dates, $pregMatchSearch, $data = "") {

	foreach(explode("\n",trim($dates)) as $key=>$val) {

		$search = array("\n","  ");
		$replace = array(" ","");
		$date = date("d.m.Y", strtotime($val));

		$uri = BASE_URL.$date;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $uri);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

		$content = str_replace($search,$replace, strip_tags(curl_exec($ch)));
		
		preg_match($pregMatchSearch,$content,$match);

		curl_close($ch);

		$data .= "\n<div class='row'>$date ".trim($match['2'])/100 . "<br><a href='$uri'>src</a></div>";

		flush();
		
		sleep(rand("1","2"));

	}

return $data;

}

?>

<style>
.row {margin: 10px 0;}
.row a {font-size: 70%;}
</style>
<h1>Currency exchange rate <b>UAH</b> to <b><?=$curr;?></b></h1>

<?=getCurrencyExchangeRates($dates, $pregMatchSearch); ?>