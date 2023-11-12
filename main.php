<?php
    // main program with the initial display
    // session start to store username
    session_start();
    $_SESSION['user'] = "";
?>
<html>
    <body>
        <h1>TRAVEL BROWSER</h1>
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
        mysqli_close($cn);

        ?>

        <!-- options to use in the app -->
        <div>
            <div>
                <form action = "cities.php" method = "post">
                    <input type="submit" value="Cities">
                </form>
            </div>
            <div>
                <form action = "museums.php" method = "post">
                    <input type="submit" value="Museums">
                </form>
            </div>
            <div>
                <form action = "artpiece.php" method = "post">
                    <input type="submit" value="Art Pieces">
                </form>
            </div>
            <div>
                <form action = "analytics.php" method = "post">
                    <input type="submit" value="Analytics">
                </form>
            </div>
        </div>


        <div align="center">
            <label><strong>Enter username or enter a new one to create plans:</strong></label>
            <form action = "user.php" method = "post">

                <input type="text" name="session_value">

                <input type="submit" name ="set_session" value="Login">

            </form>
        </div>
    </body>

</html>


