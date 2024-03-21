<head>
    <a href="indexFinal.php" class="btn btn-primary">Home</a>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>IMDB Movie Database</title></br>
    <h1>IMDB Movie Database</h1>
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

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

        if (!empty($_POST["searchTerm"])) {

            $searchTerm = explode(", ", $_POST["searchTerm"]); // Splitting the input into genre and city
    
            $genre = $searchTerm[0]; // First part of input is genre
            $city = isset($searchTerm[1]) ? $searchTerm[1] : null;  // Second part of input is city if available
    
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
    
            // Construct the SQL query based on input
            if ($city) {
                // If city is provided, search for movies with given genre and shot exclusively in the specified city
                $query = "SELECT MotionPicture.name, MotionPicture.rating
                            FROM MotionPicture
                            JOIN Genre ON MotionPicture.id = Genre.mpid
                            JOIN Location ON MotionPicture.id = Location.mpid
                            WHERE MotionPicture.id >= 200
                            AND Genre.genre_name = '$genre'
                            AND Location.city = '$city'
                            GROUP BY MotionPicture.name
                            HAVING COUNT(DISTINCT Location.city) = 1
                            ORDER BY MotionPicture.rating DESC
                            LIMIT 2";
            } else {
                // If only genre is provided, show all movies with that genre
                $query = "SELECT MotionPicture.name, MotionPicture.rating
                            FROM MotionPicture
                            JOIN Genre ON MotionPicture.id = Genre.mpid
                            WHERE MotionPicture.id >= 200
                            AND Genre.genre_name = '$genre'
                            ORDER BY MotionPicture.rating DESC";
            }
    
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
            echo "Please select both a city and a genre.";
        }
    }
}
?>
