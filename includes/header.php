<!DOCTYPE html>
<html lang="en">
<?php


if (!isset($_SESSION['usersId'])) {
 
    $redirect = "index.php";
}
 
if (isset($_GET['logout'])) {
   
    $_SESSION = array();

    
    session_destroy();

    
    $redirect = "index.php";
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<style>
h1 {
text-align: center;
}
</style>
</head>
<body>




<header class="bg-dark text-white">
    <div class="container">
        <h1>Admin Panel</h1>
        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item"><a class="nav-link text-white" href="admindashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="add_item.php">Producten toevoegen</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="gebruikertvg.php">Gebruiker toevoegen</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="categorieen.php"> categorieen</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="?logout=1">Log uit</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="container mt-4">