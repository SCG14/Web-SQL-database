<?php
    session_start();
    $user = $_SESSION['user'];
    $_SESSION['cityNAME'] = "";
?>
<!-- add page that is able to add a museum, art or city and keep adding-->
<html>

    <body>
        <h1 >ADD</h1>
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

        // gets the values from the previous page, name, gdp and inflation

        ?>



        <form method="post" action="">
            <!-- menu dropdown, retrieves countries from database -->
            <select id="selectype" name="city">
                <?php
                $sql = mysqli_query($cn, "SELECT city_name FROM City");
                while ($row = $sql->fetch_assoc()){
                    echo "<option value='" . $row['city_name'] . "'>" . $row['city_name'] . "</option>";
                }
                ?>
            </select>

            <input type="submit" name ="addC" value="Add City">
        </form>
        <?php
        // gets the info of the country to display
        if(isset($_POST['addC'])) {

            $city_name = $_POST['city'];

            $q = "SELECT id FROM Plan WHERE name = ? and username = ?";

            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("ss", $city_name, $user);
            $st->execute();
            $st->bind_result($code);
            $row = $st->fetch();
            // if code is being used then it will not insert
            if ($row) {
                header("Refresh:0");
                echo "<script type='text/javascript'>alert('Item already in plan');</script>";
            }
            else {
                $q = "SELECT city_id, city_name, country FROM City WHERE city_name = ?";
                $st = $cn->stmt_init();
                $st->prepare($q);
                $st->bind_param("s", $city_name);
                $st->execute();
                $st->bind_result($city_id, $city_name, $country);
                $type = 'city';
                $st->fetch();
                $q = "INSERT INTO Plan VALUES (?,?,?,?,?)";
                $st = $cn->stmt_init();
                $st->prepare($q);
                $st->bind_param("sssss", $user, $city_id, $city_name, $country, $type);
                $st->execute();
                echo $city_name;
                echo "<p>Country Added Successfully</p>";
            }
        }

            ?>

        <form method="post" action="">
            <!-- menu dropdown, retrieves countries from database -->
            <select id="selectype" name="museum">
                <?php
                $sql = mysqli_query($cn, "SELECT museum_name FROM Museum");
                while ($row = $sql->fetch_assoc()){
                    echo "<option value='" . $row['museum_name'] . "'>" . $row['museum_name'] . "</option>";
                }
                ?>
            </select>

            <input type="submit" name ="addM" value="Add Museum">
        </form>
        <?php
        // gets the info of the country to display
        if(isset($_POST['addM'])) {

            $museum_name = $_POST['museum'];
            echo $museum_name;

        $q = "SELECT id FROM Plan WHERE name = ? and username = ?";

        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("ss", $museum_name, $user);
        $st->execute();
        $st->bind_result($code);
        $row = $st->fetch();
        // if code is being used then it will not insert
        if ($row) {
            header("Refresh:0");
            echo "<script type='text/javascript'>alert('Item already in plan');</script>";}
        else {
            $q = "SELECT museum_id, museum_name, city_name FROM Museum JOIN City USING(city_id) WHERE museum_name = ?";
            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("s", $museum_name);
            $st->execute();
            $st->bind_result($museum_id, $museum_name, $city_name);
            $type = 'museum';
            $st->fetch();
            $q = "INSERT INTO Plan VALUES (?,?,?,?,?)";
            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("sssss", $user, $museum_id, $museum_name, $city_name, $type);
            $st->execute();

            echo "<p>Museum Added Successfully</p>";
        }
        } ?>


        <form method="post" action="">
            <!-- menu dropdown, retrieves countries from database -->
            <select id="selectype" name="art">
                <?php
                $sql = mysqli_query($cn, "SELECT painting_name FROM ArtPiece");
                while ($row = $sql->fetch_assoc()){
                    echo "<option value='" . $row['painting_name'] . "'>" . $row['painting_name'] . "</option>";
                }
                ?>
            </select>

            <input type="submit" name ="add" value="Add Art Piece">
        </form>
        <?php
        // gets the info of the country to display
        if(isset($_POST['add'])) {

            $painting_name = $_POST['art'];

        $q = "SELECT id FROM Plan WHERE name = ? and username = ?";

        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("ss", $painting_name, $user);
        $st->execute();
        $st->bind_result($code);
        $row = $st->fetch();
        // if code is being used then it will not insert
        if ($row) {
            echo "<script type='text/javascript'>alert('Item already in plan');</script>";}
        else {
            $q = "SELECT piece_id, painting_name, museum_name FROM ArtPiece JOIN Museum USING(museum_id) WHERE painting_name = ?";
            echo $painting_name;
            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("s", $painting_name);
            $st->execute();
            $st->bind_result($museum_id, $painting_name, $museum_name);
            $type = 'art piece';
            $st->fetch();
            $q = "INSERT INTO Plan VALUES (?,?,?,?,?)";
            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("sssss",  $user,$museum_id, $painting_name, $museum_name, $type);
            $st->execute();

            echo "<p>Art Piece Added Successfully</p>";
        }
        }mysqli_close($cn);?>
        <br><br><br>
        <button onclick="window.location='user.php';">Go Back</button>
    </body>
</html>