<meta charset="utf-8"><?

error_reporting(E_ALL);

$lang['totalFiles']  = "Total text strings: ";

// debug
// foreach($_GET as $key=>$val) {echo "$key=>$val<br>";}

include $_GET['f'];

$totalStrings = "Total strings: ";
$langVarName = "language";
$type = "val";

if (!empty($_GET['langVarName'])) $langVarName = $_GET['langVarName'];
if (!empty($_GET['type'])) $type = $_GET['type'];

echo $totalStrings . count(${$langVarName}) . "<br><br>";

foreach (${$langVarName} as $key=>$val) {

	switch($type) {
	
		default: 	echo "$val<br>"; break;
		case "key": 	echo "$key<br>"; break;
		case "formattedKey": 	echo "'$key' =><br>"; break;
		case "formattedVal": 	echo "'$val',<br>"; break;

	}

}