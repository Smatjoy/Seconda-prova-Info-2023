<?php
$mysqli = new mysqli("localhost", "root", "", "educational-games");
if ($mysqli -> connect_errno != 0) {
	http_response_code(500);
	die("DB ERROR");
}
?>