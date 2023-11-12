<html>
    <!-- table that displays some analytics of the database-->
    <style>
        table, th, td {
            border:1px solid black;
        }
    </style>
    <body>
        <h1 >ANALYTICS</h1>
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

        <!-- asks for the category of analyitcs to display-->
        <form method="post" action="">
            <!-- menu dropdown, retrieves countries from database -->
            <select id="selecType" name="type" onchange="this.form.submit()">
                <option value="" disabled selected>--select--</option>
                <option value="1">Check most popular items</option>
                <option value="2">Check best reviewed museums</option>
            </select>

        </form>
        <?php
        // checks if option was selected
        if(isset($_POST['type'])) {

            $type = $_POST['type'];
            // if one it will get the most popular items
            // most popular cities, art pieces and museums
            if($type == 1) {
                $q = "SELECT * FROM Plan JOIN City ON(id = city_id) WHERE type = 'city' GROUP BY city_id ORDER BY COUNT(*) DESC LIMIT 5";

                $st = $cn->stmt_init();
                $st->prepare($q);
                $st->execute();
                $result = $st->get_result();

                echo "<h3>Top 5 most popular cities</h3>";
                echo "<table style=\"width:50%\">";
                echo "<tr>";
                echo "<th>rank</th>";
                echo "<th>city id</th>";
                echo "<th>city name</th>";
                echo "<th>population</th>";
                echo "<th>country</th>";
                echo "<th>language</th>";
                echo "</tr>";

                $position = 1;
                while ($record = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$position."</td>";
                    echo "<td>".$record['city_id']."</td>";
                    echo "<td>".$record['city_name']."</td>";
                    echo "<td>".$record['city_population']."</td>";
                    echo "<td>".$record['country']."</td>";
                    echo "<td>".$record['language']."</td>";
                    echo "</tr>";
                    $position = $position + 1;
                }
                echo "</table>";
                echo "<br><br><br><h3>Top 5 most popular art pieces</h3>";
                $q = "SELECT * FROM Plan JOIN ArtPiece ON(id = piece_id) JOIN Museum USING(museum_id) WHERE type = 'art piece' GROUP BY piece_id ORDER BY COUNT(*) DESC LIMIT 5";

                $st = $cn->stmt_init();
                $st->prepare($q);
                $st->execute();
                $result = $st->get_result();

                echo "<table style=\"width:50%\">";
                echo "<tr>";
                echo "<th>rank</th>";
                echo "<th>piece id</th>";
                echo "<th>piece name</th>";
                echo "<th>author</th>";
                echo "<th>painting type</th>";
                echo "<th>creation year</th>";
                echo "<th>museum_name</th>";
                echo "</tr>";

                $position = 1;
                while ($record = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$position."</td>";
                    echo "<td>".$record['piece_id']."</td>";
                    echo "<td>".$record['painting_name']."</td>";
                    echo "<td>".$record['author']."</td>";
                    echo "<td>".$record['painting_type']."</td>";
                    echo "<td>".$record['creation_year']."</td>";
                    echo "<td>".$record['museum_name']."</td>";
                    echo "</tr>";
                    $position = $position + 1;
                }
                echo "</table>";
                echo "<br><br><br><h3>Top 5 most popular museums</h3>";
                $q = "SELECT * FROM Plan JOIN Museum ON(id = museum_id) JOIN City USING(city_id) WHERE type = 'museum' GROUP BY museum_id ORDER BY COUNT(*) DESC LIMIT 5";

                $st = $cn->stmt_init();
                $st->prepare($q);
                $st->execute();
                $result = $st->get_result();

                echo "<table style=\"width:50%\">";
                echo "<tr>";
                echo "<th>rank</th>";
                echo "<th>museum id</th>";
                echo "<th>museum name</th>";
                echo "<th>address</th>";
                echo "<th>museum_type</th>";
                echo "<th>city location</th>";
                echo "</tr>";

                $position = 1;
                while ($record = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$position."</td>";
                    echo "<td>".$record['museum_id']."</td>";
                    echo "<td>".$record['museum_name']."</td>";
                    echo "<td>".$record['address']."</td>";
                    echo "<td>".$record['museum_type']."</td>";
                    echo "<td>".$record['city_name']."</td>";
                    echo "</tr>";
                    $position = $position + 1;
                }
                echo "</table>";
                echo "<br><br><br>";
            }
            else if($type == 2) {
                // second option is to see some museum stats based on reviews
                // best average reviews (must have more than one review)
                $q = "SELECT *, AVG(rating) FROM Museum JOIN Review USING(museum_id) 
                              GROUP BY museum_id HAVING COUNT(*) > 1 ORDER BY AVG(rating) DESC LIMIT 5";

                $st = $cn->stmt_init();
                $st->prepare($q);
                $st->execute();
                $result = $st->get_result();

                echo "<h3>Top 5 museums with the best average</h3>";
                echo "<table style=\"width:50%\">";
                echo "<tr>";
                echo "<th>rank</th>";
                echo "<th>museum id</th>";
                echo "<th>museum name</th>";
                echo "<th>address</th>";
                echo "<th>museum type</th>";
                echo "<th>average rating</th>";
                echo "</tr>";

                $position = 1;
                while ($record = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$position."</td>";
                    echo "<td>".$record['museum_id']."</td>";
                    echo "<td>".$record['museum_name']."</td>";
                    echo "<td>".$record['address']."</td>";
                    echo "<td>".$record['museum_type']."</td>";
                    echo "<td>".$record['AVG(rating)']."</td>";
                    echo "</tr>";
                    $position = $position + 1;
                }
                echo "</table>";

                // museums that have the most 5 star ratings
                $q = "SELECT *, COUNT(*) FROM Museum JOIN Review USING(museum_id) WHERE rating = 5
                              GROUP BY museum_id ORDER BY COUNT(*) DESC LIMIT 5";

                $st = $cn->stmt_init();
                $st->prepare($q);
                $st->execute();
                $result = $st->get_result();

                echo "<h3>Top 5 museums with the most 5 star ratings</h3>";
                echo "<table style=\"width:50%\">";
                echo "<tr>";
                echo "<th>rank</th>";
                echo "<th>museum id</th>";
                echo "<th>museum name</th>";
                echo "<th>address</th>";
                echo "<th>museum type</th>";
                echo "<th>5 star count</th>";
                echo "</tr>";

                $position = 1;
                while ($record = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$position."</td>";
                    echo "<td>".$record['museum_id']."</td>";
                    echo "<td>".$record['museum_name']."</td>";
                    echo "<td>".$record['address']."</td>";
                    echo "<td>".$record['museum_type']."</td>";
                    echo "<td>".$record['COUNT(*)']."</td>";
                    echo "</tr>";
                    $position = $position + 1;
                }
                echo "</table>";
            }
        }
        mysqli_close($cn);
        ?>
        <br><br><br>
        <button onclick="window.location='main.php';">Go Back</button>
    </body>
</html>
