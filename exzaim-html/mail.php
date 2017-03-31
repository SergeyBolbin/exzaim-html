<?php
	
	//for protecting strings
    function protectString($str){
        $str = htmlspecialchars($str);
        $str = str_replace("'","`",$str);
        $str = strip_tags($str);
        return $str;
    }
	
	//format mail text 
	function prepareMailText($text, $pSite, $pName, $pPhone, $pDate, $pText) {
		$str = str_replace("<SITE>", $pSite, $text);
		$str = str_replace("<NAME>", $pName, $str);
		$str = str_replace("<PHONE>", $pPhone, $str);
		$str = str_replace("<DATE>", $pDate, $str);
		$str = str_replace("<TEXT>", $pText, $str);
		$str = str_replace("\n\r", "<br>", $str);
		$str = str_replace("\r\n", "<br>", $str);
		$str = str_replace("\n", "<br>", $str);
		$str = str_replace("\r", "<br>", $str);
		
		return $str;
	}
	
	function currentDateMSK() {
		$tz = 'Europe/Moscow';
		$timestamp = time();
		$dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
		$dt->setTimestamp($timestamp); //adjust the object to correct timestamp
		return $dt->format('d.m.Y H:i:s');
	}
	
	function sendShortRequest($settings) {
		$name  = protectString($_POST["name"]);
		$phone = protectString($_POST["phone"]);
		
		$date = currentDateMSK();
		
		$site  	  	 = $settings["sitename"]; 
		$mailTo   	 = $settings["mail_to"];
		$mailFrom  	 = $settings["mail_from"];
		$mailSubject = prepareMailText($settings["mail_subject"], $site, $name, $phone, $date, "");
		$mailText 	 = prepareMailText($settings["mail_text"], $site, $name, $phone, $date, "");
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

		// Additional Headers
		$headers .= "To: $mailTo" . "\r\n";
		$headers .= "From: Express-Zaim <$mailFrom>" . "\r\n";
		
		$result = mail($mailTo, $mailSubject, $mailText, $headers);
		
		$redirectUrl = $result ?  "$site/success.html" : "$site/error.html";
		header("Location: $redirectUrl");
	}
	
	function sendFullRequest($settings) {
		$name  = protectString($_POST["name1"])." ".protectString($_POST["name2"])." ".protectString($_POST["name3"]);
		$phone = protectString($_POST["phone"]);
		$text  = protectString($_POST["text"]);
		if(!empty($text)) {
			$text = "Комментарий пользователя: <br><br>".$text;	
		}
		
		$date = currentDateMSK();
		
		$site  	  	 = $settings["sitename"]; 
		$mailTo   	 = $settings["mail_to"];
		$mailFrom  	 = $settings["mail_from"];
		$mailSubject = prepareMailText($settings["mail_subject"], $site, $name, $phone, $date, $text);
		$mailText 	 = prepareMailText($settings["mail_text"], $site, $name, $phone, $date, $text);
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

		// Additional Headers
		$headers .= "To: $mailTo" . "\r\n";
		$headers .= "From: Express-Zaim <$mailFrom>" . "\r\n";
		
		$result = mail($mailTo, $mailSubject, $mailText, $headers);
		
		$redirectUrl = $result ?  "$site/success.html" : "$site/error.html";
		header("Location: $redirectUrl");
	}
	
	$settings = parse_ini_file("settings/mail_settings.ini");
	
	if(isset($_POST["submit"])) {
		sendShortRequest($settings);	
	} else if(isset($_POST["submit_full_request"])) {
		sendFullRequest($settings);
	} else {
		die("Access denied!");
	}
	
	
	
	
	
?>