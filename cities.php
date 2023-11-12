<html>
    <body>
        <!-- displays the info of the cities with a dropdown menu-->
        <h1 >BROWSING CITY</h1>
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
        ?>

        <!-- dropdown to select city-->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <!-- menu dropdown, retrieves countries from database -->
            <select id="selectype" name="city">
                <?php
                $sql = mysqli_query($cn, "SELECT city_name FROM City");
                while ($row = $sql->fetch_assoc()){
                    echo "<option value='" . $row['city_name'] . "'>" . $row['city_name'] . "</option>";
                }
                ?>
            </select>
            <input type="submit" value="Submit">
        </form>

        <?php
        // gets the info of the city to display
        if(isset($_POST['city'])) {
            $city_name = $_POST['city'];
        }
        $q = "SELECT * FROM City WHERE city_name = ?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("s", $city_name);
        $st->execute();
        $st->bind_result ($city_id , $city_name , $city_population, $country, $language );
        while ($st->fetch()) {
            echo "<li > City Code:  <strong> " . $city_id . "</strong> </li >\n";
            echo "<li > City Name:  <strong> " . $city_name . "</strong> </li >\n";
            echo "<li > City Population:  <strong> " . $city_population . "</strong> </li >\n";
            echo "<li > City's Country:  <strong> " . $country . "</strong> </li >\n";
            echo "<li > City language:  <strong> " . $language . "</strong> </li >\n";
        }
        mysqli_close($cn);
        ?>
        <br><br><br>
        <button onclick="window.location='main.php';">Go Back</button>
    </body>
</html>