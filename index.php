<?php 
session_start();
session_regenerate_id();
require_once 'config/connect.php';
error_reporting(0);
@ini_set('display_errors', 0);

//if(!isset($_SESSION['customer']) & empty($_SESSION['customer'])){
//		header('location: login.php');}
include 'inc/header.php'; ?>
<!--Navagation-->
<?php include 'inc/nav.php'; ?>
	
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
						<div class="row">
							<div id="shop-mason" class="shop-mason-4col">
						    <!--we get all the products from the DB and display them looping-->

							<?php 
								$sql = "SELECT * FROM products";
								//here we get the category Id from the navgation URL and display only books related to category selcted 
								if(isset($_GET['id']) & !empty($_GET['id']))
								{
									$id = intval($_GET['id']);
									$sql .= " WHERE catid=$id";
								}
								$res = mysqli_query($connection, $sql);
								//while loop is used to iterate and display all items
								while($r = mysqli_fetch_assoc($res)){
							?>
								<div class="sm-item isotope-item">
									<div class="product">
										<div class="product-thumb">
											<!--The path of the images are taken from the DB-->
											<img src="admin/<?php echo $r['thumb']; ?>" class="img-responsive" width="250px" alt="">
											<div class="product-overlay">
												<span>
												<a href="single.php?id=<?php echo $r['id']; ?>" class="fa fa-link"></a>
												<a href="addtocart.php?id=<?php echo $r['id']; ?>" class="fa fa-shopping-cart"></a>
												</span>					
											</div>
										</div>
										
										<!--Product Title and Product Price are given here -->
										<h2 class="product-title"><a href="single.php?id=<?php echo $r['id']; ?>"><?php echo $r['name']; ?></a></h2>
										<div class="product-price">$ <?php echo $r['price']; ?>.00<span></span></div>
									</div>
								</div>
							<?php } ?>

								
							</div>
						</div>
						<div class="clearfix"></div>
						<!-- Pagination -->
						<div class="page_nav">
							<a href=""><i class="fa fa-angle-left"></i></a>
							<a href="" class="active">1</a>
							<a href="">2</a>
							<a href="">3</a>
							<a class="no-active">...</a>
							<a href="">9</a>
							<a href=""><i class="fa fa-angle-right"></i></a>
						</div>
						<!-- End Pagination -->
					</div>
				</div>
			</div>
		</div>
	</section>
<?php include 'inc/footer.php' ?>
