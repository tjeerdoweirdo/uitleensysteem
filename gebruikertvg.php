<?php
include('db_connection.php');

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "User successfully added.";
    } else {
        echo "Error adding user: " . $conn->error;
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>gebruiker toevoegen</title>
</head>
<body>

<h2>gebruikers toevoegen</h2>
<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
    gebruikersnaam: <input type="text" name="username" required><br>
    wachtwoord: <input type="password" name="password" required><br>
    <input type="submit" value="toevoegen">
</form>

</body>
</html>
