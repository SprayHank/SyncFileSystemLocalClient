<?php defined('SYNCSYSTEM') || die('No direct script access.');

if(!@$_REQUEST['talkingSite']) {
	$talkingSite = substr(@$_SERVER['HTTP_REFERER'], 7, (strpos(@$_SERVER['HTTP_REFERER'], 'sync.php') - 8));
} else {
	$talkingSite = $_REQUEST['talkingSite'];
}
if(!$talkingSite) {
	exit($head.' unknow site!!'.$foot);
}

spl_autoload_register('sync_autoload');
function sync_autoload($class){
	$cls = './class/'.$class.'.class.php';
	is_file($cls) && is_readable($cls) && require($cls);//目标为文件（非目录），可读，载入
}

require 'config.php';
require 'functions.php';

$localdir = "D:/Site/$talkingSite/";

is_dir($localdir) || die('NO Local system tomanage');


$submit = '';
isset($_REQUEST['submit']) && $submit = $_REQUEST['submit'];
$operation = '';
isset($_REQUEST['operation']) && $operation = $_REQUEST['operation'];
$do = '';
isset($_REQUEST['do']) && $do = $_REQUEST['do'];

if($do != ''){
	$includefiles = isset($_REQUEST['includefiles']) ? $_REQUEST['includefiles'] : array();
	$list = isset($_REQUEST['list']) ? str_replace('"', '',str_replace($localdir, '', str_replace('\\', '/', $_REQUEST['list']))) : '';
	$listArray = explode(' ', $list);
	$targetList = array_merge($listArray, $includefiles);
}


exit;


if(@!$_REQUEST['do']) {
} else {


	echo $head;
	$hiddenform = '';
	if($_REQUEST['do'] == 'MD5 Compare') {
		//		$fp = fopen('./md5.xml', 'w');
		//		fwrite($fp, '');
		//		fclose($fp);
		//		$fp = fopen('./md5.xml', 'a');
		function listfiles($dir = ".") {
			global $sublevel, $localdir, $fp, $ignores, $hiddenform;
			$sub_file_num = 0;
			$dir          = preg_replace('/^\.\//i', '', $dir);
			$realdir      = $localdir.$dir;
			if(is_file("$realdir")) {
				//fwrite($fp, md5_file($realdir) . ' *' . $dir."\n");
				$hiddenform .= '<input type="hidden" name="file['.g2u($dir).']" value="'.md5_file($realdir).'" />'."\n";
				return 1;
			}

			$handle = opendir("$realdir");
			$sublevel++;
			while($file = readdir($handle)) {
				if(preg_match($ignores, $file)) continue;
				$sub_file_num += listfiles("$dir/$file");
			}
			closedir($handle);
			$sublevel--;
			return $sub_file_num;
		}


		$filenum  = 0;
		$sublevel = 0;

		foreach($targetList as $file) {
			$filenum += listfiles($file);
		}
		$includefiles = serialize($includefiles);
		$hiddenform .= <<<HTML
<input type="hidden" name="operation" value="md5" />
<input type="hidden" name="list" value="$list" />
<input type="hidden" name="includefiles" value="$_REQUEST[includefiles]" />
HTML;

		//$package->createfile();
		//fclose($fp);

	} elseif($_REQUEST['do'] == 'upload'){


		packfiles($targetList);

		$package = realpath('package.zip');
		$data    = array('file' => "@$package");
		$res     = curlrequest("http://$talkingSite/sync.php?operation=push", $data);
		echo($res);
		//$hiddenform .= "<input type='hidden' name='operation' value='' />";
	} elseif($_REQUEST['do'] == 'sync'){
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
					//@unlink(u2g($localdir.$file));
					$delete[] = $file;
					//echo '<input type="hidden" name="delete[]" value="' . $file . '" />';
					break;
			}
			$op = $option.'[]';
			$hiddenform .= "<input type='hidden' name='$op' value='$file' />\n";
		}
		$hiddenform .= "<input type='hidden' name='operation' value='md5checkedsync' />";
		if(count($upload)) {
			packfiles($upload);
			$package = realpath('package.zip');
			$data    = array('file' => "@$package");
			$res     = curlrequest("http://$talkingSite/sync.php?operation=push", $data);
			echo($res);
		}
	}
	echo <<<FOM
		\n
