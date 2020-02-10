<?php

if(isset($_GET['progress_key'])) {

  $status = apc_fetch('upload_'.$_GET['progress_key']);
  var_dump($status);
}
//$id = uniqid("");
//echo $id;
?>