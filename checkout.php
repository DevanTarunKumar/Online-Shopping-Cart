<?php
    //To deal with buffer overflow
	ob_start();
	session_start();
session_regenerate_id();
error_reporting(0);
@ini_set('display_errors', 0);

	require_once 'config/connect.php';
	//User Authentication
	if(!empty($_SESSION['cart'])){
                    	//if it has any elements then only assign it to $cart variable
					$cart = $_SESSION['cart'];
					
				}
				else
				{
					 echo "<script>
						alert('Cart is empty add items to cart before you checkout');
						window.location.href='index.php';
						</script>";
					
				}
	if(!isset($_SESSION['customer']) & empty($_SESSION['customer'])){
		echo "<script>
						
						window.location.href='login.php';
						</script>";
		
	}

//retrieves the customerId from the session 
include 'inc/header.php'; 
include 'inc/nav.php'; 
$uid = mysqli_real_escape_string($connection, $_SESSION['customerid']);
$email = mysqli_real_escape_string($connection, $_SESSION['customer']);

if(isset($_POST) & !empty($_POST)){
	//We check if all the conditions are agreed, if agree button is not pressed then 
	//we cant proceed further 
	if(isset($_POST['agree']) & $_POST['agree'] == true){
		//We sanatize the inputs as these values will be inserted into database so may cause issues if they are not sanatized properly 
		if(!empty($_POST['country'])&&!empty($_POST['fname'])&&!empty($_POST['lname'])&&!empty($_POST['company'])&&!empty($_POST['address1'])&&!empty($_POST['address2'])&&!empty($_POST['city'])&&!empty($_POST['state'])&&!empty($_POST['phone'])&&!empty($_POST['payment'])&&!empty($_POST['zipcode'])){
		$country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
		$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
		$lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
		$company = filter_var($_POST['company'], FILTER_SANITIZE_STRING);
		$address1 = filter_var($_POST['address1'], FILTER_SANITIZE_STRING);
		$address2 = filter_var($_POST['address2'], FILTER_SANITIZE_STRING);
		$city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		$state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
		$phone = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
		$payment = filter_var($_POST['payment'], FILTER_SANITIZE_STRING);
		$zip = filter_var($_POST['zipcode'], FILTER_SANITIZE_NUMBER_INT);
        //Checks if the users data already exists in the usermeta table
        //If so then it UPDATES the table with newly changed user data
        //If there is no user data then the data is INSERTED
		$sql = "SELECT * FROM usersmeta WHERE uid=$uid";
		$res = mysqli_query($connection, $sql);
		$r = mysqli_fetch_assoc($res);
		$count = mysqli_num_rows($res);
		//if count is not 1 then there is no UserMeta Data saved already
		if($count == 1){
			//update data in usersmeta table
			$usql = "UPDATE usersmeta SET country='$country', firstname='$fname', lastname='$lname', address1='$address1', address2='$address2', city='$city', state='$state',  zip='$zip', company='$company', mobile='$phone' WHERE uid=$uid";
			$ures = mysqli_query($connection, $usql) or die(mysqli_error($connection));
			if($ures){
//If the user data update is succesful then
//We calculate the TOTAL expense of the products 
				$total = 0;
				foreach ($cart as $key => $value) {
					//echo $key . " : " . $value['quantity'] ."<br>";
					$ordsql = "SELECT * FROM products WHERE id=$key";
					$ordres = mysqli_query($connection, $ordsql);
					$ordr = mysqli_fetch_assoc($ordres);

					$total = $total + ($ordr['price']*$value['quantity']);
					$fin=$ordr['quantity']-$value['quantity'];
					$quansql = "UPDATE products SET quantity=$fin WHERE id=$key";
					$quanres = mysqli_query($connection, $quansql);
					
				}
//Then update the order details with 
//The UID is userId which is retrived from Session 
//Total Price is calculated above
//Order status with static string "Order Placed "
//Payment method like cod 

				echo $iosql = "INSERT INTO orders (uid, totalprice, orderstatus, paymentmode) VALUES ('$uid', '$total', 'Order Placed', '$payment')";
				$iores = mysqli_query($connection, $iosql) or die(mysqli_error($connection));
				if($iores){
//If the orders table is succesfully inserted 
//Then we update orderItems table
//Return the auto-generated id used in the last query so order id generated is retrived
					$orderid = mysqli_insert_id($connection);
					//We retrieve all the products in the cart and their quantity to insert into the order Items DB 
					foreach ($cart as $key => $value) {
				
						$ordsql = "SELECT * FROM products WHERE id=$key";
						$ordres = mysqli_query($connection, $ordsql);
						$ordr = mysqli_fetch_assoc($ordres);

						$pid = $ordr['id'];
						$productprice = $ordr['price'];
						//We get the quantity of items ordered from Cart session
						$quantity = $value['quantity'];
						$_SESSION['dontknow']=$orderid;
						$orditmsql = "INSERT INTO orderitems (pid, orderid, productprice, pquantity) VALUES ('$pid', '$orderid', '$productprice', '$quantity')";
						$orditmres = mysqli_query($connection, $orditmsql) or die(mysqli_error($connection));
						
					}
				}
//If order is placed and order table is succesfully updated then we unset the session and redirect the user to my-account.php
				unset($_SESSION['cart']);
				if($_POST['payment'] == cod){
					header("location: my-account.php");
					exit();
				}
				else 
				{
					header("location: paypal/index.php");
				exit();}
		}
	}
		else
		{
			//insert data in usersmeta table
			$isql = "INSERT INTO usersmeta (country, firstname, lastname, address1, address2, city, state, zip, company, mobile, uid) VALUES ('$country', '$fname', '$lname', '$address1', '$address2', '$city', '$state', '$zip', '$company', '$phone', '$uid')";
			$ires = mysqli_query($connection, $isql) or die(mysqli_error($connection));
			if($ires){

				$total = 0;
				foreach ($cart as $key => $value) {
					//echo $key . " : " . $value['quantity'] ."<br>";
					$ordsql = "SELECT * FROM products WHERE id=$key";
					$ordres = mysqli_query($connection, $ordsql);
					$ordr = mysqli_fetch_assoc($ordres);

					//$total = $total + ($ordr['price']*$value['quantity']);
					$total = $total + ($ordr['price']*$value['quantity']);
				}

				echo $iosql = "INSERT INTO orders (uid, totalprice, orderstatus, paymentmode) VALUES ('$uid', '$total', 'Order Placed', '$payment')";
				$iores = mysqli_query($connection, $iosql) or die(mysqli_error($connection));
				if($iores){
					//echo "Order inserted, insert order items <br>";
					$orderid = mysqli_insert_id($connection);
					foreach ($cart as $key => $value) {
						//echo $key . " : " . $value['quantity'] ."<br>";
						$ordsql = "SELECT * FROM products WHERE id=$key";
						$ordres = mysqli_query($connection, $ordsql);
						$ordr = mysqli_fetch_assoc($ordres);

						$pid = $ordr['id'];
						$productprice = $ordr['price'];
						$quantity = $value['quantity'];

						$_SESSION['dontknow']=$orderid;
						$orditmsql = "INSERT INTO orderitems (pid, orderid, productprice, pquantity) VALUES ('$pid', '$orderid', '$productprice', '$quantity')";
						$orditmres = mysqli_query($connection, $orditmsql) or die(mysqli_error($connection));
						//if($orditmres){
							//echo "Order Item inserted redirect to my account page <br>";
						//}
					}
				}
				unset($_SESSION['cart']);
				if($_POST['payment'] == cod){
					header("location: my-account.php");
					exit();
				}
				else 
				{
					header("location: paypal/index.php");
				
				exit();}
			}
		}
	}
	else
	{
    //if not inserted or not unique then the registration will be failed and message will be retreived with help of the below message 2
    //$fmsg = "Invalid Login Credentials";
    header("location: checkout.php?message=1");
  	}
	}


}

