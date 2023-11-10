
<?php
//Credentials for database connection
$servername = "localhost";
$username = "masounk_admin_A6";
$password = "adminDatabseA6";
$dbname = "masounk_DatabaseA6";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inserting record into the books table
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = cleanInput($_POST['title']);
    $author = cleanInput($_POST['author']);
    $year = cleanInput($_POST['year']);
    $quantity = cleanInput($_POST['quantity']);

    // Validating the inputs
    $errors = [];
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    if (empty($author)) {
        $errors[] = "Author is required";
    }
    if (empty($year)) {
        $errors[] = "Publication Year is required";
    }
    if (!is_numeric($year) || $year < 0) {
        $errors[] = "Publication Year must be a positive number";
    }
    if (empty($quantity)) {
        $errors[] = "Quantity is required";
    }
    if (!is_numeric($quantity) || $quantity < 0) {
        $errors[] = "Quantity must be a positive number";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO books (title, author, year, quantity) VALUES ('$title', '$author', '$year', '$quantity')";

        if ($conn->query($sql) === TRUE) {
            // Setting up a success message
            $message = "Book record inserted successfully";
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Closing the database connection
$conn->close();

function cleanInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    // Converting special characters to HTML entities
    $input = htmlspecialchars($input);
    return $input;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Management System - Insert</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Book Management System - Insert</h1>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="author">Author:</label>
        <input type="text" id="author" name="author" required>

        <label for="year">Publication Year:</label>
        <input type="number" id="year" name="year" required>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>

        <button type="submit">Add Book</button>
    </form>

    <?php if (isset($message)) { ?>
        <!-- Displaying success message if set otherwise showing relevant error messages -->
        <p class="success"><?php echo $message; ?></p>
    <?php } ?>

    <?php if (isset($error)) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <?php if (!empty($errors)) { ?>
        <ul class="error">
            <?php foreach ($errors as $error) { ?>
                <li><?php echo $error; ?></li>
            <?php } ?>
        </ul>
    <?php } ?>
</body>
</html>