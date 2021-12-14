<?php
	header("Content-Type: text/html; charset=utf-8");

 	error_reporting(0);

	require_once("scripts/functions.php");
	require_once("scripts/smtp-func.php");

	$mail_login    = "biblio@chelreglib.ru";
	$mail_password = "Xtkz,bycr2020";
	$mailbox_from  = "25";
	$mailbox_to    = "25ok";
	$mail_from 	   = "mag@arbicon.ru";
	$mail_imap 	   = "{imap.yandex.ru:993/imap/ssl}" . $mailbox_from;
	$filename      = "./tmp/1.iso";
/*	
	if (file_exists('C:\opac-global\web\htdocs\opacg\mail_mars\tmp\1.iso')) {
		unlink(__DIR__ . $filename);
	}
	
	$connection = imap_open($mail_imap, $mail_login, $mail_password);
	
	log_add("#");
	log_add("Запуск программы");
	
	if(!$connection){
		log_add("Ошибка соединения с почтой - " . $mail_login);
		exit;
	}
	
	$emails = imap_search($connection,'FROM "'. $mail_from . '"');
	if(count($emails)==1){
		log_add("Писем нет (папка ". $mailbox_from . ")");
		echo '<p><br><br><br><br>Писем нет<p>';
		echo '<p><a href="index.php">Вернуться назад</a></p>';
		exit;
	}
	
	$mails_data = array();
	
	log_add("Сохраняются вложенные файлы:");
	
	foreach($emails as $i) {

		// Тело письма
		$msg_structure = imap_fetchstructure($connection, $i);
	
		// Вложенные файлы
		if(isset($msg_structure->parts)){
					
			for($j = 1, $f = 2; $j < count($msg_structure->parts); $j++, $f++){

					$mails_data[$i]["attachs"][$j]["name"] = $msg_structure->parts[$j]->parameters[0]->value;
					$mails_data[$i]["attachs"][$j]["file"] = structure_encoding(
						$msg_structure->parts[$j]->encoding,
						imap_fetchbody($connection, $i, $f)
					);
					$file_content = $mails_data[$i]["attachs"][$j]["file"];
					file_put_contents(__DIR__ . $filename, $file_content, FILE_APPEND | LOCK_EX);					
					log_add($i . ". " . $mails_data[$i]["attachs"][$j]["name"]);
					
			}
		}
	}
	$imapresult = imap_mail_move($connection, '1:' . count($emails), $mailbox_to);
	imap_expunge('1:' . count($emails));
	if ($imapresult)
		log_add("Письма перемещены из папки ". $mailbox_from . " в папку " . $mailbox_to);
	else log_add("Письма НЕ ПЕРЕМЕЩЕНЫ");
	
	imap_close($connection);	
	
	if (system('c:\opac-global\tools\zagruzka.exe -e cp1251 25 C:\opac-global\web\htdocs\opacg\mail_mars\tmp\1.iso'))
		log_add("Загрузка в БД запущена. Подробная информация в логах (Вкладка 'Загрузка БЗ в OPAC')");
	else log_add("Загрузка в БД НЕ ЗАПУСТИЛАСЬ");
	
	echo '<p><br><br><br><br>Программа запущена<p>';
	echo '<p><a href="index.php">Вернуться назад</a></p>';
	
	$message = "<p>Была запущена программа загрузки МАРС в OPAC. <a href='http://192.168.10.121/opacg/mail_mars/index.php'>Посмотреть результаты</a></p>";
	$sended = smtpmail("bogdanovichi06@rambler.ru", "MARS", $message, "OA"); 
	$sended = smtpmail("biblio@chelreglib.ru", "MARS", $message, "OA");*/
$sended = smtpmail("bogdanovichi06@rambler.ru", "MARS", "23", "OA"); 
	//$sended = smtpmail("bogdanovichi06@rambler.ru", "MARS", "123"); 
?>