<form action="http://$talkingSite/sync.php" method="post" enctype="multipart/form-data">
<label for="file">Filename:</label>
$hiddenform
<br />
<script type="text/javascript">
document.getElementsByTagName('FORM')[0].submit();
</script>
</form>
FOM;
	exit;
}
if($_REQUEST['operation'] == 'postpackagetoremote') {

	//echo realpath('package.zip');
	$package = realpath('package.zip');
	$res     = curlrequest("http://$talkingSite/sync.php?operation=push", array('file' => "@$package"));
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
	$res = curlrequest("http://$talkingSite/sync.php?operation=md5checkedsync", $data);
	echo $res;
	exit;
	/*echo <<<HTML
	<br/>
<input type="submit" name="submit" value="submit" />
</form>
HTML;*/
} elseif($_REQUEST['operation'] == 'messagetopick') {


} elseif($_REQUEST['operation'] == 'pulltolocal') {
	echo $head;
	pulltolocal();
	echo $foot;
	exit;
} elseif($_REQUEST['operation'] == 'catchthepull') {

} elseif($_REQUEST['operation'] == 'md5ResultToLocal') {
	echo '<form action="sync.php?operation=aftermd5check" method="post">';
	echo "<input type='hidden' name='talkingSite' value='$talkingSite' />";
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
		选择要压缩的文件或目录：<a href="http://$talkingSite/sync.php">浏览远程文件</a><br>
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
		?>
		<br/>
		<div style="clear:both;">
			<input type='button' value='反选' onclick='selrev();'>
			<input type='button' value='测试' onclick='ssd()'>
			<input type='hidden' name='talkingSite' value="<?= $talkingSite ?>"/>
			<input type='text' name='list' style="width:400px;"/>
			<input type="submit" name="submit" value="zip">
			<input type="submit" name="submit" value="md5">
		</div>
		<script language='javascript'>
			function selrev() {
				with (document.myform) {
					for (i = 0; i < elements.length; i++) {
						var thiselm = elements[i];
						if (thiselm.name.match(/includefiles\[\]/))    thiselm.checked = !thiselm.checked;
					}
				}
			}
			function ssd() {
				with (document.myform) {
					for (i = 0; i < elements.length; i++) {
						var thiselm = elements[i];
						if (thiselm.name.match(/includefiles\[\]/))    thiselm.indeterminate = !thiselm.indeterminate;
					}
				}
			}
		</script>
		</form>
	<?php
	} elseif($_REQUEST['submit'] == 'zip') {
		echo $head;
		if(!@$_REQUEST['includefiles']) {
			$_REQUEST['includefiles'] = array();
		}
		$targetList = array_merge(explode(' ', @$_REQUEST['list']), $_REQUEST['includefiles']);

		packfiles($targetList);

		$package = realpath('package.zip');
		$data    = array('file' => "@$package");
		$res     = curlrequest("http://$talkingSite/sync.php?operation=push", $data);
		echo $res;
		echo $foot;


	}
}


function packfiles($files) {
	global $localdir;
	$Zip = new PclZip('./package.zip');
	//$_REQUEST['includefiles'] = array('./xwb.php', './userapp.php');
	$_files = array();
	function listfiles($dir = ".", $_files) {
		global $localdir, $ignores;
		$dir     = preg_replace('/^\.\//i', '', $dir);
		$realdir = $localdir.$dir;
		if(is_file("$realdir")) {
			//print_r($realdir);
			//fwrite($fp, md5_file($realdir) . ' *' . $dir."\n");
			//$hiddenform .= '<input type="hidden" name="file[' . g2u($dir) . ']" value="' . md5_file($realdir) . '" />';
			array_push($_files, "$realdir");
			return $_files;
		}

		$handle = opendir("$realdir");
		while($file = readdir($handle)) {
			if(preg_match($ignores, $file)) continue;
			$_files = listfiles("$dir/$file", $_files);
		}
		closedir($handle);
		return $_files;
	}

	foreach($files as $file) {
		$_files = listfiles($file, $_files);
	}
	//print_r($_files);
	$Zip->create($_files, PCLZIP_OPT_REMOVE_PATH, $localdir);
	$list = $Zip->listContent();
	if($list) {
		$fold       = 0;
		$fil        = 0;
		$tot_comp   = 0;
		$tot_uncomp = 0;
		foreach($list as $key => $val) {
			if($val['folder'] == '1') {
				++$fold;
			} else {
				++$fil;
				$tot_comp += $val['compressed_size'];
				$tot_uncomp += $val['size'];
			}
		}
		$message = '<font color="green">压缩目标文件：</font><font color="red"> '.'package.zip'.'</font><br />';
		$message .= '<font color="green">压缩文件详情：</font><font color="red">共'.$fold.' 个目录，'.$fil.' 个文件</font><br />';
		$message .= '<font color="green">压缩文档大小：</font><font color="red">'.dealsize($tot_comp).'</font><br />';
		$message .= '<font color="green">解压文档大小：</font><font color="red">'.dealsize($tot_uncomp).'</font><br />';
		//$message .= '<font color="green">压缩执行耗时：</font><font color="red">' . G('_run_start', '_run_end', 6) . ' 秒</font><br />';
		echo $message;


	} else {
		exit ($localdir."package.zip 不能写入,请检查路径或权限是否正确.<br>");
	}
}


