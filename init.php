<?php defined('SYNCSYSTEM') || die('No direct script access.');
if(!@$_REQUEST['SessionSite']) {
	$SessionSite = substr(@$_SERVER['HTTP_REFERER'], 7, (strpos(@$_SERVER['HTTP_REFERER'], 'sync/') - 8));
} else {
	$SessionSite = $_REQUEST['SessionSite'];
}
define('LOCAL_DIR', "D:/Site/$SessionSite/");
is_dir(LOCAL_DIR) || die('NO Local system tomanage:'.$SessionSite);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
include dirname(dirname(__FILE__)).'/SyncClass/init.php';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($operation != '') {
	in_array($operation, array('MD5 Compare', 'upload', 'dnload')) || die('Unkonwn operation');
	response($operation, 'outside');
}

if($do != '') {
	in_array($do, array('pulltolocal', 'continue upload on local')) || die('Unkonwn do');
	response($do, 'inside');
}

exit;
function response($flag, $type) {
	GLOBAL $SessionSite, $continue;
	$includefiles           = isset($_REQUEST['includefiles']) ? $_REQUEST['includefiles'] : array();
	$list                   = isset($_REQUEST['list']) ? str_replace('"', '', str_replace(LOCAL_DIR, '', str_replace('\\', '/', trim($_REQUEST['list'])))) : '';
	$listArray              = explode(' ', $list);
	$SIZE                   = isset($_REQUEST['UPLOAD_LIMIT_SIZE']) ? $_REQUEST['UPLOAD_LIMIT_SIZE'] : 0;
	$targetList             = array_merge($listArray, $includefiles);
	$func                   = str_replace(' ', '_', $flag);
	$hiddenform             = htmlentities(call_user_func_array(array('Sync', $func), array($targetList)), ENT_QUOTES);
	$includefilesHiddenform = '';
	while($includefile = $includefiles) {
		$includefilesHiddenform .= "<input type='hidden' name='includefiles[]' value='$includefile' />";
	}
	$do = '';
	if($type == 'outside') {
		$do = "after $flag on local";
	} else if($type == 'inside') {
		$do = strtr($flag, array('continue' => 'after'));
	}
	echo <<<FOM
		\n
<form action="http://$SessionSite/sync/" method="post" enctype="multipart/form-data">
<input type="hidden" name="do" value="$do" />
<input type="hidden" name="list" value="$list" />
<input type="hidden" name="UPLOAD_LIMIT_SIZE" value="$SIZE" />
<input type="hidden" name="continue" value="$continue" />
$includefilesHiddenform
<input type="hidden" name="displayInfo" value="$hiddenform" />
</form>
FOM;
echo Page_Template::autoSubmit();
}


