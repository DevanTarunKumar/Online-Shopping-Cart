<!--This page is useful for routing the logged in users to check out and users with no proper details back to login-->
<?php 
session_start();
session_regenerate_id();
require_once 'config/connect.php'; 
require_once 'password_compat/lib/password.php';
if(isset($_POST) & !empty($_POST)){
        $email = mysqli_real_escape_string($connection, $_POST['email']);
	$email = filter_var($email, FILTER_SANITIZE_EMAIL);
	$password = $_POST['password'];
	if(empty($email))
	{

		header("location: index.php");
	}
	$sql = "SELECT * FROM users WHERE email='$email'";
	$result = mysqli_query($connection, $sql);
	$count = mysqli_num_rows($result);
	$r = mysqli_fetch_assoc($result);

	if($count == 1){

	
if(password_verify($password, $r['password'])){		
	//echo "User exits, create session";
			$_SESSION['customer'] = $email;
			$_SESSION['customerid'] = $r['id'];
			header("location: index.php");
		}else{
			//$fmsg = "Invalid Login Credentials";
                        sleep(10);
			header("location: login.php?message=1");
		}
	}else{
                sleep(10);
		header("location: login.php?message=1");
	}
}
?>