<html>
    <body>
    <!-- page that displays a faceted search to look for art pieces    -->
        <h1 >BROWSING ART PIECE</h1>
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

        <!-- dropdowns to make faceted search -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <!-- menu dropdown to select by author if desired -->
            <select id="selectype1" name="author">
                <option value="none">--select author--</option>
                <?php
                $sql = mysqli_query($cn, "SELECT DISTINCT author FROM ArtPiece");
                while ($row = $sql->fetch_assoc()){
                    echo "<option value='" . $row['author'] . "'>" . $row['author'] . "</option>";
                }
                ?>
            </select>

            <!-- menu dropdown to select by painting type if desired -->
            <select id="selectype2" name="type">
                <option value="none">--select art type--</option>
                <?php
                $sql = mysqli_query($cn, "SELECT DISTINCT painting_type FROM ArtPiece");
                while ($row = $sql->fetch_assoc()){
                    echo "<option value='" . $row['painting_type'] . "'>" . $row['painting_type'] . "</option>";
                }
                ?>
            </select>

            <!-- menu dropdown to select a range of years -->
            <select id="selectype3" name="age">
                <option value="none">--select creation year--</option>
                <option value="1">0-400</option>
                <option value="2">401-800</option>
                <option value="3">801-1200</option>
                <option value="4">1201-1600</option>
                <option value="5">1601-2022</option>
            </select>

            <!-- menu dropdown to select countries where paintings are -->
            <select id="selectype4" name="country">
                <option value="none">--select country painting is in--</option>
                <?php
                $sql = mysqli_query($cn, "SELECT DISTINCT country FROM ArtPiece JOIN Museum USING(museum_id) JOIN City USING(city_id)");
                while ($row = $sql->fetch_assoc()){
                    echo "<option value='" . $row['country'] . "'>" . $row['country'] . "</option>";
                }
                ?>
            </select>
            <input type="submit" value="Submit">
        </form>

        <?php
        // executes the faceted search
        if(isset($_POST['country'])) {
            $author = $_POST['author'];
            $type = $_POST['type'];
            $age = $_POST['age'];
            $country = $_POST['country'];
            $display_message = "";
            $q = "SELECT piece_id, painting_name, author, painting_type, creation_year, museum_name, country FROM ArtPiece JOIN Museum USING(museum_id) JOIN City USING(city_id)";
            $array_of_values = array();
            $check = false;
            $types = "";

            // checks if a filter was selected and adds it to the query if selected
            if($author != 'none') {
                $array_of_values[] = $author;
                if (!$check) {
                    $q .= " WHERE author = ?";
                    $check = true;
                }
                else {
                    $q .= " and author = ?";
                }
                $types .= "s";
                $display_message .= "Art created by ";
                $display_message .= $author;
            }

            if($type != 'none') {
                $array_of_values[] = $type;
                if (!$check) {
                    $q .= " WHERE painting_type = ?";
                    $check = true;
                }
                else {
                    $q .= " and painting_type = ?";
                }
                $types .= "s";
                $display_message .= "Art that is ";
                $display_message .= $type;
            }

            if($age != 'none') {
                $min_age = 0;
                $max_age = 0;
                if($age == 1) {
                    $min_age = 0;
                    $max_age = 400;
                }
                elseif ($age == 2) {
                    $min_age = 401;
                    $max_age = 800;
                }
                elseif ($age == 3) {
                    $min_age = 801;
                    $max_age = 1200;
                }
                elseif ($age == 4) {
                    $min_age = 1201;
                    $max_age = 1600;
                }
                elseif ($age == 5) {
                    $min_age = 1601;
                    $max_age = 2022;
                }
                $array_of_values[] = $min_age;
                $array_of_values[] = $max_age;
                if (!$check) {
                    $q .= " WHERE creation_year >= ? and creation_year <= ?";
                    $check = true;
                }
                else {
                    $q .= " and creation_year >= ? and creation_year <= ?";
                }
                $types .= "ss";
                $display_message .= "Art created before ";
                $display_message .= $max_age;
                $display_message .= "And after ";
                $display_message .= $min_age;
            }

            if($country != 'none') {
                $array_of_values[] = $country;
                if (!$check) {
                    $q .= " WHERE country = ?";
                    $check = true;
                }
                else {
                    $q .= " and country = ?";
                }
                $display_message .= "Art currently in ";
                $display_message .= $country;
                $types .= "s";
            }
            $st = $cn->stmt_init();
            $st->prepare($q);
            echo $display_message;
            echo "<br>";
            // query executed
            if(strlen($types) > 0) {
                $st->bind_param($types, ...$array_of_values);
            }
            $st->execute();
            $result = $st->get_result();
            $st->bind_result($piece_id, $painting_name, $author, $painting_type, $creation_year, $museum_name, $country);
            while ($record = mysqli_fetch_assoc($result)) {
                echo "<li > Piece id:  <strong> " . $record['piece_id'] . "</strong> </li >\n";
                echo "<li > Art Piece Name:  <strong> " . $record['painting_name'] . "</strong> </li >\n";
                echo "<li > Author:  <strong> " . $record['author'] . "</strong> </li >\n";
                echo "<li > Art Piece Type:  <strong> " . $record['painting_type'] . "</strong> </li >\n";
                echo "<li > Creation Year:  <strong> " . $record['creation_year'] . "</strong> </li >\n";
                echo "<li ><a href='museum_search.php?museum=".$record['museum_name']."'> Museum Owner:  <strong> " . $record['museum_name'] . "</strong> </a></li >\n";
                echo "<li > Museum Owner:  <strong> " . $record['country'] . "</strong> </li >\n";
                echo "<br><br>";
            }
        }
        mysqli_close($cn);
        ?>
        <br><br><br>
        <button onclick="window.location='main.php';">Go Back</button>
    </body>
</html>


