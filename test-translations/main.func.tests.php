<?

// Превращение массива в список
// Convert an array into a <li> list 
function listItems($items) { foreach ($items as $key=>$val) {echo "<li>$val</li>"; }}


// Уточнение, на странице заказа ли мы
// Are we on the ordering page
function isOrderPage() {

	if (!empty($_GET['id']) && $_GET['id']=="26" || !empty($_GET['id']) && $_GET['id']=="81" || !empty($_GET['id']) && $_GET['id']=="82") return true;

}

// Ограничитель количества видимых символов в строке
// Function allows limiting the number of visible characters according to a settings
// 03.08.2007
// 08.03.2008 threedots moved to init
function limitVisiblePart($fieldname, $limitto="22", $threedots = "") {
	
	if (strlen($fieldname) > $limitto) $threedots = "...";
	return substr(stripslashes(strip_tags($fieldname)),'0', $limitto).$threedots;

}

// Только для чистки URL и HTML тегов
// Cleans URL and HTML tags in a string
function trimmer($variable) {
	return trim(strip_tags(stripslashes($variable)));
}

// Проверка правильности заполнения полей
// Form fields checker
// 17.07.2007
function IsRequiredFieldsFilled($RequiredFielsArray) {

	global $error_msg, $translation;

		foreach($RequiredFielsArray as $key=>$value)	{
			if (empty($_POST[$key]) || strlen($_POST[$key]) < 4) $error_msg .= $translation['131'] . "\"<B>$value</B>\"" . $translation['132'];
		}
			if (empty($error_msg)) return 1;
			else return 0;
}

// Проверка наличия переменных в формах. Если есть - выводим
// Check if there is a value was submitted through the form. If exists - show it
// 16.07.2007
function ifExistGetValue($valuename) {

	global $f;
	if (isset($_POST[$valuename])) echo $_POST[$valuename]; 
	else echo $f[$valuename];
}

// Функция проверки наличия ошибок
// Checks if there is an error while submitting a form
// 16.07.2007
function ErrorMsg () {

	global $error_msg, $status;
	if (!empty($error_msg))	echo "<div class='error_msg' id='$status'>$error_msg</div>"; 
}


// Получение ID каталога
// Getting catalogueID
// 03.08.2007
function GetCategoryId ($cat_path) {

	global $CategoryId; $rescat = ExecuteSqlGetArray("SELECT id FROM `categories` WHERE cat_path='$cat_path'");
	$CategoryId =  $rescat['id'];

}

// Получение пути к файлу по его ID
// 03.08.2007
function GetFilePath ($id) {

	$rescat = ExecuteSqlGetArray("SELECT `path` FROM `files` WHERE id='$id'");
	return $rescat['path'];

}

// Определение имени страницы по пути и ид категории
// PageName Resolved by path and category_ID
// 03.08.2007
function GetPageName ($page_path, $cat_id) {

	global $PageName;

	$respage = ExecuteSqlGetArray("SELECT * FROM `pages` WHERE `page_path`='$page_path' AND `cat_id` = '$cat_id'");
	return  $respage['page_name'];

}

// Определение имени категории по ИД
// CatName Resolved by id
// 03.08.2007
function GetCatName ($id) {

	global $catPath;

	$respage = ExecuteSqlGetArray("SELECT cat_path FROM `categories` WHERE `id`='$id'");
	return  $respage['cat_path'];

}

// Подсветка текущей страницы
// 03.08.2007
function HighLightCurrentPage($section,$page) {

	global $cat_path, $page_path;
	if ($cat_path == $section && $page == $page_path) return "class='current'";
}


// Определение домашней страницы
// 03.08.2007
function IsHomePage() {

	global $page_path, $cat_path;

	if ($cat_path == "default" && $page_path == "default") {
	return 1;

	}

}

function getCatProdLinks($where="categories", $what="priority", $sort="desc", $limit="6", $position="", $separator=" &nbsp; ", $header="y") {

	/*	DATE ADDED: 08.10.2006 
		MOD DATE:	12.10.2006
		Comment:	issue with default category
	*/

	global $siteurl, $link;
	$CatLinks = "";

	if ($header == "y") $header = "AND cat_path != 'default'";

	$sql_result = mysqli_query ($link, "SELECT * FROM $where WHERE `visibility`='y' $header ORDER BY $what $sort LIMIT $limit");
	$num_links =  mysqli_num_rows($sql_result);

	for ($i=0; $i<$num_links; $i++){
		$row = mysqli_fetch_array($sql_result);
		$cat_name = ucfirst($row['cat_name']);
		if ($row['cat_path'] == "default") $cat_path = ""; else $cat_path = "$row[cat_path]/";
		$CatLinks .= "<a href='" . SITEURL . "$cat_path' target='Content'>$cat_name</a>$position\n";
		if ($i == ($num_links-1)) $CatLinks .= ""; else $CatLinks .= "$separator";
	}
	echo $CatLinks;

}



function getFAQ($showanswers, $shownumbers, $category, $howmanyfaq) {

	/*
	Version:	1.0
	Module:		FAQ generator
	Date:		17.09.2006
	*/

	global $siteurl, $link;

	$questions = "<a name=\"questions\"></a><br>\n";
	$faqcategory = "";
	$number = "";
	$shownumber = "";
	$answers = "<br><br>\n";

	if ($category !== "") $faqcategory = "AND cat_id=$category";
	if ($howmanyfaq !== "") $howmanyfaq = "LIMIT 0,$howmanyfaq";

	$sql_res = mysqli_query($link, "SELECT * FROM `faq` WHERE `visibility`='y' $faqcategory ORDER BY `priority` desc $howmanyfaq");
	$num_faq = mysqli_num_rows($sql_res);

	for ($i=0; $i<$num_faq; $i++){
		$row = mysqli_fetch_array($sql_res);
		$question = ucfirst($row['question']);
		$answer = ucfirst($row['answer']);
		$number = $i+1;
		// if ($popup == "y");
		if ($shownumbers == "y") $shownumber = $number.".";
			$questions .= "<div class='justquestion'>$shownumber <a href='#$number'>$question</a></div>\n";
		if ($showanswers == "y") $answers .= "<div class='questionandanswer'>
			<div class='question'><a name='$number'></a>$shownumber <b>$question</B></div>\n
			<div class='answer'> $answer</div>\n
			<div class='backtotop'><a href='#questions'>to the top</a></div>\n
			</div>";
		
}

	echo $questions;
	echo $answers;

}