$sql = "SELECT * FROM usersmeta WHERE uid=$uid";
$res = mysqli_query($connection, $sql);
$r = mysqli_fetch_assoc($res);
$sql2 = "SELECT * FROM users WHERE email='$email'";
$res2 = mysqli_query($connection, $sql2);
$r2 = mysqli_fetch_assoc($res2);
?>

	
	<!-- SHOP CONTENT -->
	<section id="content">
		<div class="content-blog">
					<div class="page_header text-center">
						<h2>Shop - Checkout</h2>
						<p>Value for money</p>
					</div>
					<?php if(isset($_GET['message'])){
								if($_GET['message'] == 1){
						 ?><div class="alert alert-danger" role="alert"> <?php echo "enter all details"; ?> </div>

						 <?php } }?>
<form method="post">
<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="billing-details">
						<h3 class="uppercase">Billing Details</h3>
						<div class="space30"></div>
							<label class="">Country </label>
							<select name="country" class="form-control">
								<option value="">Select Country</option>
                                                                 <option value="US">USA</option>
                                                                 <option value="IN">India</option>
								<option value="AX">Aland Islands</option>
								<option value="AF">Afghanistan</option>
								<option value="AL">Albania</option>
								<option value="DZ">Algeria</option>
								<option value="AD">Andorra</option>
								<option value="AO">Angola</option>
								<option value="AI">Anguilla</option>
								<option value="AQ">Antarctica</option>
								<option value="AG">Antigua and Barbuda</option>
								<option value="AR">Argentina</option>
								<option value="AM">Armenia</option>
								<option value="AW">Aruba</option>
								<option value="AU">Australia</option>
								<option value="AT">Austria</option>
								<option value="AZ">Azerbaijan</option>
								<option value="BS">Bahamas</option>
								<option value="BH">Bahrain</option>
								<option value="BD">Bangladesh</option>
								<option value="BB">Barbados</option>
							</select>
							<div class="clearfix space20"></div>
							<div class="row">
								<div class="col-md-6">
