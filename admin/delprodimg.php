<?php
	session_start();
session_regenerate_id();
	require_once '../config/connect.php';
	if(!isset($_SESSION['admin']) & empty($_SESSION['admin'])){
		header('location: login.php');
	}
    // Acces the Id from the edit product page 
	if(isset($_GET['id']) & !empty($_GET['id'])){
		$id = intval($_GET['id']);
		$sql = "SELECT thumb FROM products WHERE id=$id";
		$res = mysqli_query($connection, $sql);
		$r = mysqli_fetch_assoc($res);
		if(!empty($r['thumb'])){
			//unlink the thumb variable 
			if(unlink($r['thumb'])){
				//update the thumbnail variable with null
				$delsql = "UPDATE products SET thumb='' WHERE id=$id";
				if(mysqli_query($connection, $delsql)){
					//redirect back to edit product page 
					header("location:editproduct.php?id={$id}");
				}
			}else{
				$delsql = "UPDATE products SET thumb='' WHERE id=$id";
				if(mysqli_query($connection, $delsql)){
					header("location:editproduct.php?id={$id}");
				}
			}

	}else{
		$delsql = "UPDATE products SET thumb='' WHERE id=$id";
		if(mysqli_query($connection, $delsql)){
			header("location:editproduct.php?id={$id}");
		}
	}
}else{
	header("location:editproduct.php?id={$id}");
}