function getRelatedLinks($CategoryId, $symbols, $quantity="999") {

	/*
	Version: 1.0
	Date:		09.10.2006
	Modified:	17.06.2007	Allowed to show only numbers of related pages for artemide.ru
	*/

	global $RelatedLinks, $cat_path, $page_path, $siteurl, $link;

	$sql_result = mysqli_query ($link, "SELECT * FROM `pages` WHERE `visibility`='y' AND `cat_id` = '$CategoryId' AND `cat_id` != '4' AND `page_path` != 'view' ORDER BY `priority` desc limit $quantity");
	$num_links =  mysqli_num_rows($sql_result);

	if ($num_links > 1) { 

		for ($i=0; $i<$num_links; $i++){
			$row = mysqli_fetch_array($sql_result);
			$number = $i+1;

			$content = strip_tags(substr(stripslashes($row["description"]), 0, 250))." ...";
			$RelatedLinks .= "<a href='" . SITEURL . "$cat_path/$row[page_path]/' target='Content'>$number</a> &nbsp;";

		}

		return $RelatedLinks;

	}

}

function getNews($quantity="", $isonthefirstpage="n") {

	/*
	Version:	1.0
	Module:		NewsList generator
	Date:		22.09.2006
	Mod:		if no articles, ask user to add it
				12.10.2006 - $titlesymbols - Symblos allowed in the title 
				23.10.2006 - added $news after global (was not declared)
				17.02.2007 - Showing more short version of the date 
				16.06.2007 - Show some articles only the first page
	*/


	global $siteurl, $cat_path, $Months;

	$news = $linka = $onthefirstpage = "";

	if ($isonthefirstpage=="y") $onthefirstpage = "AND `onthefirstpage` = '1'";

	if ($cat_path=="default") {
		$linka = "<div style='width:85%;padding: 5px 0;' align=right><a href='/news/'>Архив новостей...</a></div>";
		$quantity = 3;
	}

	if (!empty($quantity)) $quantity = "LIMIT $quantity";

	$sql_res = mysqli_query($link, "SELECT * FROM `news` WHERE `visibility`='y' $onthefirstpage ORDER BY `timestamp` desc $quantity");
	$num =  mysqli_num_rows($sql_res);

	if ($num == 0) $news = "<div id='content'>Пока никаких новостей</div>";

		for ($i=0; $i<$num; $i++){
			$row = mysqli_fetch_array($sql_res);

				$news .= 

					
					"\n\t<div class='news'>
					<span class='date'>". ConvertMysqlTimeStamp($row['timestamp'], ".") ."</span><p>"
					. limitVisiblePart($row['content'],"612"). 
					
					"</p></div>";						

		}


	echo $news.$linka;

}

// Времена суток
function sutki() {

	$time = date("G", time()+18000);

	if ($time > 9 && $time < 18 ) echo "day";
	else echo "night";
}


// Вывод определенной новости, согласно идентификатору из строки адреса
function getNewsByID() {

	/*
	Version:	1.1	WITH CNVRTSQLTME (MySQL v4 support)
	Module:		An article generator
	04.10.2006:		removed convertSQLtime
	18.11.2006:		Added convertSQLtime
	*/

	global $siteurl, $timestamp;

	$row = ExecuteSqlGetArray("SELECT * FROM `news` WHERE `id`=$_GET[id] AND `visibility`='y'");

	if ($row != false) {
		
		$title = ucfirst($row['title']);
		return "\n\t<div class='news'><img src='/img/coffe.gif' width='50' height='11' /><B>"
					. strip_tags(stripslashes(ucfirst($row['title']))) . "</B><br>"
					. $row['content'] . "</div>";	


	}


}


function getSubLinks($section="default") {

	global $CategoryId, $cat_path, $page_path, $link;

	if ($section == $cat_path) {

		GetCategoryId ($section);

		$sql_result = mysqli_query ($link, "SELECT * FROM `pages` WHERE `visibility`='y' AND `cat_id` = '$CategoryId' AND `page_path` !=  'default' ORDER BY `priority` desc");
		$num_links =  mysqli_num_rows($sql_result);

		if ($num_links > 0) {

			$SubLinks="<ul>";
			for ($i=0; $i<$num_links; $i++){
				$row = mysqli_fetch_array($sql_result);

				if ($row['page_path'] == $page_path) $SubLinks .= "<li>$row[page_name]</li>";
				else 	$SubLinks .= "<li><a href='/$cat_path/$row[page_path]/'>$row[page_name]</a></li>";
				
			
			}
			echo $SubLinks."</ul>";	

		}

	}

}

