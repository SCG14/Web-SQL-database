<?php
// code to delete review
// gets review id to delete
$id = $_GET['id'];
// connection params
$config = parse_ini_file ("config.ini");
$server = $config ["servername"];
$username = $config ["username"];
$password = $config ["password"];
$database = "scalvillo_DB";
// connect to db
$cn = mysqli_connect ( $server , $username , $password , $database);
// check connection
if (! $cn ) {
    die(" Connection failed : " . mysqli_connect_error ());
}

// deletes review row based on id
if(!empty($id)) {
    $q = "DELETE FROM Review WHERE review_id = ?";
    $st = $cn->stmt_init();
    $st->prepare($q);
    $st->bind_param("d",  $id);
    $st->execute();

}
// stays on the user page
header("location:user.php");
mysqli_close($cn);
?>
<br><br><br>