/*
if(@!$_REQUEST['do']) {
} else {
	echo $head;
	$hiddenform = '';
	if($_REQUEST['do'] == 'MD5 Compare') {
	} elseif($_REQUEST['do'] == 'upload') {
	} elseif($_REQUEST['do'] == 'sync') {
	}
	exit;
}
if($_REQUEST['operation'] == 'postpackagetoremote') {
	//echo realpath('package.zip');
	$package = realpath('package.zip');
	$res     = curlrequest("http://$SessionSite/sync.php?operation=push", array('file' => "@$package"));
	echo $res;
	exit;
}
if($_REQUEST['operation'] == 'md5checkedsync') {
	echo $foot;
} elseif($_REQUEST['operation'] == 'aftermd5check') {
	echo $head;
	$upload = $dnload = $delete = array();
	foreach($_POST['file'] as $file => $option) {
		switch($option) {
			case 'ignore':
				$ignorelist = file_get_contents($localdir.'./sync/ignorelist.txt');
				if(!in_array($file, explode("\n", $ignorelist))) {
					$fp = fopen($localdir.'./sync/ignorelist.txt', 'a');
					fwrite($fp, "\n$file");
					fclose($fp);
				}
				break;
			case 'upload':
				$upload[] = $file;
				break;
			case 'dnload':
				$dnload[] = $file;
				//echo '<input type="hidden" name="dnload[]" value="' . $file . '" />';
				break;
			case 'delete':
				$delete[] = $file;
				//echo '<input type="hidden" name="delete[]" value="' . $file . '" />';
				break;
		}
	}
	echo '<br />upload:'.implode('<br />upload:', $upload);
	echo '<br />dnload:'.implode('<br />dnload:', $dnload);
	echo '<br />delete:'.implode('<br />delete:', $delete);
	if(count($upload)) {
		packfiles($upload);
		$package = realpath('package.zip');
		$data    = array('delete' => serialize($delete), 'dnload' => serialize($dnload), 'file' => "@$package");
	} else {
		$data = array('delete' => serialize($delete), 'dnload' => serialize($dnload));
	}
	$res = curlrequest("http://$SessionSite/sync.php?operation=md5checkedsync", $data);
	echo $res;
	exit;
	echo <<<HTML
	<br/>
<input type="submit" name="submit" value="submit" />
</form>
HTML;
} elseif($_REQUEST['operation'] == 'messagetopick') {
} elseif($_REQUEST['operation'] == 'pulltolocal') {
	echo $head;
	pulltolocal();
	echo $foot;
	exit;
} elseif($_REQUEST['operation'] == 'catchthepull') {

} elseif($_REQUEST['operation'] == 'md5ResultToLocal') {
	echo '<form action="sync.php?operation=aftermd5check" method="post">';
	echo "<input type='hidden' name='SessionSite' value='$SessionSite' />";
	$ignorelist = file_get_contents($localdir.'./sync/ignorelist.txt');
	$ignorelist = explode("\n", trim($ignorelist));
	foreach($_POST['file'] as $file => $md5) {
		//		$item = explode(' *', $item);
		if(in_array($file, $ignorelist)) continue;
		if(file_exists($localdir.$file)) {
			if(md5_file($localdir.$file) != $md5) {
				echo <<<HTML
<input type="radio" name="file[$file]" value="ignore" />忽略
<input type="radio" name="file[$file]" value="upload" />上传
<input type="radio" name="file[$file]" value="dnload" />下载
<input type="radio" name="file[$file]" value="delete" />删除
HTML;

				echo '文件：'.($file).';<br />';
			}
		} else {
			echo <<<HTML
<input type="radio" name="file[$file]" value="ignore" />忽略
<input type="radio" name="file[$file]" value="upload" disabled />上传
<input type="radio" name="file[$file]" value="dnload" />下载
<input type="radio" name="file[$file]" value="delete" />删除远程
HTML;
			echo '文件：'.($file).'不存在！！<br />';
		}
	}
	echo '<input type="submit" value="submit" name="submit" />';
	echo '</form>';
} else {
	if($_REQUEST['submit'] == '') {
		echo $head;
		echo <<<HTM
		选择要压缩的文件或目录：<a href="http://$SessionSite/sync.php">浏览远程文件</a><br>
		<form name="myform" method="post" action="$_SERVER[PHP_SELF]">
HTM;
		$fdir = opendir($localdir);
		function checkfiletype($filename) {
			$ext = strrchr($filename, '.');
			$ext = substr($ext, 1);
			switch($ext) {
				case 'txt':
					$type = 'text';
					break;
				case 'htm':
					$type = 'html';
					break;
				default:
					$type = $ext;
			}
			return $type;
		}

		echo '<div class="exploreritem">';
		echo "<input name='includefiles[]' type='checkbox' value='' disabled /><br />";
		echo '<input type="submit" name="submit" class="submit floder-parent" value=".." />';
		echo '</div>';
		while($file = readdir($fdir)) {
			if(preg_match($ignores, $file)) continue;

			echo '<div class="exploreritem">';
			echo "<input name='includefiles[]' type='checkbox' value='$file' /><br />";
			if(is_dir($localdir.$file)) {
				echo '<input type="submit" name="submit" class="submit floder-page" value="'.$file.'" />';
			} else {
				echo '<input type="submit" name="submit" class="submit '.checkfiletype($file).'" value="'.$file.'" />';
			}
			echo '</div>';
		}
	} elseif($_REQUEST['submit'] == 'zip') {
		echo $head;
		if(!@$_REQUEST['includefiles']) {
			$_REQUEST['includefiles'] = array();
		}
		$targetList = array_merge(explode(' ', @$_REQUEST['list']), $_REQUEST['includefiles']);

		packfiles($targetList);

		$package = realpath('package.zip');
		$data    = array('file' => "@$package");
		$res     = curlrequest("http://$SessionSite/sync.php?operation=push", $data);
		echo $res;
		echo $foot;


	}
}


*/