function curlrequest($url, $data, $method = 'post') {
	$ch = curl_init(); //初始化CURL句柄
	curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
	//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); //设置请求方式
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-HTTP-Method-Override: $method")); //设置HTTP头信息
	curl_setopt($ch, CURLOPT_POST, 1); //以post方式提交数据
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data); //设置提交的字符串
	$document = curl_exec($ch); //执行预定义的CURL
	if(!curl_errno($ch)) {
		$info = curl_getinfo($ch);
		echo '<div>Took '.$info['total_time'].' seconds to send a request to '.$info['url'].'</div>';
	} else {
		echo 'Curl error: '.curl_error($ch);
	}
	curl_close($ch);
	return $document;
}

function pulltolocal() {
	global $talkingSite, $localdir;
	$reuslt = "";
	$reuslt = file_get_contents("http://$talkingSite/package.zip");
	$fp     = fopen('./package.zip', 'w');
	fwrite($fp, $reuslt);
	fclose($fp);
	$path      = './';
	$name      = 'package.zip';
	$remove    = 0;
	$unzippath = './';
	if(file_exists('./package.zip') && is_file('./package.zip')) {
		$Zip    = new PclZip('./package.zip');
		$result = $Zip->extract(PCLZIP_OPT_PATH, $localdir);
		if($result) {
			$statusCode = 200;
			$list       = $Zip->listContent();
			$fold       = 0;
			$fil        = 0;
			$tot_comp   = 0;
			$tot_uncomp = 0;
			foreach($list as $key => $val) {
				if($val['folder'] == '1') {
					++$fold;
				} else {
					++$fil;
					$tot_comp += $val['compressed_size'];
					$tot_uncomp += $val['size'];
				}
			}
			$message = '<font color="green">解压目标文件：</font><font color="red"> '.g2u($name).'</font><br />';
			$message .= '<font color="green">解压文件详情：</font><font color="red">共'.$fold.' 个目录，'.$fil.' 个文件</font><br />';
			$message .= '<font color="green">压缩文档大小：</font><font color="red">'.dealsize($tot_comp).'</font><br />';
			$message .= '<font color="green">解压文档大小：</font><font color="red">'.dealsize($tot_uncomp).'</font><br />';
			//$message .= '<font color="green">解压总计耗时：</font><font color="red">' . G('_run_start', '_run_end', 6) . ' 秒</font><br />';
		} else {
			$statusCode = 300;
			$message .= '<font color="blue">解压失败：</font><font color="red">'.$Zip->errorInfo(true).'</font><br />';
			//$message .= '<font color="green">执行耗时：</font><font color="red">' . G('_run_start', '_run_end', 6) . ' 秒</font><br />';
		}
	}
	echo($message);
}

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
function MD5_Compare(){

}
function sendpost($url, $data) {
	//先解析url
	$url      = parse_url($url);
	$url_port = "80";
	if(!$url) return "couldn't parse url";
	//将参数拼成URL key1=value1&key2=value2 的形式
	$encoded = "";
	while(list($k, $v) = each($data)) {
		$encoded .= ($encoded ? '&' : '');
		$encoded .= rawurlencode($k)."=".rawurlencode($v);
	}
	$len = strlen($encoded);
	//拼上http头
	$out = "POST ".$url['path']." HTTP/1.1\r\n";
	$out .= "Host:".$url['host']."\r\n";
	$out .= "Content-type: application/x-www-form-urlencoded\r\n";
	$out .= "Connection: Close\r\n";
	$out .= "Content-Length: $len\r\n";
	$out .= "\r\n";
	$out .= $encoded."\r\n";
	//打开一个sock
	$fp = @fsockopen($url['host'], $url_port);
	var_dump($fp);
	$line = "";
	if(!$fp) {
		echo "$errstr($errno)\n";
	} else {
		fwrite($fp, $out);
		while(!feof($fp)) {
			$line .= fgets($fp, 2048);
		}
		//去掉头文件
		if($line) {
			$body = stristr($line, "\r\n\r\n");
			$body = substr($body, 4, strlen($body));
			$line = $body;
		}
		fclose($fp);
		return $line;
	}
}

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
header("Content-Disposition: attachment; filename=文件名");
header("Content-Description: PHP3 Generated Data");

