<?php 
session_start();
session_regenerate_id();
if(!isset($_SESSION['customer']) & empty($_SESSION['customer'])){
		//header('location: login.php');
}
require_once 'config/connect.php';
include 'inc/header.php'; 
include 'inc/nav.php';  
error_reporting(0);
@ini_set('display_errors', 0);
if(!isset($_SESSION['customer']) & empty($_SESSION['customer'])){
		header('location: login.php');}
if(isset($_GET['id']) & !empty($_GET['id'])){
	//Get the products full details whoes id is passed through url 
	$id = intval($_GET['id']);
	$prodsql = "SELECT * FROM products WHERE id=$id";
	$prodres = mysqli_query($connection, $prodsql);
	$prodr = mysqli_fetch_assoc($prodres);
}else
{
	//Will redirect back to index if there is no ID 
	header('location: index.php');
}

?>

	
	<!-- SHOP CONTENT -->
	<section id="content">
		<div class="content-blog">
			<div class="container">
				<div class="row">
					<div class="page_header text-center">
						<h2>UNT Shopping Cart</h2>
						<p>Spend less. Buy  more.</p>
					</div>

				
					<div class="col-md-10 col-md-offset-1">

					<div class="row">
						<div class="col-md-5">
							<div class="gal-wrap">
								<div id="gal-slider" class="flexslider">
									<ul class="slides">
<!--Image is retrieved from DB-->
										<li><img src="admin/<?php echo $prodr['thumb']; ?>" class="img-responsive" alt=""/></li>
	       							</ul>
								</div>
								<ul class="gal-nav">
									<li>
										<div>
											<img src="admin/<?php echo $prodr['thumb']; ?>" class="img-responsive" alt=""/>
										</div>
									</li>
									
								</ul>
								<div class="clearfix"></div>
							
							</div>
						</div>
						<div class="col-md-7 product-single">
<!--Product title, price and description are updated-->
							<h2 class="product-single-title no-margin"><?php echo $prodr['name']; ?></h2>
							<div class="space10"></div>
							<div class="p-price">$ <?php echo $prodr['price']; ?>.00</div>
							<p><?php echo $prodr['description']; ?></p>
<!--Setting the action here to redirect page to addtocart.php-->							
							<form method="Get" action="addtocart.php">
							<div class="product-quantity">
<!--Out of stock displayed initialy on page-->								
								<?php if(intval($prodr['quantity']) <= 0){
?>
						 <div class="alert alert-danger" role="alert"> <?php echo "Out of Stock"; ?> </div>

						 <?php }

								 ?>
<!--Out of stock exception is handled -->								
		<?php if(isset($_GET['message'])){
					if($_GET['message'] == 1){
						 ?>
						 <div class="alert alert-danger" role="alert"> <?php echo "Out of Stock"; ?> </div>

						 <?php }
                    else if ($_GET['message'] == 2) 
                    {
                    	?>
<!--Negative input  exception is handled -->	
						 <div class="alert alert-danger" role="alert"> <?php echo "Please include valid Input"; ?> </div>

                    <?php }
		 
		else if ($_GET['message'] == 6) 
                    {
                    	?>
<!--Negative input  exception is handled -->	
						 <div class="alert alert-danger" role="alert"> <?php echo "Only below 20 quantities are allowed"; ?> </div>

                    <?php }

                    else if ($_GET['message'] == 3) 
                    {
                    	?>
<!--Quantity greater than availability exception is handled -->	
						 <div class="alert alert-danger" role="alert"> <?php echo "Only ".$prodr['quantity']." items are available. "; ?> </div>

                    <?php }



						  }?>		
<!--IMP here we set both quantity and id of product to get reflected in number of items in cart in addtocart.php-->							
                                    <span>Quantity:</span> 		
									<?php $_SESSION['cardaddId'] = $prodr['id']?>					
									<input type="text" name="quant" placeholder="1">							
							</div>
							<div class="shop-btn-wrap">
							<input type="submit" class="button btn-small" value="Add to Cart">
							</div>
							</form>
							<div class="product-meta">
								<span>Categories:
                                <?php 
                                //retriving category of the product with catid
								$prodcatsql = "SELECT * FROM category WHERE id={$prodr['catid']}"; 
								$prodcatres = mysqli_query($connection, $prodcatsql);
								$prodcatr = mysqli_fetch_assoc($prodcatres);
								?>
								<!--updating the category name and linking it with category id -->
								 <a href="index.php?id=<?php echo $prodcatr['id']; ?>"><?php echo $prodcatr['name']; ?></a></span><br>
								
							</div>
						</div>
					</div>
					<div class="clearfix space30"></div>
					
					<div class="space30"></div>
					<div class="related-products">
						<h4 class="heading">Related Products</h4>
						<hr>
						<div class="row">
							<div id="shop-mason" class="shop-mason-3col">
								<?php
								//Select the related products with same catid and which is not same product
								$relsql = "SELECT * FROM products WHERE id != $id and catid ={$prodr['catid']} ORDER BY rand()  LIMIT 3";
								$relres = mysqli_query($connection, $relsql);
								while ($relr = mysqli_fetch_assoc($relres)) {
								?>
								<div class="sm-item isotope-item">
									<div class="product">
										<div class="product-thumb">
											<img src="admin/<?php echo $relr['thumb']; ?>" class="img-responsive" alt="">
											<div class="product-overlay">
												<span>
												<a href="single.php?id=<?php echo $relr['id']; ?>" class="fa fa-link"></a>
												<a href="single.php?id=<?php echo $relr['id']; ?>" class="fa fa-shopping-cart"></a>
												</span>					
											</div>
										</div>
										
										<h2 class="product-title"><a href="single.php?id=<?php echo $relr['id']; ?>"><?php echo $relr['name']; ?></a></h2>
										<div class="product-price">$ <?php echo $relr['price']; ?>.00<span></span></div>
									</div>
								</div>
								<?php } ?>
							</div>
					
						</div>
					</div>
					
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<div class="clearfix space70"></div>
	<?php include 'inc/footer.php' ?> 
	