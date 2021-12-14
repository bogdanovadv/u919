<?php
	function check_utf8($charset){

		if(strtolower($charset) != "utf-8"){

			return false;
		}

		return true;
	}

	function convert_to_utf8($in_charset, $str){

		return iconv(strtolower($in_charset), "utf-8", $str);
	}

	function get_imap_title($str){

		$mime = imap_mime_header_decode($str);

		$title = "";

		foreach($mime as $key => $m){

			if(!check_utf8($m->charset)){

				$title .= convert_to_utf8($m->charset, $m->text);
			}else{

				$title .= $m->text;
			}
		}

		return $title;
	}

	function recursive_search($structure){

		$encoding = "";

		if($structure->subtype == "HTML" ||
		   $structure->type == 0){

			if($structure->parameters[0]->attribute == "charset"){

				$charset = $structure->parameters[0]->value;
			}

			return array(
				"encoding" => $structure->encoding,
				"charset"  => strtolower($charset),
				"subtype"  => $structure->subtype
			);
		}else{

			if(isset($structure->parts[0])){

				return recursive_search($structure->parts[0]);
			}else{

				if($structure->parameters[0]->attribute == "charset"){

					$charset = $structure->parameters[0]->value;
				}

				return array(
					"encoding" => $structure->encoding,
					"charset"  => strtolower($charset),
					"subtype"  => $structure->subtype
				);
			}
		}
	}

	function structure_encoding($encoding, $msg_body){

		switch((int) $encoding){

			case 4:
				$body = imap_qprint($msg_body);
				break;

			case 3:
				$body = imap_base64($msg_body);
				break;

			case 2:
				$body = imap_binary($msg_body);
				break;

			case 1:
				$body = imap_8bit($msg_body);
				break;

			case 0:
				$body = $msg_body;
				break;
			
			default:
				$body = "";
				break;
		}

		return $body;
	}
	
	function log_add($msg_body){
		
		$msg_body = date("d.m.Y H:m:s") . " " . $msg_body . "\r\n";
		
	//	echo($msg_body);
		
		file_put_contents(__DIR__ . "/../log/" . date("Ydm") . ".txt", $msg_body, FILE_APPEND | LOCK_EX);
		
	}
	
	// Функция, которая открывает файл, читает его и возвращает 
	function loadDataFromFile_log(){

		$files = listdir_by_date(__DIR__ . "/../log/");

		$file = __DIR__ . "/../log/" . reset($files);
		
		if (!file_exists($file))
			throw new Exception("Ошибка: файл $file не существует!");
		
		if (!filesize($file))
			throw new Exception("Файл $file пустой!");
		
		$f = fopen($file, "r");
		
		$content = fread($f, filesize ($file));
		
		// Заменяем переносы строки в файле на тег BR. Заменить можно что угодно
		$content = str_replace("\r\n","<br>", $content);
		
		fclose ($f);
		
		return $content;
		
	}
	
	function listdir_by_date($path){
		$dir = opendir($path);
		$list = array();
		while($file = readdir($dir)){
			if ($file != '.' and $file != '..'){
				$ctime = filectime($path . $file) . ',' . $file;
				$list[$ctime] = $file;
			}
		}
		closedir($dir);
		krsort($list);
		return $list;
    }
	
		// Функция, которая открывает файл, читает его и возвращает 
	function loadDataFromFile_tmp(){
		
		$file = __DIR__ . "/../tmp/1.iso.log";
		
		if (!file_exists($file))
			throw new Exception("Ошибка: файл $file не существует!");
		
		if (!filesize($file))
			throw new Exception("Файл $file пустой!");
		
		$f = fopen($file, "r");
		
		$content = fread($f, filesize ($file));
		
		// Заменяем переносы строки в файле на тег BR. Заменить можно что угодно
		$content = str_replace("\r\n","<br>", $content);
		
		fclose ($f);
		
		return $content;
		
	}
?>