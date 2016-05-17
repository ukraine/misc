<?

// Добавить сокращения описания, если длиннее N
// Добавить еще имя доктора
// Добавить еще затраты на каждую процедуру

ini_set("display_errors","On");
error_reporting(0);

$hl= "ru";
if (!empty($_GET['hl'])) $hl=$_GET['hl'];

define("HL",$hl);
define("MAXCHAR","40");

$lang['ru'] = array(

	"diagnosis"=>"Д-з:",
	"diagnosisVal"=>"Болезнь Грэйвса, тиеротоксикоз",
	"date"=>"Дата",
	"event"=>"Событие",
	"type"=>"Тип",
	"filter" => "Начинайте вводить слово для фильтрации данных...",

);

$lang['en'] = array(

	"diagnosis"=>"Diagnosis:",
	"diagnosisVal"=>"Graves disease, Hyperthyroidism",
	"date"=>"Date",
	"event"=>"Event",
	"type"=>"Type",
	"filter" => "Start typing a word to filter the results",

);


?><meta charset=utf8>
<link rel="stylesheet" href="//yui.yahooapis.com/pure/0.6.0/pure-min.css">
<link rel="stylesheet" href="//saeedalipoor.github.io/icono/icono.min.css"> 
<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>

<script type='text/javascript'>//<![CDATA[
$(window).load(function(){
$("#searchInput").keyup(function () {
    //split the current value of searchInput
    var data = this.value.toUpperCase().split(" ");
    //create a jquery object of the rows
    var jo = $("#results").find("tr");
    if (this.value == "") {
        jo.show();
        return;
    }
    //hide all the rows
    jo.hide();

    //Recusively filter the jquery object to get results.
    jo.filter(function (i, v) {
        var $t = $(this);
        for (var d = 0; d < data.length; ++d) {
            if ($t.text().toUpperCase().indexOf(data[d]) > -1) {
                return true;
            }
        }
        return false;
    })
    //show the rows that match.
    .show();
}).focus(function () {
    this.value = "";
    $(this).css({
        "color": "black"
    });
    $(this).unbind('focus');
}).css({
    "color": "#C0C0C0"
});
});//]]> 

</script>

<style type='text/css'>
.header {
    font-family: "Raleway", "Helvetica Neue", Helvetica, Arial, sans-serif;
    max-width: 768px;
    margin: 0 auto 25px;
    padding: 1em;
    text-align: center;
    border-bottom: 1px solid #eee;
    background: #fff;
    letter-spacing: 0.05em;
}

.header h1 {
    font-size: 320%;
	margin: 0;
	font-weight: 100;
}

.header h2 {
    font-size: 128%;
	font-weight: 100;
    line-height: 1.5;
    margin: 0;
    color: #666;
    letter-spacing: -0.02em;
}

.body {
    text-align: center;
}

</style>

<body class='body'>
<div align='center'>
<div class="header">
    <h2><?=$lang[HL]["diagnosis"] . " ". $lang[HL]["diagnosisVal"];?></h2>
  
</div>

<!-- 24/01 CT-Thymus-24-01-2016.zip -->

<div align="left">
<form role="search" method="get" class="search-form" action="#">
<input id="searchInput" type="text" placeholder="<?=$lang[HL]["filter"];?>" style='width: auto; min-width: 400px;'>
</form>
</div>

<table class="pure-table pure-table-horizontal">

  <thead>
        <tr>
            <th><?=$lang[HL]["date"];?></th>
            <th><?=$lang[HL]["type"];?></th>
            <th><?=$lang[HL]["event"];?></th>
        </tr>
    </thead>

    <tbody id="results">
<?

$file = fopen('history.' . HL . '.txt', 'r');

while (($line = fgetcsv($file,0,"\t")) !== FALSE) {

$ahrefStart = $ahrefEnd = $doubleDot = $threeDots = "";

// $line is an array of the csv elements
// print_r($line);
// $line[0]=strtotime($line[0]);
// $history[]=$line;
// echo "--\ $line[2] /--";

if (!empty(@trim($line[3]))) {

	$ahrefStart = "<a href='//nemovlyatko.com/health/$line[3]'>";
	$ahrefEnd	= "</a>";

}

if (!empty(trim(@$line[4]))) { $doubleDot	= ": <b>$line[4]</b>"; }
// if (strlen($line[2]) > MAXCHAR) { $threeDots = "..."; }


// echo "<tr><td>" . date("F d, Y", $line['0']) . "</td><td><a href='//nemovlyatko.com/health/$line[2]'>$line[1]</a></td>";
	echo "<tr><td>$line[0]</td><td>$line[1]</td><td title='$line[2]'>$ahrefStart{$line[2]}{$threeDots}$ahrefEnd{$doubleDot}</td>";
	flush();

}
fclose($file);

// print_r($history);

?>


    <tbody>
</table>