//
//$srv_ip = '192.168.10.188'; //你的目标服务地址或频道.
//$srv_port = 80;
//$url = '/demo/test_query_string.php'; //接收你post的URL具体地址
//$fp = '';
//$resp_str = '';
//$errno = 0;
//$errstr = '';
//$timeout = 10;
//$post_str = "username=demo&str=aaaa"; //要提交的内容.
//
////echo $url_str;
//if ($srv_ip == '' || $dest_url == '') {
//	echo('ip or dest url empty<br>');
//}
////echo($srv_ip);
//$fp = fsockopen($srv_ip, $srv_port, $errno, $errstr, $timeout);
//if (!$fp) {
//	echo('fp fail');
//}
//$content_length = strlen($post_str);
//$post_header = "POST $url HTTP/1.1\r\n";
//$post_header .= "Content-Type: application/x-www-form-urlencoded\r\n";
//$post_header .= "User-Agent: MSIE\r\n";
//$post_header .= "Host: " . $srv_ip . "\r\n";
//$post_header .= "Content-Length: " . $content_length . "\r\n";
//$post_header .= "Connection: close\r\n\r\n";
//$post_header .= $post_str . "\r\n\r\n";
//fwrite($fp, $post_header);
//while (!feof($fp)) {
//	$resp_str .= fgets($fp, 512); //返回值放入$resp_str
//}
//fclose($fp);
//echo($resp_str); //处理返回值.
////unset ($resp_str);
/**/
//$url = 'http://localhost/test/curl.php';
//$data = "request from put method";
//$return = curlrequest($url, $data, 'put');
//var_dump($return);
//exit;
/*//接收POST參數的URL
	$url = 'http://www.google.com';

//POST參數,在這個陣列裡,索引是name,值是value,沒有限定組數
	$postdata = array(
		'post_name' => 'post_value', 'acc' => 'hsin', 'nick' => 'joe');

//函式回覆的值就是取得的內容
	$result = sendpost($url, $postdata);

	function sendpost($url, $data) {
//先解析url 取得的資訊可以看看http://www.php.net/parse_url
		$url = parse_url($url);
		$url_port = $url['port'] == '' ? (($url['scheme'] == 'https') ? 443 : 80) : $url['port'];
		if (!$url) return "couldn't parse url";

//對要傳送的POST參數作處理
		$encoded = "";
		while (list($k, $v) = each($data)) {
			$encoded .= ($encoded ? '&' : '');
			$encoded .= rawurlencode($k) . "=" . rawurlencode($v);
		}

//開啟一個socket
		$fp = fsockopen($url['host'], $url_port);
		if (!$fp) return "Failed to open socket to " . $url['host'];

//header的資訊
		fputs($fp, "Host: " . $url['host'] . "\n");
		fputs($fp, 'POST ' . $url['path'] . ($url['query'] ? '?' . $url['query'] : '') . " HTTP/1.0\r\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
		fputs($fp, "Content-length: " . strlen($encoded) . "\n");
		fputs($fp, "Connection: close\n\n");
		fputs($fp, $encoded . "\n");

//取得回應的內容
		$line = fgets($fp, 1024);
		if (!eregi("^HTTP/1.. 200", $line)) return;
		$results = "";
		$inheader = 1;
		while (!feof($fp)) {
			$line = fgets($fp, 2048);
			if ($inheader && ($line == "\n" || $line == "\r\n")) {
				$inheader = 0;
			} elseif (!$inheader) {
				$results .= $line;
			}
		}

		fclose($fp);
		return $results;
	}*/
/*$arrVal["eee"] = "Hello";
$arrVal["ee"] = "Sorry";
$reuslt = "";
$reuslt = sendpost("http://127.0.0.1/test/postFile.php", $arrVal);
var_dump($reuslt);
//$arguments = file_get_contents('php://input');
//print_r($arguments);*/