// 27.03.2008
// Removed $do = $_POST['dosome...]
function PostParser() {

	global $error_msg;

	switch($_POST['dosometh']) {

	default:

		break;

	// Работа с подписчиками
	case "do_subscribe":

		// Таблица базы данных, с которой работаем
		$table = "subscribers";
		$what = "email";
		
		// Проверка правильности емейл адреса
		if (check_email_address() && empty($error_msg)) {

			// Если всё хорошо, цеплям дефолтовые функции по работе с БД
			include "mad/lib/default.func.php";
			
			// Если человек подписывается
			if ($_POST['do']=="sub") {

				// Делаем вставку
				insert_data ($details, $table);

				// Выводим сообщение о занесении емейла в БД
				$error_msg = "Вы успешно подписались";
			
			}

			else {

				// Удаляем подписчика из БД
				delete_data ($what, $table, $_POST['email']);

				// Выводим сообщение о занесении емейла в БД
				$error_msg = "Вы успешно отписались. Больше мы вам ничего не пошлем";
			
			}

		}

		break;

	case "contact":

		global $ContactEmailForm, $estimatedetails;

		$_POST['subject'] = "1translate.com - FeedBack form";

//		print_r($_FILES);

		// Проверка формы
		if (IsRequiredFieldsFilled($ContactEmailForm)) {

			// Генерируем quote ID 
			$_POST['quote_ID'] = date('dmYHis');

			// Пишем файл на диск и даем на него ссылку в переменную
			$_POST['uploadedfile'] = SaveFileToDisk($_POST['quote_ID']);

			// Отправляем уведомление по почте
			EmailFormSubmitter2("contact");
	
			// Уведомление об отправке
			$error_msg = "<p><B>Your message was successfully sent</B>.<br>
			You should receive a copy of it in your email within 5-10 minutes.</p>

<p>If you don't receive a reply from us within 24 hours,
please email us directly at requests14@1translate.com
and include this tracking number <B>$_POST[quote_ID]</B>.
Don't forget to add our email to your whitelist.</p>
			
			
			";

			// Возвращаем статус об успехе операции
			$estimatedetails['status'] = "1";

		} 

		break;

	case "submittest":

		global $TranslatorTestApplication;

		if (IsRequiredFieldsFilled($TranslatorTestApplication)) {

			// Отправляем уведомление по почте
			// EmailFormSubmitter2("translator_test_application");

			$_POST['ip'] = $_SERVER['REMOTE_ADDR'];

			// Если всё хорошо, цеплям дефолтовые функции по работе с БД
			include "mad/lib/default.func.php";

			unset($_POST['xip'], $_POST['url']);
			$_POST['visibility'] = 'y';

			// Делаем вставку
			insert_data ($_POST, "tests");

			// Уведомление об отправке
			$error_msg = "<p><B>Your test application successfully submitted</B></p>";

			// Возвращаем статус об успехе операции
			$estimatedetails['status'] = "1";


		}

		break;

	case "trapplication":

		global $TranslatorApplication;

		if (IsRequiredFieldsFilled($TranslatorApplication)) {

			// Отправляем уведомление по почте
			EmailFormSubmitter2("translator_application");

			include "mad/lib/default.func.php";

			insert_data($_POST,"translators");

			// Уведомление об отправке
			$error_msg = "<p><B>Your application successfully submitted</B></p>";

			// Возвращаем статус об успехе операции
			$estimatedetails['status'] = "1";


		}

		break;

	case "trapplication2":

		global $TranslatorApplication;

		if (IsRequiredFieldsFilled($TranslatorApplication)) {

			// Отправляем уведомление по почте
			EmailFormSubmitter2("translator_application");

			include "mad/lib/default.func.php";

			insert_data($_POST,"translators");

			// Уведомление об отправке
			$error_msg = "<p><B>Your application successfully submitted</B></p>";

			// Возвращаем статус об успехе операции
			$estimatedetails['status'] = "1";

		}

		break;

	case "quotenow":

		global $QuoteRequestFields, $estimatedetails;

		$emailTemplate = "quote_request";

		// Проверка формы
		if (IsRequiredFieldsFilled($QuoteRequestFields)) {

			// Генерируем quote ID 
			$_POST['quote_ID'] = date('dmYHis');

			// Пишем файл на диск и даем на него ссылку в переменную
			$_POST['uploadedfile'] = SaveFileToDisk($_POST['quote_ID']);

			if (@$_GET['service']=="expoInterpreter") $emailTemplate = "interpreter_quote";

			// Отправляем уведомление по почте
			EmailFormSubmitter2($emailTemplate);
	
			header("Location: " . SITEURL . "26?success=1&quote_id=$_POST[quote_ID]");

		} 

		break;

	case "addurl":

		global $LinkSubmissionForm, $estimatedetails, $Settings;

		// Проверка формы
		if (IsRequiredFieldsFilled($LinkSubmissionForm)) {

			// Отправляем уведомление по почте
			EmailFormSubmitter();

			// Уведомление об отправке
			$error_msg = "Link successfully submitted for a review";

			// Возвращаем статус об успехе операции
			$estimatedetails['status'] = "1";

		}

		break;

	case "instaquote":

		global $InstantQuoteRequest, $estimatedetails, $Settings;

		// Проверка формы
		if (IsRequiredFieldsFilled($InstantQuoteRequest)) {
	
			// Получаем цену за слово
			$ppw = explode("_",$_POST['area_id']);

			// Получаем названия языков
			$sourcelng = explode("_",$_POST['source_id']);
			$targetlng = explode("_",$_POST['target_id']);

			// Считаем слова 
			// Added for tool based on the known wordcount 10.11.2009
			if (!empty($_POST['wordcount'])) $estimatedetails['wordcount'] = intval($_POST['wordcount']);
			else $estimatedetails['wordcount'] = str_word_count($_POST['source_text']);
			
			// множим кол-во слов на цзс, получаем стоимость
			$estimatedetails['estimated_price'] = $estimatedetails['wordcount'] * $ppw['1'];

			// Если стоимость меньше допустимой, ставим минимально допустимую
			if ($estimatedetails['estimated_price'] < $Settings['minimal_request_price']) $estimatedetails['estimated_price'] = $Settings['minimal_request_price'];

			// Возвращаем статус об успехе вычислений
			$estimatedetails['status'] = "1";

			// Название области перевода
			$estimatedetails['areaname'] = $ppw['2'];

			// Идентификатор области перевода
			$estimatedetails['area_id'] = $ppw['0'];

			// Название исходного языка
			$estimatedetails['source_language'] = $sourcelng['1'];

			// Название целевого языка
			$estimatedetails['target_language'] = $targetlng['1'];

			// ID исходного языка
			$estimatedetails['source_id'] = $sourcelng['0'];

			// ID целевого языка
			$estimatedetails['target_id'] = $targetlng['0'];

			// Даем пользователю инфо

			// о днях на перевод
			$estimatedetails['days'] = ceil($estimatedetails['wordcount']/2000);

			// приблизительной дате выполнения работы
			$estimatedetails['dateforecast'] = date("F d, Y", mktime(0,0,0,date("m"),date("d")+$estimatedetails['days'],date("Y")));


		} 

		break;

	}

}

// Записываем информацию в куки о том, откуда пришел посетитель
function RefCookie() {

	// 21.11.2008
	if (empty($_COOKIE['referal'])) @setcookie("referal",$_SERVER['HTTP_REFERER']);
}


// Запись файлов на диск
// 27.03.2008
function SaveFileToDisk($quote_id="") {

	global $ForbiddenChars;
	$replace = array("","","","","","");

	$FileName = str_replace($ForbiddenChars,$replace,$_FILES['uploadfile']['name']);
	$FileName = str_replace(" ","-",$FileName);
	$FileName = str_replace("_","-",$FileName);

	// Копируем файл физически на сервере
	if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], FILESTORAGEPATH."$quote_id-$FileName"))
	
	// Возвращаем путь к файлу
	return URLSTORAGEPATH."$quote_id-$FileName";
}

// Простой сабмиттер содержимого любой формы
// 31.03.2008 Added Cookie, IP and URL
function EmailFormSubmitter ($content="", $subject="", $ctype="0")	{

	global $Settings;

		$_POST['ip']	= $_SERVER['REMOTE_ADDR'];
		$_POST['url']	= $_SERVER['REQUEST_URI'];
		$_POST['referal']	= $_COOKIE['referal'];

		if (empty($content)) {
			foreach($_POST as $key=>$val) $content.="$key : $val \n";
		}

	// Формирование шапки в зависимости от наличия файлов
	$headers = 
		"From: $_POST[name] <$_POST[email]>\r\n" .
		"Reply-To: $_POST[name] <$_POST[email]>\r\n" .
		"MIME-version: 1.0\n".
		"Content-type: text/plain; charset=\"UTF-8\"";

		$file = @fopen("mail.html","a");
		
		fseek($file, 0, SEEK_END);
		fwrite($file, "\n\n$content--------------------------------\n\n\n");
		fflush($file);

		mail($Settings['email'], $_POST['subject'], $content, $headers);

}


