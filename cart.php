<?php 
session_start();
session_regenerate_id();
require_once 'config/connect.php';
error_reporting(0);
@ini_set('display_errors', 0);

//session holds the cart values like Id and their quantity 
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
include 'inc/header.php'; 
include 'inc/nav.php'; 

?>

	
	<!-- SHOP CONTENT -->
	<section id="content">
		<div class="content-blog">
			<div class="container">
				<div class="row">
					<div class="page_header text-center">
						<h2>UNT Shopping Cart</h2>
						<p>Spend less. Buy more.</p>
					</div>
					<div class="col-md-12">

<table class="cart-table table table-bordered">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>Product</th>
						<th>Price</th>
						<th>Quantity</th>
						<th>Total</th>
						<!--<th>Available quantity</th>-->
					</tr>
				</thead>
				<tbody>
				<?php
					
				$total = 0;
				//Here we access the cart id and variable array
				//Iterate through for each loop and give id as the key of the array that is again the Id of each element in array
					foreach ($cart as $key => $value) {
			
						$cartsql = "SELECT * FROM products WHERE id=$key";
						$cartres = mysqli_query($connection, $cartsql);
						$cartr = mysqli_fetch_assoc($cartres);

					
				 ?>
					<tr>
						<td>
							<a class="remove" href="delcart.php?id=<?php echo $key; ?>"><i class="fa fa-times"></i></a>
						</td>
						<td>
							<a href="#"><img src="admin/<?php echo $cartr['thumb']; ?>" alt="" height="90" width="90"></a>					
						</td>
						<td>
							<!--The product name is limited to 30 charecters-->
							<a href="single.php?id=<?php echo $cartr['id']; ?>"><?php echo substr($cartr['name'], 0 , 30); ?></a>					
						</td>
						<td>
							<span class="amount">$<?php echo $cartr['price']; ?>.00</span>					
						</td>
						<td>
							<div class="quantity"><?php echo $value['quantity']; ?></div>
						</td>
						<td>
							<!--Here the amount is calculated by multplying the quantity with the price -->
							<span class="amount">$<?php echo ($cartr['price']*$value['quantity']); ?>.00</span>					
						</td>
						<!--<td>
							<span class="amount"><?php echo ($cartr['quantity']); ?></span>					
						</td>-->
					</tr>
				<?php 
				// final total is also calculated by summing the total for each product 
					$total = $total + ($cartr['price']*$value['quantity']);
				} ?>
					<tr>
						<td colspan="6" class="actions">
							<div class="col-md-6">
							<!--	<div class="coupon">
									<label>Coupon:</label><br>
									<input placeholder="Coupon code" type="text"> <button type="submit">Apply</button>
								</div> -->
							</div>
							<div class="col-md-6">
								<div class="cart-btn">
									<!-- <button class="button btn-md" type="submit">Update Cart</button> -->
									<a href="checkout.php" class="button btn-md" >Checkout</a>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>		

			<div class="cart_totals">
				<div class="col-md-6 push-md-6 no-padding">
					<h4 class="heading">Cart Totals</h4>
					<table class="table table-bordered col-md-6">
						<tbody>
							<tr>
								<th>Cart Subtotal</th>
								<td><span class="amount">$<?php echo $total; ?>.00</span></td>
							</tr>
							<tr>
								<th>Shipping and Handling</th>
								<td>
									Free Shipping				
								</td>
							</tr>
							<tr>
								<th>Order Total</th>
								<td><strong><span class="amount">$ <?php echo $total; ?>.00</span></strong> </td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>			
							
					</div>
				</div>
			</div>
		</div>
	</section>
<?php include 'inc/footer.php' ?>
