<?php
session_start();
$user = $_SESSION['user'];

?>
<html>

<body>
<h1 >UPDATE</h1>
<hr>
<!-- code to update review-->
<?php
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

// gets the review to update
if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $q = "SELECT review_id, author, rating, museum_id, museum_name FROM Review JOIN Museum USING(museum_id) WHERE review_id = ?";
    $st = $cn->stmt_init();
    $st->prepare($q);
    $st->bind_param("d", $id);
    $st->execute();
    $st->bind_result ($id , $author , $rating, $museum_id, $museum_name);
    $st->fetch();
    echo "Update review of museum: ";
    echo $museum_name;

}
?>
<br><br>
<form method="post" action="">
    <!-- menu dropdown to update rating -->
    <select id="selectype" name="rating">
        <option value="1">1 star</option>
        <option value="2">2 star</option>
        <option value="3">3 star</option>
        <option value="4">4 star</option>
        <option value="5">5 star</option>
    </select>
    <input type="hidden" value="<?php echo $id ?>" name="id" >
    <input type="submit" name ="addR" value="Update Review">
</form>
<?php
// updates reviews if changed
if(isset($_POST['addR'])) {

    $rating = $_POST['rating'];
    $id = $_POST['id'];
    echo $id;
    echo $user;

    $q = "UPDATE Review SET rating = ? WHERE review_id = ?";
    $st = $cn->stmt_init();
    $st->prepare($q);
    $st->bind_param("sd",$rating, $id);
    $st->execute();
    mysqli_close($cn);

    header("location:user.php");
} ?>
<button onclick="window.location='user.php';">Cancel</button>

</body></html>