// Простой сабмиттер содержимого любой формы
// 31.03.2008 Added Cookie, IP and URL
// 27.12.2009 Added a template based replies + auto-replies
function EmailFormSubmitter2 ($template="", $subject="", $ctype="0")	{

	global $Settings;

	$content = file_get_contents("templates/emails/$template.html");

		$_POST['ip']	= $_SERVER['REMOTE_ADDR'];
//		$_POST['xip']	= $_SERVER['HTTP_X_FORWARDED_FOR'];
		$_POST['url']	= $_SERVER['REQUEST_URI'];
		$_POST['referal']	= $_COOKIE['referal'];

		// Удаляем значения стоимости слова в начале ключа area
		foreach($_POST as $key=>$val) {
			if (!empty($val)) $content = str_replace("%" . strtoupper($key) . "%", preg_replace('/(.*)_/','',$val), $content);
		}

		// Меняем оставшиеся и необработанные переменные на n/a
		$content = preg_replace('/%(.*)%/','n/a',$content);



		// echo $content;

	// Формирование шапки в зависимости от наличия файлов
	$headers = 
		"From: $_POST[name] <$_POST[email]>\r\n" .
		"Reply-To: $_POST[name] <$_POST[email]>\r\n" .
		"CC: $_POST[name] <$_POST[email]>\r\n".
		"BCC: <yuriy.yatsiv@gmail.com>\r\n".
		"MIME-version: 1.0\n".
		"Content-type: text/html; charset=\"UTF-8\"";

		$file = @fopen("mail.html","a");
		
		fseek($file, 0, SEEK_END);
		fwrite($file, "<br><br>$content<br><br>");
		fflush($file);


		mail("requests14@1translate.com", $_POST['subject'], $content, $headers);

}

// 27.03.2008
function check_email_address() {

	$email = $_POST['email'];

	global $error_msg;

	$err = "Пожалуйста, введите верный адрес";
  // First, we check that there's one @ symbol, and that the lengths are right
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
	// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
	$error_msg = $err;
	return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
	 if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
		$error_msg = $err;
	  return false;
}
  }  
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
	$domain_array = explode(".", $email_array[1]);
	if (sizeof($domain_array) < 2) {
		$error_msg = $err;
		return false; // Not enough parts to domain
}
	for ($i = 0; $i < sizeof($domain_array); $i++) {
	  if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
		$error_msg .= $err;
		return false;

  }
}
  }
  return true;
}


function sendmail($subject, $str="")
	
	{
		global $Settings, $ForbiddenChars, $AllowedChars;

		foreach($_POST as $key=>$val)
			{
				$str.= "$key :".strip_tags(str_replace($ForbiddenChars, $AllowedChars, $val))." \n";
			}
		if(mail($Settings['email'], $subject, $str))
			{
				$error_msg = "Your message was successfully sent.";
				return 1;
			}
		else
				$error_msg = "Your message was not sent. Please try again later";
	}

