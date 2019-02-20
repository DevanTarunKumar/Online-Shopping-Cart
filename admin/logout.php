<?php
	session_start();
session_regenerate_id();
	//session is destroyed and user is redirected to login page for authentication
	session_destroy();
	header('location: login.php');
?>