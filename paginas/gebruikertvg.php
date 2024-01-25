<?php
session_start();
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('../includes/db_connection.php');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["password"])) {
    $usersEmail = $_POST["email"];
    $usersPassword = $_POST["password"];

    $sql_insert = $conn->prepare("INSERT INTO users (usersPwd, usersEmail) VALUES (?, ?)");
    $sql_insert->bind_param("ss", $usersPassword, $usersEmail);

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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>User Management</title>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="card text-center fade-in">
            <div class="card-header">
                <h2>Docent toevoegen</h2>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Toevoegen</button>
                </form>
            </div>
        </div>

        <h2 class="mt-4 fade-in">Bestaande gebruikers</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<div class='row'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='col-md-3 mb-4 fade-in'>";
                echo "<div class='card fade-in'>";
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
            echo "<p class='fade-in'>No users found.</p>";
        }

        $result->close();
        ?>

    </div>

</body>

</html>

<?php
$conn->close();
?>
