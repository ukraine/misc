<?

error_reporting(E_ALL);

include "lib/configs.php";
include "lib/shared.php";
include "main.func.tests.php";
include "default.func.php";
include "-tests.inc.php";

if ($_POST['dosometh']=="ratetest") {

	$details['id'] = $_POST['id'];
	$details['rate'] = $_POST['rate'];
	$details['rater_comment'] = $_POST['rater_comment'];

	edit_data ($details, "tests");

}

if (empty($_GET['test_id'])) $_GET['test_id'] = "3"; else { $urlPrefix = "?test_id=$_GET[test_id]&"; }
if (empty($_GET['grade']) && $_GET['grade'] != "0") $_GET['rate'] = "5"; else $_GET['rate'] = $_GET['grade'];


$maxGrades = "13";

function generateFilter($array, $urlPrefix = "?") { 

	$properties = explode(" ", $array);
	
	for ($i=0; $i < $array; $i++) {

		if ($_GET['grade'] == $i) $current = "current";
		$link .= " <a href='{$urlPrefix}grade=$i' class='$current'>$i</a> "; $current = "";

	} 
	return $link;
}

$currentGoogleTranslation = trim($GoogleTranslations[$_GET['test_id']]);
$currentGoogleTranslationHash = md5($currentGoogleTranslation);

define("GSTRLEN",mb_strlen($currentGoogleTranslation)) ;
define("HASHTEST",$currentGoogleTranslationHash);
define("MACHINETRANS",$currentGoogleTranslation);

?>
<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<!--<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">-->


<style type='text/css'>
body {font-family: arial; margin: 30px 0;}
a.current {font-weight: bold; text-decoration: none;}

.tests {

    border-bottom: 3px solid #CCCCCC;
    margin: 25px;
    padding-bottom: 25px;
    width: 500px;

}

.instructions {
    background: none repeat scroll 0 0 #E5E5E5;
    font-family: arial;
    font-size: 75%;
    left: 600px;
    overflow: auto;
    padding: 0 25px 25px;
    position: fixed;
	top: 104px;
    width: 500px;
}

.instructions h2 {color: #4F6791;}

.testarea {

	background: none repeat scroll 0 0 #F4F2A8;
    border: 0px solid #CCCCCC;
    clear: both;
	font-size: 70%;
    height: 250px;
    margin: 15px 0 5px;
    overflow: auto;
    padding: 12px;

}

.header {

    background: none repeat scroll 0 0 #D9DFEA;
    border-bottom: 3px solid #91A4C4;
    font-size: 90%;
    margin-left: 0;
    padding: 25px 25px 20px;
    position: fixed;
    top: 0;
    width: 100%;

}

.phishing {background-color: red !important;}

.instr {

    font-size: 90%;
    left: 600px;
    overflow: auto;
    position: fixed;
    top: 3px;
    width: 550px;

}

.stats {
	font-size: 70%;
	color: #ccc;
}

</style>
</head>

<body>

<div class='instructions'>

	<h2>Source</h2>
	<? echo nl2br(trim($tests[$_GET['test_id']])); ?>

	</div>

<div class='header'>

<!-- Tests <a href="?test_id=3">RU > EN</a> &nbsp; <a href="?test_id=5">EN > HE</a> &nbsp; <a href="?test_id=6">EN > KR</a> <a href="?test_id=7">IT > RU</a><br> -->

Test: <a href="?test_id=149">EN > DA</a><br>

<div class='instr'>
	<p><b>Assessment instructions: </b> Please choose the grade below each translation. Please also add a comment into the text field, clarifying your assessment. Then press the rate button to submit your assesment. Once the form has been submitted you won't be able to change it. That's all</p>
</div>

Sort by grades: <?=generateFilter($maxGrades, $urlPrefix); ?>

</div>

<div style='padding-top: 45px;'>
<? GenerateListOfSomeThing("tests"); ?>
</div>

</body>