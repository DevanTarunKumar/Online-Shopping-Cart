<?php
require_once 'config/connect.php';
?>
<style type="text/css">
    body{
        font-family: Arail, sans-serif;
    }
    /* Formatting search box */
    .search-box{
        width: 200px;
        position: relative;
        display: inline-block;
        font-size: 14px;
    }
    .search-box input[type="text"]{
        height: 32px;
        padding: 5px 10px;
        border: 1px solid #CCCCCC;
        font-size: 14px;
    }
    .result{
        position: absolute;        
        z-index: 999;
        top: 100%;
        left: 0;
    }
    .search-box input[type="text"], .result{
        width: 100%;
        box-sizing: border-box;
    }
    /* Formatting result items */
    .result p{
        margin: 0;
        padding: 7px 10px;
        border: 1px solid #CCCCCC;
        border-top: none;
        cursor: pointer;
        background: #f2f2f2;
    }
    .result p:hover{
        background: #f2f2f2;
    }
</style>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        if(inputVal.length){
            $.get("backend-search.php", {term: inputVal}).done(function(data){

                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });
    
    // Set search input value on click of result item
    $(document).on("click", ".result p", function(){
        $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
        $(this).parent(".result").empty();
    });
});
</script>
    
<div class="menu-wrap">
				<div id="mobnav-btn">Menu <i class="fa fa-bars"></i></div>
				<ul class="sf-menu">
					<li>
						<a href="index.php">Home</a>
					</li>
					<li>
						<a href="#">Shop</a>
						<div class="mobnav-subarrow"><i class="fa fa-plus"></i></div>
						<ul>
							<?php
							//we select all the categories from the table 
							$catsql = "SELECT * FROM category";
							$catres = mysqli_query($connection, $catsql);
							while($catr = mysqli_fetch_assoc($catres)){
						 ?>
						 <!--Here we display all the categories present and also implemented LINKs redirecting it with Catid so as the index.php will display all the products belonging to particular category-->
							<li><a href="index.php?id=<?php echo $catr['id']; ?>"><?php echo $catr['name']; ?></a></li>
						<?php } ?>
						</ul>
					</li>
<!--If there is no user session set then MY Account Details are hidden-->
					<?php if(!empty($_SESSION['customer'])){ ?>
					<li >		
						<?php $ema=mysqli_real_escape_string($connection,$_SESSION['customer'] );
							$catsql = "SELECT first FROM users where email='$ema'";
							$catres = mysqli_query($connection, $catsql);
							while($catr = mysqli_fetch_assoc($catres)){ 
							
						?>
						<a href="#"><font size="1">Hi, <?php echo $catr['first']; ?></font></br>My Account </a><?php } ?>
						<div class="mobnav-subarrow"><i class="fa fa-plus"></i></div>
						<ul>
							<li><a href="my-account.php">My Orders</a></li>
							<li><a href="edit-address.php">Update Address</a></li>
							<li><a href="logout.php">Logout</a></li>
						</ul>
					</li>
					<?php }?>
                                        <?php if(empty($_SESSION['customer'])){ ?>
					<li>
						<a href="#">Login</a>
						<div class="mobnav-subarrow"><i class="fa fa-plus"></i></div>
						<ul>
							<li><a href="login.php">Login</a></li>
													</ul>
					</li>
					<?php }?>

									
				<div class="header-xtra">
					<!--Session values are retreived -->

					<?php 
					//Initialize the cart with empty array
					//Check if the session has any array elements
                    if(!empty($_SESSION['cart'])){
                    	//if it has any elements then only assign it to $cart variable
					$cart = $_SESSION['cart'];}?>
					<div class="s-cart">
						<div class="sc-ico">
							<i class="fa fa-shopping-cart"></i>
							<em>
								<!--We update the count of the items on cart icon as well as in menu text  -->
								<?php if(empty($cart)){ echo 0; }else {echo count($cart);} ?>
									
							</em>
						</div>

						<div class="cart-info">
							<small>You have <em class="highlight"><?php	echo count($cart); ?> item(s)</em> in your shopping bag</small>
							<br>
							<br>
							<?php
								$total = 0;
								foreach ($cart as $key => $value) {
									$navcartsql = "SELECT * FROM products WHERE id=$key";
									$navcartres = mysqli_query($connection, $navcartsql);
									$navcartr = mysqli_fetch_assoc($navcartres);								
							 ?>
							<div class="ci-item">
								<img src="admin/<?php echo $navcartr['thumb']; ?>" width="70" alt=""/>
								<div class="ci-item-info">
									<h5><a href="single.php?id=<?php echo $navcartr['id']; ?>"><?php echo substr($navcartr['name'], 0 , 20); ?></a></h5>
									<p><?php echo $value['quantity']; ?> x $ <?php echo $navcartr['price']; ?>.00/-</p>
									<div class="ci-edit">
										<!-- <a href="#" class="edit fa fa-edit"></a> -->
										<a href="delcart.php?id=<?php echo $key; ?>" class="edit fa fa-trash"></a>
									</div>
								</div>
							</div>
							<?php 
							$total = $total + ($navcartr['price']*$value['quantity']);

							} ?>
							<div class="ci-total">Subtotal: $ <?php echo $total; ?>.00</div>
							<div class="cart-btn">
								<a href="cart.php">View Bag</a>
								<a href="checkout.php">Checkout</a>
							</div>
						</div>
					</div>
					<div class="s-search">
						<div class="search-box">
       					 <input type="text" autocomplete="off" placeholder="Search products..." />
        					<div class="result"></div>
    					</div>
					</div>
				</div>
			</div>
		</div>
	</header>