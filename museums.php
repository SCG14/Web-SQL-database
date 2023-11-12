<html>
    <body>
        <h1 >BROWSING MUSEUM</h1>
        <hr>
        <!-- displays the info of the museums with a dropdown menu-->
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
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <!-- menu dropdown, retrieves museums from database -->
            <select id="selectype" name="museum">
                <?php
                $sql = mysqli_query($cn, "SELECT museum_name FROM Museum");
                while ($row = $sql->fetch_assoc()){
                    echo "<option value='" . $row['museum_name'] . "'>" . $row['museum_name'] . "</option>";
                }
                ?>
            </select>
            <input type="submit" value="Submit">
        </form>

        <?php
        // gets the info of the museum to display
        if(isset($_POST['museum'])) {
            $museum_name = $_POST['museum'];
        }
        $q = "SELECT * FROM Museum WHERE museum_name = ?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("s", $museum_name);
        $st->execute();
        $st->bind_result ($museum_id , $museum_name , $address, $museum_type, $city_id );
        while ($st->fetch()) {
            echo "<li > Museum ID:  <strong> " . $museum_id . "</strong> </li >\n";
            echo "<li > Museum Name:  <strong> " . $museum_name . "</strong> </li >\n";
            echo "<li > Address:  <strong> " . $address . "</strong> </li >\n";
            echo "<li > Museum Type:  <strong> " . $museum_type . "</strong> </li >\n";
            echo "<li > Location:  <strong> " . $city_id . "</strong> </li >\n";
        }
        mysqli_close($cn);
        ?>
        <br><br><br>
        <button onclick="window.location='main.php';">Go Back</button>
    </body>
</html>

