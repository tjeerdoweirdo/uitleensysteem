<!DOCTYPE html>
<html lang="nl">

<?php
session_start();
require_once('../includes/db_connection.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["usersEmail"]) && isset($_POST["password"])) {
        $usersEmail = $_POST["usersEmail"];
        $password = $_POST["password"];

        $sql = "SELECT * FROM users WHERE usersEmail=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usersEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Compare plain text password
            if ($password === $row["usersPwd"]) {
                $_SESSION["userName"] = $row["usersName"];
                header("Location: admindashboard.php");
                exit();
            } else {
                $error_message = "Ongeldige gebruikersnaam of wachtwoord. Probeer het opnieuw.";
            }
        } else {
            $error_message = "Ongeldige gebruikersnaam of wachtwoord. Probeer het opnieuw.";
        }

        $stmt->close();
    } else {
        $error_message = "Niet alle vereiste velden zijn ingevuld.";
    }
}

$conn->close();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Inloggen - Uitleen App</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
        }

        .home-btn {
            text-decoration: none;
            color: #fff;
            padding: 8px 16px;
            border-radius: 4px;
            background-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .home-btn:hover {
            background-color: #0056b3;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }
    </style>
</head>

<body class="text-center">
    <header>
        <h1>Uitleen systeem</h1>
        <a href="index.php" class="home-btn">Home</a>
    </header>
    <main class="form-signin">
        <section>
            <h2>Inloggen</h2>
            <?php
            if (isset($error_message)) {
                echo "<p style='color: red;'>$error_message</p>";
            }
            ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="usersEmail" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Wachtwoord:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button class="btn btn-primary" type="submit">Inloggen</button>
            </form>
        </section>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
