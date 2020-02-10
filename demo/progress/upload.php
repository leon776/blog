<?php  
$res = '上传成功';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	//$res = json_encode(apc_fetch("upload_{$_POST['APC_UPLOAD_PROGRESS']}"));
}
header("Content-Type: text/plain"."\r\n");
header("Content-Length: ".strlen($res)."\r\n");
header("Accept-Ranges: bytes"."\r\n");
header("Content-Encoding: compress"."\r\n");
echo $res;
?>