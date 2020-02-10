<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
	header('WWW-Authenticate: Basic realm="Jnecw.com"');
	header('HTTP/1.0 401 Unauthorized');
	echo '用户点击取消按钮后发送';
	exit;
} 
else {
	if($_SERVER['PHP_AUTH_USER'] === 'xxx' && $_SERVER['PHP_AUTH_PW'] === 'xxx') {
		//do sth
	}
}
?>
<script>
function login() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    xhr = new XMLHttpRequest();

    xhr.open("POST", "<?php echo $_SERVER['PHP_SELF'];?>", false, username, password);
    xhr.setRequestHeader("Authorization", "Basic bGV5aWZyZWU6MTY4OTE4dHdpdHRlcg=="); 
    xhr.send(null);

    return xhr.status == 200;
}
</script>