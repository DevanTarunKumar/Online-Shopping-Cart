<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
require_once 'config/connect.php';

if(!$connection){
	echo "Error : Unable to connect to MySQL.", PHP_EOL;
	
	echo "Debugging errono:",mysql_connect_errno(), PHP_EOL;
	echo "Debugging errono:",mysql_connect_errno(), PHP_EOL;
	exit;

}

 
// Check connection
if($connection=== false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

if (!$connection)
{
    $error = mysqli_connect_error();
    $errno = mysqli_connect_errno();
    print "$errno: $error\n";
    exit();
}

//if(isset($_REQUEST['term'])){

$stmt = $connection->prepare("SELECT * FROM products WHERE name like ?");
$param_term = $_REQUEST['term'] . '%';
$stmt->bind_param("s", $param_term);

    if ( $stmt === false ) {

        # Throw Exception (error message)
        throw new Exception("Error, could not process data submitted.");

    }
$stmt->execute();
 if ( $stmt === false ) {

        # Throw Exception (error message)
        throw new Exception("Error, count not execute database query.");

    }
$RESULT = get_result( $stmt);
if($RESULT){
while ( $DATA = array_shift( $RESULT ) ) {
    // Do stuff with the data
echo "<a href='single.php?id=".$DATA["id"]."'><p>" . $DATA["name"] . "</p></a>";
}
}
else{
                echo "<p>No matches found</p>";
            }

function get_result( $Statement ) {
    $RESULT = array();

    $Statement->store_result();
    for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
        $Metadata = $Statement->result_metadata();
        $PARAMS = array();
        while ( $Field = $Metadata->fetch_field() ) {
            $PARAMS[] = &$RESULT[ $i ][ $Field->name ];
        }
        call_user_func_array( array( $Statement, 'bind_result' ), $PARAMS );
        $Statement->fetch();
    }
    return $RESULT;
}
//}
mysqli_close($connection);

?>