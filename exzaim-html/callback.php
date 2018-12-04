<?php
if (isset($_GET["code"])) {
	$code = $_GET["code"];
	file_put_contents ("./doc/uploads/code.txt", $code);
}

readfile("./doc/uploads/code.txt");
?>