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

            if ($selectField === "highest rating") {
                // Assuming the search term is the genre name
                $genreName = $searchTerm;



                // Assuming you have a database connection established
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

                // Construct the SQL query
                $query = "SELECT name, rating
                            FROM MotionPicture
                            WHERE rating > (
                                SELECT AVG(rating)
                                FROM MotionPicture
                                JOIN Genre ON MotionPicture.id = Genre.mpid
                                WHERE Genre.genre_name = '$genreName'
                            )
                            ORDER BY rating DESC";

                // Execute the query
                $result = $conn->query($query);

                // Output the filtered results
                if ($result->num_rows > 0) {
                    echo "<h2>Query Results</h2>";
                    echo "<table border='1'>";
                    echo "<thead><tr><th>Movie Name</th><th>Rating</th></tr></thead>";
                    echo "<tbody>";
                    // Output table data
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row['name']."</td>";
                        echo "<td>".$row['rating']."</td>";
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
                echo "Please enter a valid search term.";
            }
        }
    }
    ?>
</body>
</html>
