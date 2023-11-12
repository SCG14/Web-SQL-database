<?php
session_start();
$user = $_SESSION['user'];

?>
<html>
    <!-- code to write review-->
    <body>
        <h1 >REVIEW</h1>
        <hr>

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

        // gets the museum id to review
        if(isset($_POST['museum'])) {
            $id = $_POST['museum'];

            echo "Creating review for museum:";
            echo $id;
        }
        ?>
        <br><br><br>
        <form method="post" action="">
            <!-- menu dropdown to select the number of stars to give to that museum -->
            <select id="selectype" name="rating">
                <option value="1">1 star</option>
                <option value="2">2 star</option>
                <option value="3">3 star</option>
                <option value="4">4 star</option>
                <option value="5">5 star</option>
            </select>
            <input type="hidden" value="<?php echo $id ?>" name="id" >
            <input type="submit" name ="addR" value="Add Review">
        </form>
        <?php
        // gets the info of the review to create
        if(isset($_POST['addR'])) {

            $rating = $_POST['rating'];
            $id = $_POST['id'];
            $r_id = rand(1, 100000000);
            echo $r_id;
            echo $id;
            echo $user;

            $q = "INSERT INTO Review VALUES (?,?,?,?)";
            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("dsss", $r_id, $user, $rating, $id);
            $st->execute();

            echo "<p>Review Added Successfully</p>";
            echo $q;
            mysqli_close($cn);

            header("location:user.php");
        } ?>
    </body>
</html>
