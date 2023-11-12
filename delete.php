<?php
    // code to delete row from plan
    // gets the parameters to delete plan data
    $id = $_GET['id'];
    $user = $_GET['user'];
    echo $id;
    echo $user;
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

    // deletes plan row using the id and username
    if(!empty($id) && !empty($user)) {
        $q = "DELETE FROM Plan WHERE id = ? and username = ?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("ss",  $id,$user);
        $st->execute();

    }
    // stays on the user page
    header("location:user.php");
    mysqli_close($cn);
?>
<br><br><br>