// Получение данных о странице в массиве
// 21.10.2007
// Добавлены настройки сео по умолчанию
// 19.12.2007 Новые правила для сайта с многовложенностью.
// 18.11.2009 Работа с документами для добавления h1,title,kw
function PageGen($id) {
	
	global $AllowedChars, $ForbiddenChars, $Settings;
	
	// Контент по умолчанию
	$content = "";

	$page = ExecuteSqlGetArray("SELECT * FROM `".PREFIX."pages` WHERE `id`='$id' AND (`visibility`='y' OR `visibility`='d')");
	
		if (!empty($page['content'])) {
			$page['content'] = str_replace($AllowedChars, $ForbiddenChars, $page["content".LANGUAGE]);
		}
		else $page['content'] = "Unfortunately this page was either deleted or moved somewhere else. Please refer to <a href='24'>our sitemap</a> and find the page you need there";

		// Добавление h1 and title для страниц вроде "заказа или инфо о конкретном документе"
		if ($id == 50) {

			$docDetails = ExecuteSqlGetArray("

	SELECT * FROM `".PREFIX."rates`
	WHERE `visibility`='y' 
	AND `fixedrate` != '0'
	AND `id` = '" . intval(trimmer($_GET["document_type"])). "'			
			
			");
			
			$DocName = rtrim($docDetails['name'],"s");
			$page['title'] = $page['h1'.LANGUAGE] = "Ukrainian/Russian $DocName translation services (certified)";
			$page['description'] = $page['title'.LANGUAGE] . ", rates, turnaround time and how to order it";
			$page['keywords'] =  "$DocName, certified translation, russian translation, $DocName certified translation, $DocName certified translation rate";

			
		}

		// Сео-таги по умолчанию срабатывают в этом случае
		$seotags = @array("title" => $page['title'], "description" => $page['description'], "keywords" =>$page['keywords']);		
		foreach($seotags as $key=>$value) {
		
			if (empty($page[$key])) $page[$key] = $Settings['default_'.$key];

		}

		// Генерация текущего пути
		$page['currentpath'] = getCurrentPathHome($page['id']);


	return $page;

}

function getSiteMapLinks($CategoryId) {

	/* 08.10.2006 */
	/* 17.02.2007 Removing the link to the same page as section root goes */
	/* 26.10.2007 A bit simplified */

	global $SiteMapLinks, $catPath, $siteurl, $link;

	$sql_result = mysqli_query($link, "SELECT * FROM `pages` WHERE `visibility`='y' AND `cat_id` = '$CategoryId' ORDER BY `priority` desc");
	$num_links =  mysqli_num_rows($sql_result);

	for ($i=0; $i<$num_links; $i++){
		$row = mysqli_fetch_array($sql_result);
		if ($row['page_path']!= "default" )$SiteMapLinks .= "<ol><a href='" . SITEURL . "$catPath/$row[page_path]/'>$row[page_name]</a></ol>";
}
	return $SiteMapLinks;

}


function getSiteMap () {

	/* 08.10.2006 */

	global $siteurl, $SiteMapLinks, $catPath, $Settings, $link;

	$CategoryAndPages = "<a href='" . SITEURL . "'>$Settings[sitename]</a><ul>";

	$sql_result = mysqli_query($link, "SELECT * FROM `categories` WHERE `visibility`='y' AND `cat_path` !='default' ORDER BY `priority` desc");
	$num_links =  mysqli_num_rows($sql_result);

	for ($i=0; $i<$num_links; $i++)	{

		$SiteMapLinks = "";
		$row = mysqli_fetch_array($sql_result);
		$cat_id = $row['id'];
		$catPath = $row['cat_path'];
		$CategoryAndPages .= "<li><a href='" . SITEURL . "$row[cat_path]/'>" . ucfirst($row['cat_name']) . "</a></li>\n";
		getSiteMapLinks($cat_id);
		$CategoryAndPages .= "$SiteMapLinks";

	}
	echo $CategoryAndPages."</ul>";

}

//
// Список функций для конкретного сайта
//


function convertGetIdIntoPlainVar ($getID="") {

	if (!empty($_GET['id'])) $getID = trim($_GET['id']);
	return $getID;

}

function generateTopNavigationMenu($footer="",$starttag="",$endtag="") {

	//	DATE ADDED: 27.12.2007

	global $siteurl, $Settings, $link;

	$getID = convertGetIdIntoPlainVar();

	$divTagStart = $divTagEnd = "";

	$sql_result = mysqli_query($link, "SELECT * FROM `".PREFIX."pages` WHERE `visibility`='y' AND `parent_id` = '1' ORDER BY priority DESC LIMIT 0,$Settings[itemsontopmenu]");
	$num_links =  mysqli_num_rows($sql_result);

	$numberoflinktoclose = $num_links-1;

	for ($i=0; $i<$num_links; $i++){

		$row = mysqli_fetch_array($sql_result);
		if ($i==$numberoflinktoclose) $starttag=str_replace("class='sep'","",$starttag);
		if ($row['id'] == $getID && $footer=="") {$divTagStart = "<div>"; $divTagEnd = "</div>"; }
		if ($row['id'] == $getID && $footer=="1") {$divTagStart = "<span>"; $divTagEnd = "</span>"; }
		echo "\n$starttag$divTagStart<a href='" . SITEURL . "$row[id]/'>" . $row["page_name".LANGUAGE] . "</a>$divTagEnd$endtag ";
		$divTagStart = $divTagEnd = "";
}

}


function generateSubMenu() {

	//	DATE ADDED: 29.12.2007

	global $siteurl, $Settings, $link;

	$sql_result = mysqli_query($link, "SELECT * FROM `".PREFIX."pages` WHERE `visibility`='y' AND `parent_id` = '$_GET[id]' ORDER BY priority ASC, id ASC");
	$num_links =  mysqli_num_rows($sql_result);

	for ($i=0; $i<$num_links; $i++){

		$row = mysqli_fetch_array($sql_result);
		echo "<p><a href='" . SITEURL . "$row[id]/'>$row[page_name]</a></p>";
}

}

// Query time
function GetMicroTime() { 
	list($usec, $sec) = explode(" ", microtime()); 
	return ((float)$usec + (float)$sec); 
}

function GenerateListOfSomeThing($for, $limit="", $orderby = "`priority` DESC", $SourceOrTarget="source_id", $stlimit="0",$cat="", $getID="") {

	global $Settings, $startlimit, $link;

	if (empty($_GET['test_id'])) $_GET['test_id'] = "";
	if (empty($_GET['rate'])) $_GET['rate'] = "";

	//	DATE ADDED:		13.02.2008
	//	DATE MODDED:	17.02.2008
	//	DATE MODDED:	29.03.2008 isSelect added & $key parametr

	// queries format: table, additional query, limitation, ,order by

	$getID = convertGetIdIntoPlainVar();

	$queries = array(

		"rateshome"		=> array("rates","",3),
		"rates"			=> array("rates","AND `fixedrate` = 0","","`name` ASC"),
		"rates_eur"		=> array("rates","AND `fixedrate` = 0","","`name` ASC"),
		"fixedrates_new"=> array("rates","AND `fixedrate` != 0 AND `issued_in` = 0","","`name` ASC"),
		"fixedrates"	=> array("rates","AND `fixedrate` != 0 AND `issued_in` = 0","","`name` ASC"),
		"fixedrates_euro"=> array("rates","AND `fixedrate` != 0 AND `issued_in` = 0","","`name` ASC"),
		"fixedrates_us"	=> array("rates","AND `fixedrate` != 0 AND `issued_in` = 1","","`name` ASC"),
		"areasselect"	=> array("rates","","","`priority` DESC, `name` ASC"),
		"services"		=> array("pages","AND `parent_id` = '9'",5),
		"languages"		=> array("languages","","","`name` ASC"),
		"lngselectlist"	=> array("languages","","","`priority` DESC, `name` ASC"),
		"quicklinks"	=> array("pages","AND `advertise` = '1' AND `id` != '$getID'", 5),
		"generateindex"	=> array("pages","AND `parent_id` = '$getID'"),
		"generateSvcs"	=> array("pages","AND `parent_id` = '$getID' AND `category` = '$cat'"),
		"paymenttypes"  => array("paymenttypes",""),
		"links"			=> array("links","",$Settings['itemsonlinkspage'],"",$startlimit),
		"expos"			=> array("expos"," AND `city` != 'Moscow' AND `country` = 'Russia' ",$Settings['itemsonlinkspage'],"",$startlimit),
		"relatedpages"	=> array("pages","AND `parent_id` = '9' AND `id` != '" . convertGetIdIntoPlainVar() . "'", "",'RAND()'),
		"tests"			=> array("tests","AND `testtranslation` !='' AND `test_id` = '$_GET[test_id]' AND `rate` = '$_GET[rate]'"),

		);

	// print_r($queries);

	// Если сказано лимитировать кол-во объектов - лимитируем
	if (!empty($queries[$for]['4'])) $stlimit = $startlimit;


	// Если сказано лимитировать кол-во объектов - лимитируем
	if (!empty($queries[$for]['2'])) $limit = "LIMIT $stlimit," . $queries[$for]['2'];


	// Если сказано лимитировать кол-во объектов - лимитируем
	if (!empty($queries[$for]['3'])) $orderby = $queries[$for]['3'];

	// For debug
	$sql = 
	"
	
	SELECT * FROM `".PREFIX.$queries[$for]['0']."`
	WHERE `visibility`='y' 
	". $queries[$for]['1'] ."
	ORDER BY $orderby
	$limit
	
	";

	// if ($_SERVER['HTTP_X_REAL_IP'] == "77.41.12.151") echo $sql;

	// echo "$for = $SourceOrTarget\n";

	// Получаем массив
	$sql_result = mysqli_query($link, $sql);

	// Получаем общее число объектов
	$num_links =  mysqli_num_rows($sql_result);

	// echo $num_links;
	// echo $sql;

	//if ($_SESSION && $_SESSION['loggedin'] == "yes")
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == "yes") {
		echo mysqli_error($link);
	}
	// else echo $_SERVER['REMOTE_ADDR'];

	for ($i=0; $i<$num_links; $i++){

		$row = mysqli_fetch_array($sql_result);

		$number = $i+1;

		// echo "testaaa";

		@$row['page_name'] = str_replace("/"," ", $row['page_name']);
		@$row['retailPrice'] = $row['fixedrate']*1.5;

		@$row['testtranslation'] = str_replace($AllowedChars,$ForbiddenChars,$row['testtranslation']);

		@$documentName = rtrim($row['name'],"s");
		@$documentNameURL = str_replace(" ","-",$documentName);

		if (@$row['visibility'] == 'd') $row['url'] = "#";

		$itemtemplate = array(
	
		// Шаблон для вывода таблицы с тарифами
		"rates" =>
			"
			<tr>
				<td class='c1'>$row[name]</td>
				<td class='c3'>$".number_format(round($row['ppw'],2),2)."</td>
				<td class='c3'>$". $row['ppw']*500 ."</td>
				<td class='quoterequest'>request a <a href='".SITEURL."26/?area_id=$row[id]'>translation quote</a> now</td>
			</tr>",

		// Вывод тестовых переводов
		"tests" =>
			"
				<form method='post' action='' class='tests pure-form'><p style='font-size:80%;'><B>" . intval($i+1) . ". </B>
				<span class='stats'> ID $row[id] &middot;
				 
				 <!-- <a href='http://ip2location.com/$row[ip]' target='_blank'>$row[ip]</a> $row[ip] $row[name] - $row[email] -->
				<!-- $$row[ppw] - $row[timespent] m. &middot; <span title='$row[email]\n\n$row[comments]'>eml</span>-->
				
				" . cmpWithGoogle(htmlspecialchars($row['testtranslation'])) . "</span></p> 
					<div class='testarea'>". str_replace("\\","",nl2br(trim(htmlspecialchars($row['testtranslation'])))) . "</div>
					<div style='padding-top: 6px;'>" . showRates($row['id'], $row['rate'], $row['rater_comment']) . "</div>
				</form>
			", 

		// Шаблон для вывода таблицы с тарифами
		"rates_eur" =>
			"
			<tr>
				<td class='c1'>$row[name]</td>
				<td class='c3'>€".round(number_format(round($row['ppw'],2),2)*USDEURRATE,2)."</td>
				<td class='c3'>€". round($row['ppw'] * 500 * USDEURRATE,2) ."</td>
				<td class='quoterequest'>request a <a href='".SITEURL."26/?area_id=$row[id]'>translation quote</a> now</td>
			</tr>",

		// Шаблон вывода таблицы с фиксированными тарифами на определенные виды документов / выдача в Рос/Укр
		"fixedrates" =>
			"
			<tr>
				<td class='c1' style='width:180px !important;'>
				
				
				<a href='/50/?document_type=$row[id]&amp;document_name=$documentNameURL&amp;issued_in=$row[issued_in]'><img src='/i/certifiedtr/$row[name].gif' title='$documentName Certified Russian / Ukrainian translation services' alt='$documentName Certified Russian / Ukrainian translation services'></a>
				<br><br>
				<a href='/50/?document_type=$row[id]&amp;document_name=$documentNameURL&amp;issued_in=$row[issued_in]'><b>$documentName</b></a>
				<div class='retailPrice'>Market Value: <span>$$row[retailPrice]</span></div>
				<div class='redPrice'>Our Price: <B>$$row[fixedrate]</B>

				<br><a href='".SITEURL."26/?area_id=$row[id]&amp;source_id=2&amp;target_id=1'><img src='/i/order-now.png'
				alt='Order Russian/Ukrainian to English $documentName translation and certification'
				title='Order Russian/Ukrainian to English $documentName translation and certification'
				></a>
						
				</div>



				</td>

				<td class='size90 orderlink' valign='middle'>

				<div align='center'></div>

 				order <a href='".SITEURL."26/?area_id=$row[id]&amp;source_id=2&amp;target_id=1'>Russian $documentName translation and certification</a><br>
				order <a href='".SITEURL."26/?area_id=$row[id]&amp;source_id=3&amp;target_id=1'>Ukrainian $documentName translation and certification</a>
				</td>
			</tr>",


		// Шаблон вывода таблицы с фиксированными тарифами на определенные виды документов / выдача в Рос/Укр
		"fixedrates_euro" =>
			"
			<tr>
				<td class='c1' style='width:180px !important;'>
				
				
				<a href='/50/?document_type=$row[id]&amp;document_name=$documentNameURL&amp;issued_in=$row[issued_in]'><img src='/i/certifiedtr/$row[name].gif' title='$documentName Certified Russian / Ukrainian translation services' alt='$documentName Certified Russian / Ukrainian translation services'></a>
				<br><br>
				<a href='/50/?document_type=$row[id]&amp;document_name=$documentNameURL&amp;issued_in=$row[issued_in]'><b>$documentName</b></a>
				<div class='retailPrice'>Market Value: <span>€" . ceil($row['retailPrice'])  . "</span></div>
				<div class='redPrice'>Our Price: <B>€" . ceil($row['fixedrate']*USDEURRATE). "</B>

				<br><a href='".SITEURL."26/?area_id=$row[id]&amp;source_id=2&amp;target_id=1'><img src='/i/order-now.png'
				alt='Order Russian/Ukrainian to English $documentName translation and certification'
				title='Order Russian/Ukrainian to English $documentName translation and certification'
				></a>
						
				</div>



				</td>

				<td class='size90 orderlink' valign='middle'>

				<div align='center'></div>

 				order <a href='".SITEURL."26/?area_id=$row[id]&amp;source_id=2&amp;target_id=1'>Russian $documentName translation and certification</a><br>
				order <a href='".SITEURL."26/?area_id=$row[id]&amp;source_id=3&amp;target_id=1'>Ukrainian $documentName translation and certification</a>
				</td>
			</tr>",

		// Шаблон вывода таблицы с фиксированными тарифами на определенные виды документов / выданы в США
		"fixedrates_us.old" =>
			"
			<tr>
				<td class='c1' style='width:180px;'>
				
				<a href='/50/?document_type=$row[id]&amp;document_name=$documentNameURL&amp;issued_in=$row[issued_in]'><img src='/i/certifiedtr/$row[name].gif' title='$documentName Certified English to Russian / Ukrainian translation services' alt='$documentName Certified English to Russian / Ukrainian translation services'></a>
				<br><br>
				<a href='/50/?document_type=$row[id]&amp;document_name=$documentNameURL&amp;issued_in=$row[issued_in]'><b>$documentName</b></a><div class='redPrice'> Price: <B>$$row[fixedrate]</B>
				
				<br><a href='".SITEURL."26/?area_id=$row[id]&amp;source_id=2&amp;target_id=1'><img src='/i/order-now.png'
				alt='Order English to Russian/Ukrainian $documentName translation and certification'
				title='Order English to Russian/Ukrainian $documentName translation and certification'
				></a>				
				
				
				</div></td>
				
				<td class='size90 orderlink'>
				order <a href='".SITEURL."26/?area_id=$row[id]&amp;source_id=2&amp;target_id=1&amp;issued_in=$row[issued_in]'>English to Russian $documentName translation and certification</a><br>
				order <a href='".SITEURL."26/?area_id=$row[id]&amp;source_id=3&amp;target_id=1&amp;issued_in=$row[issued_in]'>English to Ukrainian $documentName translation and certification</a>
				</td>
			</tr>",

		// Шаблон для вывода таблицы со списком языков
		"languages"		=> "<li>$row[name]</li>",

		// Шаблон для вывода таблицы со списком языков
		"lngselectlist" => "<option value='$row[id]_$row[name]'". IsSelected($SourceOrTarget, $row['id']) .">$row[name]</option>",

		// Шаблон для вывода таблицы со списком языков
		"areasselect"	=> "<option value='$row[id]_$row[ppw]_$row[name]'". IsSelected("area_id", $row['id']) .">$row[name]</option>",

		// Шаблон для вывода таблицы со списком языков
		// "quicklinks"	=> "<li><a href='".SITEURL."$row[id]/'>$row[page_name]</a></li>",
		"quicklinks"	=> "<li><a href='".SITEURL."$row[id]/'><img src='/i/quicklinks/$row[id].png' 
		alt='$row[page_name]'
		title='$row[page_name]'
		width='150' 
		height='80' 
		class='png'
		></a></li>",

		// Шаблон для вывода таблицы со списком языков
		"generateindex"	=> 
			"
		
			<TABLE>
				<TR>
					<TD width='110' height='100'>
					
					<a href='".SITEURL."$row[id]/'><img src='/i/pagethumbs/$row[page_name].gif' alt='$row[page_name]' title='$row[page_name]' width='92' height='82'></a>
					
					</TD>
					<TD>
					
					<div>
						<a href='".SITEURL."$row[id]/'><B>$row[page_name]</B></a>
						<p>". limitVisiblePart($row['content'],'255'). "</p>
					</div>		
					
					</TD>
				</TR>
			</TABLE>		
		
		
		",

		// Шаблон для вывода таблицы со списком языков
		"generateSvcs"	=> 
			"
		
			<TABLE>
				<TR>
					<TD width='110' height='100'>
					
					<a href='".SITEURL."$row[id]/'><img src='/i/pagethumbs/$row[page_name].gif' alt='$row[page_name]' title='$row[page_name]'></a>
					
					</TD>
					<TD>
					
					<div>
						<a href='".SITEURL."$row[id]/'><B>$row[page_name]</B></a>
						<p>". limitVisiblePart($row['content'],'255'). "</p>
					</div>		
					
					</TD>
				</TR>
			</TABLE>		
		
		
		",

		// Шаблон для вывода таблицы с тарифами
		"rateshome" =>
			"
			<tr>
				<td class='c1'>$row[name]</td>
				<td class='c2'>$".number_format(round($row['ppw'],2), 2)."</td>
				<td>$". $row['ppw']*500 ."</td>
			</tr>",
		
		// Шаблон для вывода списка услуг
		"services" => "<li><a href='".SITEURL."$row[id]/'>$row[page_name]</a></li>\n",

		// Шаблон для вывода списка других услуг, если открыта какая-то конкретная услуга
		"relatedpages" => "<li><a href='".SITEURL."$row[id]/'>$row[page_name]</a></li>\n",

		// Шаблон для вывода списка типов платежей
		"paymenttypes" => "<option value='$row[id]_$row[name]'>$row[name]</option>",

		// Шаблон для вывода списка ссылок
		"links" => "
			<div>
				" . ($stlimit+$number) . ". <a href='$row[url]' target='_blank'>$row[title]</a> - " . substr($row['description'],0,99). "...<br><span>edited: $row[timestamp]</span>
				
			</div>",

		// Шаблон для вывода списка выставок
		"expos" => "
			<div class='div'>
				<div class='date'>". str_replace(" - ", "<br>", $row['dates']) . "</div>
				<div class='logo' style='float:left; padding-right: 15px;'><img src='/i/logos/$row[logo].png' title='$row[name]'></div>
				<div>
					<!-- <a href='/resources/expos-in-russia/" . nameConvert($row['name']). "-$row[id].html'
					target='_blank'>--><B>$row[name]</B><!-- </a> -->
					- $row[country], $row[city]
				</div>
				<div>" . substr($row['description'],0,80) . "...</div>
				<div class='ourservices'>Services: interpreting, translation</div>
			</div>".showInterpreters($i)
		
		);

		eval("echo \"$itemtemplate[$for]\";");
		// echo $items;

}

}