//header -- 发送一个原始 HTTP 标头说明 　

//void header ( string string [, bool replace [, int http_response_code]] )

//　　header() 函数用来发送一个原始 HTTP 标头。有关 HTTP 标头的更多内容见 HTTP/1.1 规范。
//　　可选参数 replace 指明是替换掉前一条类似的标头还是增加一条相同类型的标头。默认为替换，但如果将其设为 FALSE 则可以强制发送多个同类标头。例如:


header('WWW-Authenticate: Negotiate');
header('WWW-Authenticate: NTLM', false);


//　　第二个可选参数 http_response_code 强制将 HTTP 响应代码设为指定值（此参数是 PHP 4.3.0 新加的）。
//　　有两种特殊的 header 调用。第一种是标头以字符串“HTTP/”（大小写不重要）开头的，可以用来确定要发送的 HTTP 状态码。例如，如果配置了 Apache 用 PHP 来处理找不到文件的错误处理请求（使用 ErrorDocument 指令），需要确保脚本产生了正确的状态码。


header("HTTP/1.0 404 Not Found")


//　　注: HTTP 状态码标头行总是第一个被发送到客户端，而并不管实际的 header() 调用是否是第一个。除非 HTTP 标头已经发送出去，任何时候都可以通过用新的状态行调用 header() 函数来覆盖原先的。
//
//　　HTTP状态检测（HTTP Header）：http://www.veryhuo.com/tools/http_header.php
//
//　　第二种特殊情况是以“Location:”标头。它不只是把这个标头发送回浏览器，它还将一个 REDIRECT（302）状态码返回给浏览器，除非之前已经发出了某个 3xx 状态码。


header("Location: http://www.example.com/");
//  重定向浏览器
// 确保重定向后，后续代码不会被执行
exit;


//　　注: HTTP/1.1 标准需要一个绝对地址的 URI 做为 Location: 的参数, 但有一些客户端支持相对 URI。通常可以使用 $_SERVER['HTTP_HOST']、$_SERVER['PHP_SELF'] 及 dirname() 函数来自己从相对 URI 产生出绝对 URI：


header("Location: http://" . $_server['http_host'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $relative_url);


//　　注: 即使启用了 session.use_trans_sid，Session ID 也不会随着 Location 头信息被传递。必须手工用 SID 常量来传递。
//　　
//　　PHP 脚本通常会产生一些动态内容，这些内容必须不被浏览器或代理服务器缓存。很多代理服务器和浏览器都可以被下面的方法禁止缓存：


header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间


//　　注: 可能会发现即使不输出上面所有的代码，网页也没有被缓冲。用户有很多选项可以设置来改变浏览器的默认缓存行为。通过发送上述标头，应该可以覆盖任何可以导致脚本页面被缓存的设置。
//　　
//　　另外，当使用了 session 时，利用 session_cache_limiter() 函数和 session.cache_limiter 选项可以用来自动产生正确的缓存相关标头。
//　　
//　　要记住 header() 必须在任何实际输出之前调用，不论是来自普通的 HTML 标记，空行或者 PHP。有一个常见错误就是在通过 include()，require() 或一些其它的文件存取类函数读取代码时，有一些空格或者空行在调用 header() 之前被发送了出去。同样在一个单独的 PHP/HTML 文件中这个错误也很普遍。


//这将产生一个错误，因为在调 header()
//之前已经输出了东西
header('Location: http://www.example.com/');


//　　注: 自 PHP 4 起，可以通过一些输出缓冲函数来解决这个问题。代价是把所有向浏览器的输出都缓存在服务器，直到下命令发送它们。可以在代码中使用 ob_start() 及 ob_end_flush() 来实现这样的功能，或者通过修改 php.ini 中的 output_buffering 配置选项来实现，也可以通过修改服务器配置文件来实现。
//
//附header()两个常用用法：*/

?>
</body>
</html>