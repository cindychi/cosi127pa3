<html>
<head>
<a href="indexFinal.php" class="btn btn-primary">Home</a>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>IMDB Movie Database</title></br>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>IMDB Movie Database</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="submitted" value="true"> 
            <input type="submit" name="v_movies" value="View All Movies">
            <input type="submit" name="v_actors" value="View All Actors">
    </form>


<?php
$query = ""; // Define $query variable

$request_method_post = ($_SERVER["REQUEST_METHOD"] == "POST") ? "true" : "false";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // Retrieve form data
    if (
        isset($_POST["selectTable"], $_POST["selectField"], $_POST["searchTerm"]) &&
        !empty($_POST["selectTable"]) && !empty($_POST["selectField"]) && !empty($_POST["searchTerm"])
    ) {

        $selectTable = $_POST["selectTable"];
        $selectField = $_POST["selectField"];
        $searchTerm = $_POST["searchTerm"];

        // Construct the SQL query
        $query = "SELECT * FROM $selectTable WHERE $selectField LIKE '%$searchTerm%'";

        // Assuming you have a database connection stored in $conn variable
        // Execute the query (assuming you have a database connection already established)
        // For example:
        $servername = "localhost"; // Change if your MySQL server is hosted elsewhere
        $username = "root"; // Your MySQL username
        $password = ""; // Your MySQL password
        $dbname = "cosi127_pa1_2"; // Your MySQL database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //DIFFERENT CASES

        //Motion Picture
        //MOVIES
        if($selectField == "movie name"){
            $query = "SELECT MotionPicture.*, Movie.* 
                        FROM MotionPicture
                        JOIN Movie ON id = mpid
                            WHERE name LIKE '%$searchTerm%' AND id >= 200";
        }

        //SERIES
        if($selectField == "series name"){
            $query = "SELECT MotionPicture.*, Series.* 
                        FROM MotionPicture
                        JOIN Series ON id = mpid
                            WHERE name LIKE '%$searchTerm%' AND id >= 100";
        }

        //SHOOTING LOCATION COUNTRY
        if($selectField == "shooting location country"){

            $sT = $searchTerm;
            $searchTerm = '%' . $searchTerm . '%';

            $query = "SELECT DISTINCT name
              FROM MotionPicture
              JOIN Location ON id = mpid 
              WHERE country LIKE '$searchTerm'";

            echo "<p><strong>Filter Criteria:</strong> $sT</p>";
        

            // Execute query
            $result = $conn->query($query);

            // Display query results
            if ($result && $result->num_rows > 0) {
                echo "<h2>Query Results</h2>";
                echo "<table border='1'>";
                // Output table headers
                echo "<table class='table table-bordered'>";
                echo "<thead class='thead-dark'>";
                $header_printed = false;
                echo "<tr>";
                while ($fieldInfo = $result->fetch_field()) {
                    echo "<th>" . $fieldInfo->name . "</th>";
                }
                echo "</tr>";

                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>" . $value . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
        }

        else{
            // Execute query
            $result = $conn->query($query);

            // Display query results
            if ($result && $result->num_rows > 0) {
                echo "<h2>Query Results</h2>";
                echo "<table border='1'>";
                // Output table headers
                echo "<table class='table table-bordered'>";
                echo "<thead class='thead-dark'>";
                $header_printed = false;
                echo "<tr>";
                while ($fieldInfo = $result->fetch_field()) {
                    echo "<th>" . $fieldInfo->name . "</th>";
                }
                echo "</tr>";

                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>" . $value . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
        }
        
        //GENRE
        
        
        // 10. Find the top 2 rated thriller movies (genre is thriller) that were shot exclusively 
        // in Boston. This means that the movie cannot have any other shooting location. List the 
        // movie names and their ratings
       
        // $selectedField = "SELECT MotionPicture.name, MotionPicture.rating
        //                     FROM MotionPicture
        //                     JOIN Location ON MotionPicture.id = Location.mpid
        //                     JOIN Genre ON MotionPicture.id = Genre.mpid
        //                     WHERE Genre.genre_name = 'thriller' AND Location.city = 'Boston'
        //                     ORDER BY MotionPicture.rating DESC
        //                     LIMIT 2";
    }

    else if(!empty($_POST["selectTable"]) && empty($_POST["selectField"]) && empty($_POST["searchTerm"])){
       
        $servername = "localhost"; // Change if your MySQL server is hosted elsewhere
        $username = "root"; // Your MySQL username
        $password = ""; // Your MySQL password
        $dbname = "cosi127_pa1_2"; // Your MySQL database name, changed from cosi127_pa1_2

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $table_name = $_POST["selectTable"];

        echo "<h2>$table_name</h2>";

        $sql_rows = "SELECT * FROM $table_name";
        $result_rows = $conn->query($sql_rows);


        if ($result_rows->num_rows > 0) {
            // Output data of each row
            echo "<table class='table table-bordered'>";
            echo "<thead class='thead-dark'>";
            $header_printed = false;
            while ($row = $result_rows->fetch_assoc()) {
                if (!$header_printed) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        echo "<th>$key</th>";
                    }
                    echo "</tr>";
                    $header_printed = true;
                }
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>$value</td>";
                }
                echo "</tr>";
            }
            echo "</thead>";
            echo "</table>";
        } else {
            echo "0 results";
        }
        $conn->close();
    }

    else if(!empty($_POST["selectTable"]) && !empty($_POST["selectField"]) && empty($_POST["searchTerm"])){
    
        $servername = "localhost"; // Change if your MySQL server is hosted elsewhere
        $username = "root"; // Your MySQL username
        $password = ""; // Your MySQL password
        $dbname = "cosi127_pa1_2"; // Your MySQL database name, changed from cosi127_pa1_2

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }          

        $table_name = $_POST["selectTable"];
        $selectField = $_POST["selectField"];

        if($selectField == "movie name"){
            $query = "SELECT MotionPicture.*, Movie.* 
                        FROM MotionPicture
                        JOIN Movie ON id = mpid
                            WHERE id >= 200";
        }

        if($selectField == "series name"){
            $query = "SELECT MotionPicture.*, Series.* 
                        FROM MotionPicture
                        JOIN Series ON id = mpid
                            WHERE id >= 100";
        }
        
        if($selectField == "shooting location country"){
            $query = "SELECT MotionPicture.name, Location.*
                        FROM MotionPicture
                        JOIN Location ON id = mpid ";
        }

        if($selectField == "genre_name"){
            $query = "SELECT Genre.*, MotionPicture.name, MotionPicture.rating, Location.city, Location.zip
                        From MotionPicture
                        JOIN Location ON MotionPicture.id = Location.mpid
                        JOIN Genre ON MotionPicture.id = Genre.mpid";

        }


        else{
            $query = "SELECT * FROM $table_name ORDER BY $selectField";
        }

        echo "<h2>$table_name</h2>";

        $result_rows = $conn->query($query);

        if ($result_rows->num_rows > 0) {
            // Output data of each row
            echo "<table class='table table-bordered'>";
            echo "<thead class='thead-dark'>";
            $header_printed = false;
            while ($row = $result_rows->fetch_assoc()) {
                if (!$header_printed) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        echo "<th>$key</th>";
                    }
                    echo "</tr>";
                    $header_printed = true;
                }
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>$value</td>";
                }
                echo "</tr>";
            }
            echo "</thead>";
            echo "</table>";
        } else {
            echo "0 results";
        }
        $conn->close();
    }

    else if(empty($_POST["selectTable"]) && empty($_POST["selectField"]) && empty($_POST["searchTerm"])){
        // echo "GGG";
            // MySQL database connection
            $servername = "localhost"; // Change if your MySQL server is hosted elsewhere
            $username = "root"; // Your MySQL username
            $password = ""; // Your MySQL password
            $dbname = "cosi127_pa1_2"; // Your MySQL database name, changed from cosi127_pa1_2

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
    
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
    
            // Query to fetch all table names
            $sql_tables = "SELECT table_name FROM information_schema.tables WHERE table_schema = '$dbname'";
            $result_tables = $conn->query($sql_tables);
    
            if ($result_tables->num_rows > 0) {
                // Output data of each table
                while ($row_table = $result_tables->fetch_assoc()) {
                    $table_name = $row_table["table_name"];
                    echo "<h2>$table_name</h2>";
    
                    // Query to fetch all rows from the current table
                    $sql_rows = "SELECT * FROM $table_name";
                    $result_rows = $conn->query($sql_rows);
    
                    if ($result_rows->num_rows > 0) {
                        // Output data of each row
                        echo "<table class='table table-bordered'>";
                        echo "<thead class='thead-dark'>";
                        $header_printed = false;
                        while ($row = $result_rows->fetch_assoc()) {
                            if (!$header_printed) {
                                echo "<tr>";
                                foreach ($row as $key => $value) {
                                    echo "<th>$key</th>";
                                }
                                echo "</tr>";
                                $header_printed = true;
                            }
                            echo "<tr>";
                            foreach ($row as $value) {
                                echo "<td>$value</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</thead>";
                        echo "</table>";
                    } else {
                        echo "0 results";
                    }
                }
            } else {
                echo "0 tables";
            }
            $conn->close();
    }


}

?>

</body>
</html>