function showInterpreters($i) {

	if ($i%SHOWINTERPRETERSEVERY == 0 && $i !==0)	return file_get_contents("lib/interpreters.cache.html");

}

function nameConvert($name) {

	$search		= array(" ", ".", "--");
	$replace	= array("-", "-", "-");

	$name = str_replace($search,$replace,str_replace("--","-",$name)); 

return $name;

}

function insertImg($imgFile) {

echo "";

}


// Генерация тега select
// 26.02.2008
function GenerateSelectTag($name, $WhatWhatTableToSelect="languages", $orderby = "`name` ASC")	{

	global $link;

	$res = mysqli_query ($link, "SELECT * FROM `" . PREFIX . "$WhatWhatTableToSelect` $orderby");

	$select = "<select name='$name'>";
	
	while($col = mysqli_fetch_array($res))	{

		$select .= "\t\t<option value='".$col['id']."'";
		$select .= selectv3($name, $col['id']);
		$select .= ">$col[name]</option>\n";
	}

	echo $select."</select>";

}


/*
// Site specific func
*/

function CountWordsCalculateTotalPrice() {

	// Инициализируем переменную
	$result = array();

	// Считаем кол-во слов
	$result['words']	= str_word_count(trimmer($_POST['content']));

	// Разбираем полученное значение цены и ид области перевода
	$ppw				= explode("_", $_POST['area_id']);

	// Заносим в массив значние цены за слово
	$result['ppw']		= $ppw['1'];

	// Подсчитываем общую стоимость перевода
	$result['total']	= round(($result['words']*$result['ppw']),2);

	// Возвращаем результат
	return $result;

}

