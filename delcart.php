<?php
session_start();
session_regenerate_id();
if(isset($_GET['id']) & !empty($_GET['id'])){
	$id = $_GET['id'];
	unset($_SESSION['cart'][$id]);
	header('location: cart.php');
}
?>