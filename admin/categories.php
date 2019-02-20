<?php
	session_start();
session_regenerate_id();
	require_once '../config/connect.php';
	//rerouting the user back to login page if email is not set or authentication not happend
	if(!isset($_SESSION['admin']) & empty($_SESSION['admin'])){
		header('location: login.php');
	}
?>
	<!-- HEADER -->
<?php include 'inc/header.php' ?>
<?php include 'inc/nav.php' ?>

	
	<!-- SHOP CONTENT -->
<section id="content">
	<div class="content-blog">
		<div class="container">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Category Name</th>
						<th>Operations</th>
					</tr>
				</thead>
				<tbody>

					<?php 	
					//selecting all the categories into pointer and running the while loop into the table
					$sql = "SELECT * FROM category";
					$res = mysqli_query($connection, $sql); 
					while ($r = mysqli_fetch_assoc($res)) {
				?>
					<tr>
						<th scope="row"><?php echo $r['id']; ?></th>
						<td><?php echo $r['name']; ?></td>
						<td><a href="editcategory.php?id=<?php echo $r['id']; ?>">Edit</a> | <a href="delcategory.php?id=<?php echo $r['id']; ?>">Delete</a></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			
		</div>
	</div>

</section>
	
<!-- FOOTER -->
<?php include 'inc/footer.php' ?>