function AreThereAnyPagesInside() {


	// echo GetTotalData("pages",$where=" WHERE visibility = 'y' AND `parent_id` = $_GET[id]");
	if (GetTotalData("pages",$where="
		WHERE `visibility` = 'y' AND `parent_id` = $_GET[id]") > "0") return true;
	else return false;

}

function GenerateServicesIndex ($cat="0") {
	GenerateListOfSomeThing("generateSvcs", $limit="", $orderby = "`priority` DESC", $SourceOrTarget="source_id", $stlimit="0",$cat);
}

function GenerateRateList($rate="", $list="") {
	
	for ($i = 1; $i < TESTRATES; $i++) {
			$list .= "<option value='$i'" . selectv2($rate, $i) . ">$i</option>";
	}

	return $list;

}

function showRates($id, $rate, $comment) {

	if ($rate == "0") {

		// echo $rate;

	return "<input type='text' name='rater_comment' value='$comment' style='width: 377px;'>
				<select name='rate'>" . generateRateList($rate) . "</select>
				<input type='hidden' name='id' value='$id'>
				<input type='hidden' value='ratetest' name='dosometh'>
				<input type='submit' value='assess' class='pure-button pure-button-primary' style='width: auto;'>";

	} else return "Rate: <B>$rate</B> &mdash; <B>$comment</B>";

}


function generateVoiceOverList($array,$showThePrice="1",$showDownloadLlink="0") {

	$columnPrice = array(
		"",		
		"
					<td class='thead c3'>Price per 30 s</td>
					<td class='thead c3'>Order voice over</td>	
		",
		"
					<td class='thead c3'>Download</td>
		"
	);

	echo "

				<table class='price internal voiceover'>
					<tr>
						<td class='thead c1'>Name of the artist</td>
						<td class='thead c1'>Listen demo</td>
						" . $columnPrice[$showThePrice] . "
						" . $columnPrice[$showDownloadLlink] . "
					</tr>

	";

	$i = 0;
	foreach($array as $key=>$properties)	{

	$i++;
	
	$artproperties = explode("|", $properties);
	$artproperties[2] = $artproperties[2]*1.6;
	$nameClean = strip_tags($artproperties[0]);

	$columnPriceValue = array(

		"",	
		"
				<td class='c3'>$$artproperties[2] USD</td>
				<td class='c3'><a href='/26/?service=voiceOver&amp;russian-voice-over-id=$i&amp;name=$nameClean'><img src='/i/order-now.png' alt='Order Russian/Ukrainian voice over. Artist $nameClean. Character: $artproperties[1]' title='Order Russian/Ukrainian voice over. Artist $nameClean. Character: $artproperties[1]'></a></td>
		",

		"
				<td class='c3'><a href='/files/voices/$artproperties[3].mp3'>Download MP3</a></td>
		",

	);	

	echo "
			<tr>
				<td class='c1'>$artproperties[0] <div style='font-size: 90%; margin-top: -2px; color: grey;'>$artproperties[1]</div></td>
				<td class='c1'><object type='application/x-shockwave-flash' data='/i/dewplayer-mini.swf?mp3=/files/voices/$artproperties[3].mp3&amp;showtime=1' width='200' height='20'><param name='wmode' value='transparent'><param name='movie' value='/i/dewplayer-mini.swf?mp3=/files/voices/$artproperties[3].mp3&amp;showtime=1'></object></td>
				" . $columnPriceValue[$showThePrice] . "
				" . $columnPriceValue[$showDownloadLlink] . "
			</tr>
	";

	}

echo "\t\t\t\t\t</table>";

}

// Генерация списка переводчиков по городам
// 14.01.2012
function GenerateListOfInterpretersByCity($list, $cityNames) {

	foreach (explode("\n",trim($cityNames)) as $key=>$val) {

	$properties = explode(";",trim($val));

	@$output['anchors']	.= "<li><a href='#russian-Interpreter-$properties[1]'><B>Interpreter in $properties[1]</B></a></li>";
	@$output['html']		.= "<h2><a name='russian-Interpreter-$properties[1]'></a>Interpreter in $properties[1]</h2>".GenerateInterpreters($list,$properties[0],1);

	}

	return $output;

}

// 19.02.2012 1:33
function GenerateInterpreters($list, $location, $echoOrReturn=0, $i=0) {

$td = "<td class='firstColumn'><div class='languages'>Fluent in </div><div class='education'> Education </div><div class='education'> Experience </div><div class='retailPrice'>Market value</div><div class='redPrice'>Our rate per hour</div></td>";

$html = "<table class='expointerpreters'><tr>$td";

	foreach($list[$location] as $key=>$properties)	{

		$artproperties = explode(" | ", $properties);

		if ($i%THUMBSDIR == 0 && $i !==0 && $i > 0) $html .= "</tr><tr>$td";

		$html .= "

			<!-- Girl -->

				<td>

				<a href='/26/?service=expoInterpreter&interpreterName=$key&expoLocation=$location'><img src='/i/interpreters/$key.jpg' title='" . ucfirst($key) . ": $artproperties[0] Interpreter in Moscow, Russia'></a>

				<div class='name'><b>" . ucfirst($key) . "</b></div>

				<div class='languages'><b>$artproperties[0], Russian</b></div>

				<div class='education'>$artproperties[3]</div>

				<div class='education'>$artproperties[5]</div>

				<div>

					<div class='retailPrice'><span>$" . round($artproperties[4]*MARKETPRICEMULTIPLIER/8.5) . "</span></div>

					<div class='redPrice'><b>$" . round($artproperties[4]*OURPRICEMULTIPLIER/8.5) . "</b>

				</div>

				

				<a href='/26/?service=expoInterpreter&interpreterName=$key&expoLocation=$location'><img title='" . ucfirst($key) . ": $artproperties[1] Interpreter in Moscow, Russia' alt='" . ucfirst($key) . ": $artproperties[0] Interpreter in Moscow, Russia' src='/i/continue.gif'></a>

				

				</div>

				</td>

			<!-- /Girl -->

	

		";

		$i++;

	}

$html .= "</tr></table>";

switch($echoOrReturn) {

	default: echo $html; break;
	case "1": return $html; break;
}

}

function cmpWithGoogle($test) {

	$testhash = md5(trim($test));

	$actualTestLength = mb_strlen(trim($test));

	similar_text(MACHINETRANS,$test,$similar);

	$var =  "

	<!-- &middot; --> G: " . GSTRLEN . " chars 
	&middot; T: $actualTestLength chars 
	&middot; W: " . str_word_count($test) . " 
	&middot; S: " . round($similar) . "%<br>
	
	";

	if ($testhash === HASHTEST) $var .= " counterfreit";

	return $var;

}