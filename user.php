<?php
// gets the username from the session created that the user is saved on main
session_start();
if (isset($_POST['set_session'])) {
    $_SESSION['user'] = $_POST['session_value'];
}
$user = $_SESSION['user'];
?>
<!-- displays the user info with options to delete or update-->
<html>
    <!-- table format -->
    <style>
        table, th, td {
            border:1px solid black;
        }
    </style>
    <body>
        <h1 >USER</h1>
        <hr>
        <form action = "add.php" method = "post">
            <input type="hidden" name="user" value="<?php echo $user; ?>">
            <input type="submit" value="Add item">
        </form>
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

        echo "MY PLANS";
        // gets the plans to display them in the user page
        $q = "SELECT * FROM Plan WHERE username = ?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("s", $user);
        $st->execute();
        $result = $st->get_result();
        echo "<table style=\"width:50%\">";
        echo "<tr>";
        echo "<th>id</th>";
        echo "<th>name</th>";
        echo "<th>in</th>";
        echo "<th>type</th>";
        echo "<th>delete</th>";
        echo "</tr>";

        while ($record = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$record['id']."</td>";
            echo "<td>".$record['name']."</td>";
            echo "<td>".$record['inherits']."</td>";
            echo "<td>".$record['type']."</td>";
            echo "<td><a href='delete.php?id=".$record['id']."&user=".$record['username']."'>delete</a></td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "<br><br><br>";
        // displays the reviews that the user created
        echo "REVIEWS";

        $q = "SELECT * FROM Review JOIN Museum USING(museum_id) WHERE author = ?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("s", $user);
        $st->execute();
        $result = $st->get_result();
        echo "<table style=\"width:50%\">";
        echo "<tr>";
        echo "<th>author</th>";
        echo "<th>rating</th>";
        echo "<th>museum_id</th>";
        echo "<th>museum_name</th>";
        echo "<th>update</th>";
        echo "<th>delete</th>";
        echo "</tr>";

        while ($record = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$record['author']."</td>";
            echo "<td>".$record['rating']."</td>";
            echo "<td>".$record['museum_id']."</td>";
            echo "<td>".$record['museum_name']."</td>";
            // update and delete review buttons in each row
            echo "<td><a href='update_review.php?id=".$record['review_id']."'>update</a></td>";
            echo "<td><a href='delete_review.php?id=".$record['review_id']."'>delete</a></td>";
            echo "</tr>";
        }
        echo "</table>";


        ?> <br><br><br>
        <!-- dropdown to select a museum to create a review-->
        <h3>NEW REVIEW</h3>
        <form action = "review.php" method = "POST">
            <select name="museum">
                <?php
                $sql = mysqli_query($cn, "SELECT museum_id, museum_name FROM Museum");
                while ($row = $sql->fetch_assoc()){
                    echo "<option value='" . $row['museum_id'] . "'>" . $row['museum_name'] . "</option>";
                }
                ?>
            </select>
            <input type="submit" value="Create review">
        </form>
        <br><br><br>
        <?php mysqli_close($cn);
        ?>
        <button onclick="window.location='main.php';">Go Back</button>
    </body>
</html>