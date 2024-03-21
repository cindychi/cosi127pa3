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
error_reporting(E_ALL); 
ini_set('display_errors', 1);

$query = ""; // Define $query variable

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
        if (!empty($selectField) && !empty($searchTerm)) {
            echo "Scevefvvrvnrvrnvnvrelect Field: $selectField<br>";
            $query = "SELECT * FROM $selectTable WHERE $selectField LIKE '%$searchTerm%'";
        } else if ($selectField == "" && $searchTerm == "") {
            $query = "SELECT * FROM $selectTable";
        }
        
    






        // Assuming you have a database connection stored in $conn variable
        // Execute the query (assuming you have a database connection already established)
        // For example:
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

        // Execute query
        $result = $conn->query($query);

        // Display query results
        if ($result && $result->num_rows > 0) {
            echo "<h2>Query Results</h2>";
            echo "<table border='1'>";
            // Output table headers
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
        } else {
            echo "No results found.";
        }
    }
}
?>

</body>
</html>
