<!--authentication-->
<?php
	session_start();
session_regenerate_id();
	require_once '../config/connect.php';
	//rerouting the user back to login page if email is not set or authentication not happend
	if(!isset($_SESSION['admin']) & empty($_SESSION['admin'])){
		header('location: login.php');
	}
//check if something is posted and if so then insert them into DB thorugh insert categoryname
	if(isset($_POST) & !empty($_POST))
	{
		$name = mysqli_real_escape_string($connection, $_POST['categoryname']);
		$sql = "INSERT INTO category (name) VALUES ('$name')";
		$res = mysqli_query($connection, $sql);
		if($res){
			$smsg = "Category Added";
		}else{
			$fmsg = "Failed Add Category";
		}
	}
?>

<!-- HEADER -->
<?php include 'inc/header.php' ?>
<?php include 'inc/nav.php' ?>

	<!-- SHOP CONTENT -->
<section id="content">
	<div class="content-blog">
		<div class="container">
		<?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
		<?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
			<form method="post">
			  <div class="form-group">
			    <label for="Productname">Category Name</label>
			    <input type="text" class="form-control" name="categoryname" id="Categoryname" placeholder="Category Name">
			  </div>
			  
			  <button type="submit" class="btn btn-default">Submit</button>
			</form>
			
		</div>
	</div>

</section>
	
	
	<!-- FOOTER -->
<?php include 'inc/footer.php' ?>