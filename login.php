<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Inloggen - Uitleen App</title>

    <?php
    session_start();
    require_once('db_connection.php'); // Include the file with database connection logic

    error_reporting(E_ALL);
ini_set('display_errors', 1);

    // Insert the Admin user with hashed password (You may want to move this to a separate setup script)
    $username = 'Admin';
    $password = 'Admin1234';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashedPassword);
    $stmt->execute();
    $stmt->close();

    // Handle the login logic
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Validate user credentials
        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                $_SESSION["username"] = $username;
                header("Location: admindashboard.php");
                exit();
            } else {
                $error_message = "Ongeldige gebruikersnaam of wachtwoord. Probeer het opnieuw.";
            }
        } else {
            $error_message = "Ongeldige gebruikersnaam of wachtwoord. Probeer het opnieuw.";
        }

        $stmt->close();
    }

    // Close the database connection
    $conn->close();
    ?>
</head>
<body class="text-center">
    <header>
        <h1>Uitleen App</h1>
    </header>
    <main class="form-signin">
        <section>
            <h2>Inloggen</h2>
            <?php
            // Display an error message if login fails
            if (isset($error_message)) {
                echo "<p style='color: red;'>$error_message</p>";
            }
            ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Gebruikersnaam:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Wachtwoord:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button class="btn btn-primary" type="submit">Inloggen</button>
            </form>
        </section>
    </main>
    <!-- Bootstrap JS (optional, for certain features) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>