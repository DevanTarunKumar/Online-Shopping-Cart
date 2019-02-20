<!--This page is useful for routing the Registered users to check out and users with no proper details back to login-->

<?php
session_start();
session_regenerate_id();
require_once 'config/connect.php';  
require_once 'password_compat/lib/password.php';
// define variables and set to empty values
if(isset($_POST) & !empty($_POST)){
    //Here we are using more secured email and password authentication
  $email = mysqli_real_escape_string($connection, $_POST['email']);

$first=mysqli_real_escape_string($connection, $_POST['fname']);
$last=mysqli_real_escape_string($connection, $_POST['lname']);
$password = $_POST['password'];
  $password1 = $_POST['passwordagain'];
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
 
if($password==$password1 && $password!=" " && !empty($email) && ($email!=" ") && !empty($first) && $first!=" "&& !empty($last) && $last!=" ")
  {
if(!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
  header("location: login.php?message=6");
exit();
} 
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
  $sql = "SELECT * FROM users WHERE email='$email'";
  $result = mysqli_query($connection, $sql);
  $count=mysqli_num_rows($result);
if($count==0){
  $sql = "INSERT INTO users (email, first, last, password) VALUES ('$email', '$first', '$last', '$password')";
  $result = mysqli_query($connection, $sql);
  if($result){
    //echo "User exits, create session";
    $_SESSION['customer'] = $email;
    $_SESSION['customerid'] = mysqli_insert_id($connection);
    if(!empty($_SESSION['cart'])){
                      //if it has any elements then only assign it to $cart variable
           header("location: checkout.php");
          
        }
        else
        {
            header("location: edit-address.php");
        }
   
  }
}
else if($count==1){
    header("location: login.php?message=3");
exit();
  }
}
  else if($password!=$password1) {
    header("location: login.php?message=5");
exit();
  }
else if($email==" " || empty($email)) {
   
header("location: login.php?message=4");
exit();
  }
else if($first==" " || empty($first)) {
   header("location: login.php?message=7");
exit();
  }
else if($last==" " || empty($last)){
    header("location: login.php?message=8");
exit();
  }

}

?>