<!--IMP here we check if this feild is already present in DB-->
<!--If not then we update it atleast with last value user entered before pressing submit-->
<!--If nothing is entered then free space is displayed -->
									<label>First Name </label><script>alert(<?php echo $r2['first']; ?>)</script>
									<input name="fname" class="form-control" placeholder="" value="<?php echo $r2['first']; ?>" type="text">
								</div>
								<div class="col-md-6">
									<label>Last Name </label>
									<input name="lname" class="form-control" placeholder="" value="<?php echo $r2['last']; ?>" type="text">
								</div>
							</div>
							<div class="clearfix space20"></div>
							<label>Company Name</label>
							<input name="company" class="form-control" placeholder="" value="<?php if(!empty($r['company'])){ echo $r['company']; }elseif(isset($company)){ echo $company; } ?>" type="text">
							<div class="clearfix space20"></div>
							<label>Address </label>
							<input name="address1" class="form-control" placeholder="Street address" value="<?php if(!empty($r['address1'])){ echo $r['address1']; } elseif(isset($address1)){ echo $address1; } ?>" type="text">
							<div class="clearfix space20"></div>
							<input name="address2" class="form-control" placeholder="Apartment, suite, unit etc." value="<?php if(!empty($r['address2'])){ echo $r['address2']; }elseif(isset($address2)){ echo $address2; } ?>" type="text">
							<div class="clearfix space20"></div>
							<div class="row">
								<div class="col-md-4">
									<label>City </label>
									<input name="city" class="form-control" placeholder="City" value="<?php if(!empty($r['city'])){ echo $r['city']; }elseif(isset($city)){ echo $city; } ?>" type="text">
								</div>
								<div class="col-md-4">
									<label>State</label>
									<input name="state" class="form-control" value="<?php if(!empty($r['state'])){ echo $r['state']; }elseif(isset($state)){ echo $state; } ?>" placeholder="State" type="text">
								</div>
								<div class="col-md-4">
									<label>Postcode </label>
									<input name="zipcode" class="form-control" placeholder="Postcode / Zip" value="<?php if(!empty($r['zip'])){ echo $r['zip']; }elseif(isset($zip)){ echo $zip; } ?>" type="text">
								</div>
							</div>
							<div class="clearfix space20"></div>
							<label>Phone </label>
							<input name="phone" class="form-control" id="billing_phone" placeholder="" value="<?php if(!empty($r['mobile'])){ echo $r['mobile']; }elseif(isset($phone)){ echo $phone; } ?>" type="text">
						
					</div>
				</div>
				
			</div>
			
			<div class="space30"></div>
			<h4 class="heading">Your order</h4>
			
			<table class="table table-bordered extra-padding">
				<tbody>
					<tr>
						<th>Cart Subtotal</th>
						<td><span class="amount">$<?php echo $total ?>.00/-</span></td>
					</tr>
					<tr>
						<th>Shipping and Handling</th>
						<td>
							Free Shipping				
						</td>
					</tr>
					<tr>
						<th>Order Total</th>
						<td><strong><span class="amount">$<?php echo $total ?>.00/-</span></strong> </td>
					</tr>
				</tbody>
			</table>
			
			
			<div class="clearfix space30"></div>
			<h4 class="heading">Payment Method</h4>
			<div class="clearfix space20"></div>
			
			<div class="payment-method">
				<div class="row">

					<div class="col-md-4">
							<input name="payment" id="radio1" style="font-weight:bold" class="css-checkbox" type="radio"><span value="paypal">Paypal</span>
							<div class="space20"></div>
							
						</div>
					
						<div class="col-md-4">
							<input name="payment" id="radio2" style="font-weight:bold" class="css-checkbox" type="radio" value="cod"><span>Cash On Delivery</span>
							<div class="space20"></div>
							
						</div>
						
						
					
				</div>
				<div class="space30"></div>
				
					<input name="agree" id="checkboxG2" class="css-checkbox" type="checkbox" value="true"><span>I've read and accept the <a href="#">terms &amp; conditions</a></span>
				
				<div class="space30"></div>
				<input type="submit" class="button btn-lg" value="Pay Now">
			</div>
		</div>		
</form>		
		</div>
	</section>
	
<?php include 'inc/footer.php' ?>
