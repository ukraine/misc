<?php

$stopFiles = "png, ttf, css, js, woff, eot, jpg, map, json, lock, svg, woff2, ico";

$stopFiles = explode(", ", $stopFiles);

function dirToArray($dir, $stopFiles, $tab="\t") {
  
   $result = array();

   $cdir = scandir($dir);
   foreach ($cdir as $key => $value)
   {
      if (!in_array($value,array(".","..")))
      {
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
         {
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value, $stopFiles, "\t\t");
         }
         else
         {
			
			// echo pathinfo($value,PATHINFO_EXTENSION);

            if (!in_array(pathinfo($value,PATHINFO_EXTENSION), $stopFiles)) { $numbers = intval(getWordsNum($dir."/".$value)); $result[] = $value . "\t" . $numbers; $total = $numbers + intval($total);}
         }
      }

   }

	echo $total."\n\n\n";

   return $result;
}


function getWordsNum($file) {
	
// 	echo str_replace("/", "\"", getcwd()."/$file\n");

//	$text = strip_tags(file_get_contents(getcwd()."/$file"));

//	$text = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $text);

	$text = strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '', file_get_contents(getcwd()."/$file")));

	$number = count(str_word_count($text, 2,"АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя"));


	return intval($number);

}


function showText($file) {

// $text = strip_tags();

// $text = strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '', file_get_contents(getcwd()."/$file")));

return $text;

}

print_r(dirToArray("fc",$stopFiles));

// echo showText("fc/footer.php");