<meta charset="utf-8"><?

error_reporting(E_ALL);

$lang['totalFiles']  = "Total text strings: ";

// debug

// foreach($_GET as $key=>$val) {echo "$key=>$val$nlbr";}

include $_GET['f'];

$totalStrings = "Total strings: ";
$langVarName = "language";
$type = "val"; // formattedKey formattedVal val key
$nlbr = "\n";

if (!empty($_GET['langVarName'])) $langVarName = $_GET['langVarName'];
if (!empty($_GET['nlbr'])) $nlbr = $_GET['nlbr'];
if (!empty($_GET['type'])) $type = $_GET['type'];

echo $type;

echo $totalStrings . count(${$langVarName}) . "$nlbr$nlbr";

ksort(${$langVarName});

foreach (${$langVarName} as $key=>$val) {

	$val = str_replace("\n","\\n", $val);

	switch($type) {
	
		default: 	echo "$val$nlbr"; break;
		case "key": 	echo "$key$nlbr"; break;
		case "formattedKey": 	echo "'$key' =>$nlbr"; break;
		case "formattedVal": 	echo "'$val',$nlbr"; break;
		case "keyval": 	echo "$key\t$val,$nlbr"; break;

	}

}