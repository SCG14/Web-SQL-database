<html>
    <body>
        <h1 >BROWSING MUSEUM</h1>
        <hr>
        <!-- displays the info of the museums when clicked in the faceted search-->
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

        // gets the info from the faceted search to display and displays the info
        if(isset($_GET['museum'])) {
            $museum_name = $_GET['museum'];
        }
        $q = "SELECT * FROM Museum WHERE museum_name = ?";
        echo $museum_name;
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
        <!-- returns faceted search -->
        <button onclick="history.go(-1);">Go Back</button>
    </body>
</html>
