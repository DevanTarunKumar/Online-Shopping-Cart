<?php
	session_start();
session_regenerate_id();
	require_once '../config/connect.php';
	if(!isset($_SESSION['admin']) & empty($_SESSION['admin'])){
		header('location: login.php');
	}

    //checking if the url gives the id then storing it in $ID variable, if no id redirect to categories.php page back
	if(isset($_GET) & !empty($_GET)){
		$id = intval($_GET['id']);
	}else{
		header('location: categories.php');
	}
    //here we update the catogeries with the newly entered values into the DB
	if(isset($_POST) & !empty($_POST)){
		$id = intval($connection, $_POST['id']);
		$name = mysqli_real_escape_string($connection, $_POST['categoryname']);
		$sql = "UPDATE category SET name = '$name' WHERE id=$id";
		$res = mysqli_query($connection, $sql);
		if($res){
			$smsg = "Category Updated";
		}else{
			$fmsg = "Failed Update Category";
		}
	}
?>
<?php include 'inc/header.php'; ?>
<?php include 'inc/nav.php'; ?>
	
<section id="content">
	<div class="content-blog">
		<div class="container">
		<?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
		<?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
			<form method="post">
			  <div class="form-group">
			    <label for="Productname">Category Name</label>
			    <?php 	
			        //select the catagory with given ID
					$sql = "SELECT * FROM category WHERE id=$id";
					$res = mysqli_query($connection, $sql); 
					$r = mysqli_fetch_assoc($res); 
				?>
				<input type="hidden" name="id" value="<?php echo htmlentities($_GET['id']); ?>">
			    <input type="text" class="form-control" name="categoryname" id="Categoryname" placeholder="Category Name" value="<?php echo $r['name'];  ?>">
			  </div>
			  
			  <button type="submit" class="btn btn-default">Submit</button>
			</form>
			
		</div>
	</div>

</section>
<?php include 'inc/footer.php' ?>