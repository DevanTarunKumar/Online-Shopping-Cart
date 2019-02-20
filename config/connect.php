<?php
$connection = mysqli_connect('student-db.cse.unt.edu', 'jg0709', 'JungRaFuki@15!', 'jg0709');
if(!$connection){
	echo "Error : Unable to connect to MySQL.", PHP_EOL;
	
	echo "Debugging errono:",mysql_connect_errno(), PHP_EOL;
	echo "Debugging errono:",mysql_connect_errno(), PHP_EOL;
	exit;

}
?>