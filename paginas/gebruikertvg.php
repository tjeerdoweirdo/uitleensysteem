<?php
session_start();
include '../includes/header.php';

if (!isset($_SESSION['user_id']) ) {
    header('Location: login.php');
    exit();
}


function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    $charCount = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[mt_rand(0, $charCount)];
    }

    return $password;
}

include('../includes/db_connection.php');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    $usersEmail = $_POST["email"];

    $randomPassword = generateRandomPassword();

    $sql_insert = $conn->prepare("INSERT INTO users (usersPwd, usersEmail) VALUES (?, ?)");
    $sql_insert->bind_param("ss", $randomPassword, $usersEmail);

    if ($sql_insert->execute()) {
        echo "User successfully added.";

      
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error adding user: " . $conn->error;
        echo "SQL Query: " . $sql_insert->error;
    }

    $sql_insert->close();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_user"])) {
    $user_id_to_remove = $_POST["remove_user"];

    $sql_delete = $conn->prepare("DELETE FROM users WHERE usersId = ?");
    $sql_delete->bind_param("i", $user_id_to_remove);

    if ($sql_delete->execute()) {
        echo "User successfully removed.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error removing user: " . $conn->error;
        echo "SQL Query: " . $sql_delete->error;
    }

    $sql_delete->close();
}


$sql_select = "SELECT usersId, usersEmail FROM users";
$result = $conn->query($sql_select);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>User Management</title>
</head>
<body>

<div class="container mt-5">
    <div class="card text-center">
        <div class="card-header">
            <h2>Docent toevoegen</h2>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                </div>
                <button type="submit" class="btn btn-primary">Toevoegen</button>
            </form>
        </div>
    </div>

    <h2 class="mt-4">Bestaande gebruikers</h2>
    <?php
    if ($result->num_rows > 0) {
        echo "<div class='row'>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='col-md-3 mb-4'>";
            echo "<div class='card'>";
            echo "<div class='card-body'>";
            echo "<p class='card-text'>{$row['usersEmail']}</p>";
            echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            echo "<input type='hidden' name='remove_user' value='{$row['usersId']}'>";
            echo "<button type='submit' class='btn btn-danger'>Verwijder</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No users found.</p>";
    }

    $result->close();
    ?>

</body>
</html>

<?php
$conn->close();
?>
