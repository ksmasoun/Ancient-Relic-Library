
<?php
//Credentials for database connection
$servername = "localhost";
$username = "masounk_admin_A6";
$password = "adminDatabseA6";
$dbname = "masounk_DatabaseA6";

$conn = new mysqli($servername, $username, $password, $dbname);
//Showing user error if connection failed 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve all records from the books table
$sql = "SELECT * FROM books";
$result = $conn->query($sql);

// Searching (partial and full search)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchKeyword = cleanInput($_POST['search']);

    // Checking if the search keyword is not empty
    if (!empty($searchKeyword)) {
        $searchKeyword = $conn->real_escape_string($searchKeyword);
        $sql = "SELECT * FROM books WHERE title LIKE '%$searchKeyword%'";
        $result = $conn->query($sql);
    }
}

$conn->close();

function cleanInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search For Books Available In The Database!</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <h1>Book Management System - View</h1>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="search">Search by Title:</label>
        <input type="text" id="search" name="search">
        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th>Book ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Year</th>
            <th>Quantity</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // Looping through each row in the result
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["title"] . "</td>";
                echo "<td>" . $row["author"] . "</td>";
                echo "<td>" . $row["year"] . "</td>";
                echo "<td>" . $row["quantity"] . "</td>";
                echo "</tr>";
            }
        } else {
            // Displaying a message if no records are found
            echo "<tr><td colspan='5'>No records found</td></tr>";
        }
        ?>
    </table>
</body>
</html>