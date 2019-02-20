<?php 
session_start();
session_regenerate_id();
require_once 'config/connect.php';
include 'inc/header.php';
include 'inc/nav.php' ?>

<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  
	
	<!-- SHOP CONTENT -->
	<section id="content">
		<div class="content-blog">
			<div class="container">
				<div class="row">
					<div class="page_header text-center">
						<h2>Shop - Account</h2>
						<p>Get the best deals</p>
					</div>
					<div class="col-md-12">
				<div class="row shop-login">
				<div class="col-md-6">
					<div class="box-content">
						<h3 class="heading text-center">Sign In</h3>
						<div class="clearfix space40"></div>
						<!--Error handling uding get messgae from url-->
						<?php if(isset($_GET['message'])){
								if($_GET['message'] == 1){
						 ?><div class="alert alert-danger" role="alert"> <?php echo "Invalid Login Credentials"; ?> </div>

						 <?php } }?>
						<!--forms method is set and action is set to redirect towards loginprocess.php to check if user exists -->
						<form class="logregform" method="post" action ="loginprocess.php">
						<p><span class="error">* Required field.</span></p>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<label><b>Username or E-mail Address</b><span class="error">*</span></label>
										<!--updated the name of the field -->
										<input type="email" value="" name="email" class="form-control">
									</div>
								</div>
							</div>
							<div class="clearfix space20"></div>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<!--<a class="pull-right" href="#">(Lost Password?)</a>-->
										<label><b>Password</b><span class="error">*</span></label>
										<input type="password" value="" name="password" class="form-control">
									</div>
								</div>
							</div>
							<div class="clearfix space20"></div>
							<div class="row">
								<div class="col-md-6">
									<span class="remember-box checkbox">
									<!--<label for="rememberme">
									<input type="checkbox" id="rememberme" name="rememberme">Remember Me
									</label>-->
									</span>
								</div>
								<div class="col-md-6">
									<button type="submit" class="button btn-md pull-right">Login</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="col-md-6">
					<div class="box-content">
						<h3 class="heading text-center">Sign Up</h3>
						<div class="clearfix space40"></div>
						<!--Error handling uding get messgae from url-->
						<?php if(isset($_GET['message'])){ 
								if($_GET['message'] == 2){
							?><div class="alert alert-danger" role="alert"> <?php echo "Failed to Register"; ?> </div>
							<?php } 
							if($_GET['message'] == 3){
							?><div class="alert alert-danger" role="alert"> <?php echo "Email Address already exists"; ?> </div>
							<?php } 
							if($_GET['message'] == 4){
							?><div class="alert alert-danger" role="alert"> <?php echo "email address should not be empty"; ?> </div>
							<?php } 
								if($_GET['message'] == 6){
							?><div class="alert alert-danger" role="alert"> <?php echo "Password Must contain 8 characters with at least 1 number, 1 Uppercase and 1 lowercase"; ?> </div>
							<?php }
								if($_GET['message'] == 7){
							?><div class="alert alert-danger" role="alert"> <?php echo "Please enter First Name"; ?> </div>
							<?php }
								if($_GET['message'] == 8){
							?><div class="alert alert-danger" role="alert"> <?php echo "Please enter last name"; ?> </div>
							<?php }


								if($_GET['message'] == 5){
							?><div class="alert alert-danger" role="alert"> <?php echo "password doesn't match"; ?> </div>
							<?php }}?>
						<!--forms method is set and action is set to redirect towards RegisterProcess.php to check if user exists -->
						<form class="logregform" method="post" action="registerprocess.php">
						<p><span class="error">* Required field.</span></p>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<label><b>E-mail Address</b><span class="error">*</span></label>
										<input type="email" name="email" value="" class="form-control">
										
									</div>
								</div>
							</div>
							<div class="clearfix space20"></div>
							<div class="row">
								<div class="form-group">
									<div class="col-md-6">
										<label><b>First Name</b><span class="error">*</span></label>
										<input type="text" name="fname" value="" class="form-control">
                                      </div>
									<div class="col-md-6">
										<label><b>Last Name</b><span class="error">*</span></label>
										<input type="text" name="lname" value="" class="form-control">
									</div>
								</div>
							</div>
							<div class="clearfix space20"></div>
							<div class="row">
								<div class="form-group">
									<div class="col-md-6">
										<label><b>Password</b><span class="error">*</span></label>
										<input type="password" name="password" value="" class="form-control">
                                      </div>
									<div class="col-md-6">
										<label><b>Re-enter Password</b><span class="error">*</span></label>
										<input type="password" name="passwordagain" value="" class="form-control">
									</div>
								</div>
<label><span class="error">*Password must be atleast 8 characters long and should have atleast a numeric, an uppercase and a lowercase</span></label>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="space20"></div>
									<button type="submit" class="button btn-md pull-right">Register</button>
								</div>
							</div>
						</form>
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