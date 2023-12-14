<?php
include('../includes/db_connection.php');


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle user registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $userName = $_POST["username"];
    $usersPwd = $_POST["password"];
    $usersEmail = isset($_POST["email"]) ? $_POST["email"] : null; // Check if email is set

    $hashed_password = password_hash($usersPwd, PASSWORD_DEFAULT);

    $sql_insert = $conn->prepare("INSERT INTO users (usersName, usersPwd, usersEmail) VALUES (?, ?, ?)");
    $sql_insert->bind_param("sss", $userName, $hashed_password, $usersEmail);

    if ($sql_insert->execute()) {
        echo "User successfully added.";

        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error adding user: " . $conn->error;
    }

    $sql_insert->close();
}

// Handle user removal
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_user"])) {
    $user_id_to_remove = $_POST["remove_user"];

    // Use prepared statement to delete user
    $sql_delete = $conn->prepare("DELETE FROM users WHERE usersId = ?");
    $sql_delete->bind_param("i", $user_id_to_remove);

    if ($sql_delete->execute()) {
        echo "User successfully removed.";
        // Redirect back to gebruikertvg.php after removal
        header("Location: gebruikertvg.php");
        exit();
    } else {
        echo "Error removing user: " . $conn->error;
        // Print or log the actual SQL query for debugging
        echo "SQL Query: " . $sql_delete->error;
    }

    $sql_delete->close();
}

// Fetch and display existing users
$sql_select = "SELECT usersId, usersName, usersEmail FROM users";
$result = $conn->query($sql_select);

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
</head>
<body>

<h2>Existing Users</h2>
<?php
if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['usersName']} - Email: {$row['usersEmail']} ";
        echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
        echo "<input type='hidden' name='remove_user' value='{$row['usersId']}'>";
        echo "<input type='submit' value='Remove'></form></li>";
    }
    echo "</ul>";
} else {
    echo "No users found.";
}

$result->close();
?>

<h2>Add User</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Gebruikersnaam: <input type="text" name="username" required><br>
    Wachtwoord: <input type="password" name="password" required><br>
    Email: <input type="text" name="email"><br>
    <input type="submit" value="Toevoegen">
</form>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
