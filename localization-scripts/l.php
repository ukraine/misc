<meta charset="utf-8"><?

error_reporting(E_ALL);

// foreach($_GET as $key=>$val) {}

include $_GET['f'];


foreach ($language as $key=>$val) {

	switch($_GET['type']) {
	
		default: 	echo "$val<br>"; break;
		case "key": 	echo "$key<br>"; break;

	}

}