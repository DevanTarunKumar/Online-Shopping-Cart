<?php 
session_start();
session_regenerate_id();
require_once 'config/connect.php';
error_reporting(0);
@ini_set('display_errors', 0);
if(isset($_GET['id']) & !empty($_GET['id']))
{
		$id = intval($_GET['id']);
} 
else if(isset($_SESSION['cardaddId']))
{
$id =  intval($_SESSION['cardaddId']);
unset($_SESSION['cardaddId']);
}

if(!empty($id)){
		
	$prodsql = "SELECT * FROM products WHERE id=$id";
	$prodres = mysqli_query($connection, $prodsql);
	$prodr = mysqli_fetch_assoc($prodres);
	$available = intval($prodr['quantity']);
	$zero = 0;
	if($available <= $zero)
	{
		header('location: single.php?message=1&id='.$id);
		exit();
	}
	else if(isset($_GET['quant']) & !empty($_GET['quant']))
		{   
			$quant = intval($_GET['quant']);
			if($quant <=0 )
			{
				header('location: single.php?message=2&id='.$id);
				exit();
			}
                        else if ($quant > 20)
{
header('location: single.php?message=6&id='.$id);
				exit();


}

			else if ($quant > $available)
			{
				header('location: single.php?message=3&id='.$id);
				exit();
			}
		}
		else
		{
			$quant = 1;
		}
		$me = $_SESSION['cart'][$id]['quantity'];
		$quant = $quant + intval($me);
		$_SESSION['cart'][$id]= array('quantity' => $quant);
		header('location: cart.php');
}
else
{
	header('location: cart.php');
}
?>
