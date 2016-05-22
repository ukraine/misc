<meta charset="UTF-8">
<link rel="stylesheet" href="//yui.yahooapis.com/pure/0.6.0/pure-min.css">
<?

// Добавить перерыв
// Вычислить макс кол-во колонок, базируясь на колве экспертов, колве возможных сеансов и колве проектов
// Вставлять названия проектов ВЕРТИКАЛЬНО в ячейки, где перерыв - ничего не вставляем

$langSource = "

txtDebugHead	Информация для отладки скрипта
txtNumberOfExpertsRows	Количество экспертов строк
txtNumberOfProjects	Количество проектов
txtNumberOfOptions	Количество опций
txtTotalSessionDuration	Общая длительность всей экспертной сессии, в минутах
txtTimePerIndividualSession	Интервал сессии на проект, в минутах
txtTimeToSwitchExpert	Время на смену командами экспертов, в минутах
txtTimeForPizzaBreak	Время на перерыв, в минутах
txtMaxPossibleNumberOfSessions	Возможное количество сеансов по формуле t <sub>(общее)</sub> / t <sub>(сеанса)</sub>
txtActualTotalValuableTimeMinusShiftsAndPizza	Фактически полезное время за вычетом пересменок и перерыва: t <sub>(общее)</sub> - t <sub>(общий перерыв)</sub> - t <sub>(пересменка)</sub> * n <sub>(экспертов)</sub>
txtMaxPossibleSessionsPerExpert	Максимально возможных сеансов на эксперта, с округлением в большую сторону: t <sub>(полезного времени)</sub> / t <sub>(сеанса)</sub>
txtPreliminaryResult	Предварительный результат
txtProjects	Проекты
txtExperts	Эксперты
txtOptions	Опции
txtOption	Опция: 
txtHintOneRowOneExpert	Одна строка = 1 эксперт
txtHintOneRowOneProject	Одна строка = 1 проект
txtHintOneRowOneOption	Одна строка = одна опция, только целые числа!
txtGenerateChesser	Сформировать шахматку

";

function getLang($source, $lang="") {

	foreach (explode("\n", trim($source)) as $key=>$val) { $langProperties = explode("\t", $val); $lang[$langProperties[0]]=$langProperties[1]; }
	return $lang;

}

function parseTextarea($key) {

	if (!empty($key)) { $details = explode("\n",$key); }
	return $details;
}

function getOptions($options) {

	foreach ($options as $key=>$val) { $option[] = $val; }
	return $option;

}

function savedData($key) {

	if (!empty($_POST[$key])) {return $_POST[$key]; }

}

function generateTableHeader($option) {

for ($i = 1; $i < $option['numberOfActualSessions']+1; $i++) {

	$result .="<td>Сеанс $i</td>";

}

return $result;

}


function generateTable ($projects, $experts, $option) {

	$rows = "<tr><th>\</th>" . generateTableHeader($option) . "</tr>";

	foreach ($experts as $key=>$val) {$rows .= "<tr><th>$val</th>" . str_repeat("<td>ПРОЕКТ</td>",$option['numberOfActualSessions']+1) . "</tr>";}

	return $rows;

}

// Инициализация

$experts = parseTextarea($_POST['experts']);
$projects = parseTextarea($_POST['projects']);
$options = parseTextarea($_POST['options']);

// Получаем языковую версию

$lang = getLang($langSource);

if (!empty($_POST['experts'])) {

$option = getOptions($options);

// Делаем первые расчеты

$option['numberOfPossibleSessions'] = $option[0] / $option[1];
$option['totalValuableTime'] = $option[0] - $option[2] * count($experts)  - $option[3];
$option['numberOfActualSessions'] = $option['totalValuableTime'] / $option[1];

echo "

<h2>$lang[txtDebugHead]</h2>
    $lang[txtNumberOfExpertsRows]: " . count($experts) . "
<br>$lang[txtNumberOfProjects]: " . count($projects) . "
<br>$lang[txtNumberOfOptions]: " . count($options) . "

<br>$lang[txtTotalSessionDuration]: " . $option[0] . "
<br>$lang[txtTimePerIndividualSession]: " . $option[1] . "
<br>$lang[txtTimeToSwitchExpert]: " . $option[2] . "
<br>$lang[txtTimeForPizzaBreak]: " . $option[3] . "

<br>$lang[txtMaxPossibleNumberOfSessions]: $option[numberOfPossibleSessions]
<br>$lang[txtActualTotalValuableTimeMinusShiftsAndPizza]: $option[totalValuableTime]
<br>$lang[txtMaxPossibleSessionsPerExpert]: " . round($option['numberOfActualSessions']);

// echo "<br>Нужных сеансов на эксперта в зависимости от кол-ва проекта: округлить в большую сторону t (полезного времени) / t (сеанса): " . round($option[numberOfActualSessions]);

}

?>

<body class="body" style="margin-left: 25px">

<h2><?=$lang['txtPreliminaryResult']?></h2>
<table class="pure-table pure-table-horizontal">
<? echo generateTable($projects, $experts, $option); ?>
</table>

<form action="" method="post" class="pure-form">

<h2><?=$lang['txtExperts']?></h2>
<textarea name="experts" placeholder="<?=$lang['txtHintOneRowOneExpert']?>"><?=savedData("experts")?></textarea>

<h2><?=$lang['txtProjects']?></h2>
<textarea name="projects" placeholder="<?=$lang['txtHintOneRowOneProject']?>"><?=savedData("projects")?></textarea>

<h2><?=$lang['txtOptions']?></h2>
<p><? echo "
{$lang[txtoption]}$lang[txtTotalSessionDuration]<br>
{$lang[txtoption]}$lang[txtTimePerIndividualSession]<br>
{$lang[txtoption]}$lang[txtTimeToSwitchExpert]<br>
{$lang[txtoption]}$lang[txtTimeForPizzaBreak]</p>";

?>

<textarea name="options" placeholder="<?=$lang['txtHintOneRowOneOption']?>"><?=savedData("options")?></textarea>

<br><br>
<input type="submit" value="<?=$lang['txtGenerateChesser']?>" class="pure-button pure-button-primary">
</form>

</body>