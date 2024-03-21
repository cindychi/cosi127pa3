<!DOCTYPE html>
<html lang="en">
<a href="indexFinal.php" class="btn btn-primary">Home</a>
<head>
    <title>IMDB Movie Database</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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

    <form action="tabsFinal.php" method="post">
        <input type="submit" name="v_tables" value="View All Tables">
        <input type="submit" name="v_actors" value="View All Actors">
        <input type="submit" name="v_movies" value="View All Movies"><br>
    </form>

    <?php

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if form fields are set
        if (isset($_POST["selectTable"], $_POST["selectField"], $_POST["searchTerm"])) {
            $selectField = $_POST["selectField"];
            $searchTerm = $_POST["searchTerm"];

            if ($selectField === "likes and ages") {
                // Assuming "X" likes and "Y" age are passed as comma-separated values
                $parameters = explode(", ", $searchTerm);
                $likes = intval($parameters[0]);
                $age = intval($parameters[1]);

                // Assuming you have a database connection established
                $servername = "localhost"; // Change if your MySQL server is hosted elsewhere
                $username = "root"; // Your MySQL username
                $password = ""; // Your MySQL password
                $dbname = "cosi127_pa1_3"; // Your MySQL database name

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Construct the SQL query
                $query = "SELECT MotionPicture.name, COUNT(Likes.mpid) AS num_likes
                            FROM MotionPicture
                            INNER JOIN Likes ON MotionPicture.id = Likes.mpid
                            INNER JOIN User ON Likes.uemail = User.email
                            WHERE User.age < $age
                            GROUP BY MotionPicture.name
                            HAVING num_likes > $likes
                            ORDER BY num_likes DESC";

                // Execute the query
                $result = $conn->query($query);

                // Output the filtered results
                if ($result->num_rows > 0) {
                    echo "<h2>Query Results</h2>";
                    echo "<table border='1'>";
                    echo "<thead><tr><th>Movie Name</th><th>Number of Likes</th></tr></thead>";
                    echo "<tbody>";
                    // Output table data
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row['name']."</td>";
                        echo "<td>".$row['num_likes']."</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "No data found for the selected criteria.";
                }
                // Close the database connection
                $conn->close();
            } else {
                echo "Please enter a value for likes and ages.";
            }
        }
    }
    ?>
</body>
</html>
