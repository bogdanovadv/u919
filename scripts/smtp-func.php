<?php
	function get_data($smtp_conn) {
		$data="";
		while($str = fgets($smtp_conn,515)) {
			$data .= $str;
			if(substr($str,3,1) == " ") { break; }
		}
		return $data;
	}

		//Функция отправки письма по smtp
	function smtpmail($mail_to, $subject, $message, $name_to) {
		
		$name_from = "Загрузка МАРС в OPAC"; // имя отправителя
		$mail_from = "biblio@chelreglib.ru"; // email отправителя
		//$pas="Xtkz,bycr2020"; // пароль почты отправителя
		 
		$header="Date: ".date("D, j M Y G:i:s")." +0700\r\n";
		$header.="From: =?utf-8?Q?".str_replace("+","_",str_replace("%","=",urlencode($name_from)))."?= <".$mail_from.">\r\n";
		$header.="X-Mailer: The Bat! (v3.99.3) Professional\r\n";
		$header.="Reply-To: =?utf-8?Q?".str_replace("+","_",str_replace("%","=",urlencode($name_from)))."?= <".$mail_from.">\r\n";
		$header.="X-Priority: 3 (Normal)\r\n";
		$header.="Message-ID: <172562218.".date("YmjHis")."@chelreglib.ru>\r\n";
		$header.="To: =?utf-8?Q?".str_replace("+","_",str_replace("%","=",urlencode($name_to)))."?= <".$mail_to.">\r\n";
		$header.="Subject: =?utf-8?Q?".str_replace("+","_",str_replace("%","=",urlencode($subject)))."?=\r\n";
		$header.="MIME-Version: 1.0\r\n";
		$header.="Content-Type: text/html; charset=utf-8\r\n";
		$header.="Content-Transfer-Encoding: 8bit\r\n";
		 
		$smtp_conn = fsockopen("ssl://smtp.yandex.ru", 465,$errno, $errstr, 10);
		 if(!$smtp_conn) {print "соединение с серверов не прошло"; fclose($smtp_conn); exit;}
		$data = get_data($smtp_conn);
		fputs($smtp_conn,"EHLO yandex.ru\r\n");
		$code = substr(get_data($smtp_conn),0,3);
		 if($code != 250) {print "ошибка приветсвия EHLO"; fclose($smtp_conn); exit;}
		fputs($smtp_conn,"AUTH LOGIN\r\n");
		$code = substr(get_data($smtp_conn),0,3);
		 if($code != 334) {print "сервер не разрешил начать авторизацию"; fclose($smtp_conn); exit;}
		 
		fputs($smtp_conn,base64_encode("biblio@chelreglib.ru")."\r\n");
		$code = substr(get_data($smtp_conn),0,3);
		 if($code != 334) {print "ошибка доступа к такому юзеру"; fclose($smtp_conn); exit;}
		 
		 
		fputs($smtp_conn,base64_encode("Xtkz,bycr2020")."\r\n"); // пароль почты отправителя
		$code = substr(get_data($smtp_conn),0,3);
		 if($code != 235) {print "не правильный пароль"; fclose($smtp_conn); exit;}
		 
		fputs($smtp_conn,"MAIL FROM:".$mail_from."\r\n");
		$code = substr(get_data($smtp_conn),0,3);
		 if($code != 250) {print "сервер отказал в команде MAIL FROM"; fclose($smtp_conn); exit;}
		 
		fputs($smtp_conn,"RCPT TO:".$mail_to."\r\n");
		$code = substr(get_data($smtp_conn),0,3);
		 if($code != 250 AND $code != 251) {print "Сервер не принял команду RCPT TO"; fclose($smtp_conn); exit;}
		 
		fputs($smtp_conn,"DATA\r\n");
		$code = substr(get_data($smtp_conn),0,3);
		 if($code != 354) {print "сервер не принял DATA"; fclose($smtp_conn); exit;}

		fputs($smtp_conn,$header."\r\n".$message."\r\n.\r\n");
		$code = substr(get_data($smtp_conn),0,3);
		if($code != 250) {print "ошибка отправки письма"; fclose($smtp_conn); exit;}
		 
		fputs($smtp_conn,"QUIT\r\n");
		fclose($smtp_conn);
		return true;
}

?>