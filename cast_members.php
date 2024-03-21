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
    $query = "SELECT MotionPicture.name AS movie_name, 
                     COUNT(DISTINCT Role.pid) AS people_count, 
                     COUNT(*) AS role_count
              FROM MotionPicture
              JOIN Role ON MotionPicture.id = Role.mpid
              GROUP BY MotionPicture.name
              ORDER BY people_count DESC
              LIMIT 5";

    // Execute the query
    $result = $conn->query($query);

    // Output the filtered results
    if ($result->num_rows > 0) {
        echo "<h2>Top 5 Movies with Highest Number of People Playing a Role</h2>";
        echo "<table border='1'>";
        echo "<thead><tr><th>Movie Name</th><th>People Count</th><th>Role Count</th></tr></thead>";
        echo "<tbody>";
        // Output table data
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['movie_name']."</td>";
            echo "<td>".$row['people_count']."</td>";
            echo "<td>".$row['role_count']."</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No data found.";
    }
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