/*$fp = fsockopen("127.0.0.1", 1024, $errno, $errstr, 10);

$filename = '2012_07_23.zip'; //要发送的文件

fwrite($fp, $filename . "\r\n"); //写入文件名 java端用.readLine()..第一行就是文件名

$handle = fopen($filename, "r");

$contents = fread($handle, filesize($filename));
//fwrite($fp,$contents); //小文件可以这样发，但大文件请分段
$data_size = 1024 * 1; //每次1M
$data_count = ceil(strlen($contents) / $data_size); //有多少块数据
for ($i = 0; $i < $data_count; $i++) {
	$data = substr($contents, $i * $data_size, $data_size); //写入到传输socket
	fwrite($fp, $data);
}

fclose($fp);*/
/*//<1> 重定向功能，这种最常见
Header("Location: http://www.liehuo.net/");


//<2> 强制用户每次访问这个页面时获取最新资料，而不是使用存在客户端的缓存。

//代码

//告诉浏览器此页面的过期时间(用格林威治时间表示)，只要是已经过去的日期即可。
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
//告诉浏览器此页面的最后更新日期(用格林威治时间表示)也就是当天,目的就是强迫浏览器获取最新资料
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
//告诉客户端浏览器不使用缓存
header("Cache-Control: no-cache, must-revalidate");
//参数（与以前的服务器兼容）,即兼容HTTP1.0协议
header("Pragma: no-cache");
//输出MIME类型
header("Content-type: application/file");
//文件长度
header("Content-Length: 227685");
//接受的范围单位
header("Accept-Ranges: bytes");
//缺省时文件保存对话框中的文件名称
header("Content－Disposition: attachment; filename=$filename");

//<3> 输出状态值到浏览器，主要用于访问权限控制
header('HTTP/1.1 401 Unauthorized');
header('status: 401 Unauthorized');

//比如要限制一个用户不能访问该页，则可设置状态为404，如下所示，这样浏览器就显示为即该页不存在
header('HTTP/1.1 404 Not Found');
header("status: 404 Not Found");


//注意: 传统的标头一定包含下面三种标头之一，并只能出现一次。 Content-Type: xxxx/yyyy Location: xxxx:yyyy/zzzz Status: nnn xxxxxx 在新的多型标头规格 (Multipart MIME) 方可以出现二次以上。
//使用范例
//范例一: 本例使浏览器重定向到 PHP 的官方网站。

header("Location: http://www.liehuo.net/"); exit;

//范例二: 要使用者每次都能得到最新的资料，而不是 Proxy 或 cache 中的资料，可以使用下列的标头

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

//范例三: 让使用者的浏览器出现找不到档案的信息。

header("Status: 404 Not Found");

//范例四:让使用者下载档案。

header("Content-type: application/x-gzip");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Description: PHP5 Generated Data");

//header -- 发送一个原始 HTTP 标头说明

//void header ( string string [, bool replace [, int http_response_code]] )

//header() 函数用来发送一个原始 HTTP 标头。有关 HTTP 标头的更多内容见 HTTP/1.1 规范。
//可选参数 replace 指明是替换掉前一条类似的标头还是增加一条相同类型的标头。默认为替换，但如果将其设为 FALSE 则可以强制发送多个同类标头。例如:


header('WWW-Authenticate: Negotiate');
header('WWW-Authenticate: NTLM', false);


//第二个可选参数 http_response_code 强制将 HTTP 响应代码设为指定值（此参数是 PHP 4.3.0 新加的）。
//有两种特殊的 header 调用。第一种是标头以字符串“HTTP/”（大小写不重要）开头的，可以用来确定要发送的 HTTP 状态码。例如，如果配置了 Apache 用 PHP 来处理找不到文件的错误处理请求（使用 ErrorDocument 指令），需要确保脚本产生了正确的状态码。


header("HTTP/1.0 404 Not Found")


//注: HTTP 状态码标头行总是第一个被发送到客户端，而并不管实际的 header() 调用是否是第一个。除非 HTTP 标头已经发送出去，任何时候都可以通过用新的状态行调用 header() 函数来覆盖原先的。
//
//HTTP状态检测（HTTP Header）：http://www.veryhuo.com/tools/http_header.php
//
//第二种特殊情况是以“Location:”标头。它不只是把这个标头发送回浏览器，它还将一个 REDIRECT（302）状态码返回给浏览器，除非之前已经发出了某个 3xx 状态码。


header("Location: http://www.example.com/");
//  重定向浏览器
// 确保重定向后，后续代码不会被执行
exit;


//注: HTTP/1.1 标准需要一个绝对地址的 URI 做为 Location: 的参数, 但有一些客户端支持相对 URI。通常可以使用 $_SERVER['HTTP_HOST']、$_SERVER['PHP_SELF'] 及 dirname() 函数来自己从相对 URI 产生出绝对 URI：


header("Location: http://" . $_server['http_host'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $relative_url);


//注: 即使启用了 session.use_trans_sid，Session ID 也不会随着 Location 头信息被传递。必须手工用 SID 常量来传递。
//
//PHP 脚本通常会产生一些动态内容，这些内容必须不被浏览器或代理服务器缓存。很多代理服务器和浏览器都可以被下面的方法禁止缓存：


header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间


//注: 可能会发现即使不输出上面所有的代码，网页也没有被缓冲。用户有很多选项可以设置来改变浏览器的默认缓存行为。通过发送上述标头，应该可以覆盖任何可以导致脚本页面被缓存的设置。
//
//另外，当使用了 session 时，利用 session_cache_limiter() 函数和 session.cache_limiter 选项可以用来自动产生正确的缓存相关标头。
//
//要记住 header() 必须在任何实际输出之前调用，不论是来自普通的 HTML 标记，空行或者 PHP。有一个常见错误就是在通过 include()，require() 或一些其它的文件存取类函数读取代码时，有一些空格或者空行在调用 header() 之前被发送了出去。同样在一个单独的 PHP/HTML 文件中这个错误也很普遍。


//这将产生一个错误，因为在调 header()
//之前已经输出了东西
header('Location: http://www.example.com/');


//注: 自 PHP 4 起，可以通过一些输出缓冲函数来解决这个问题。代价是把所有向浏览器的输出都缓存在服务器，直到下命令发送它们。可以在代码中使
用 ob_start() 及 ob_end_flush() 来实现这样的功能，或者通过修改 php.ini 中的 output_buffering 配置选项来实现，也可以通过修改服务器配置文件来实现。
//
//附header()两个常用用法：*/
