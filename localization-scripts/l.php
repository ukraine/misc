<meta charset="utf-8"><?

error_reporting(E_ALL);

// debug
// foreach($_GET as $key=>$val) {echo "$key=>$val<br>";}

include $_GET['f'];

$langVarName = "language";

if (!empty($_GET['langVarName'])) $langVarName = $_GET['langVarName'];

foreach (${$langVarName} as $key=>$val) {

	switch($_GET['type']) {
	
		default: 	echo "$val<br>"; break;
		case "key": 	echo "$key<br>"; break;

	}

}