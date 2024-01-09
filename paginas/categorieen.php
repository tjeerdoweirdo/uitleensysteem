<?php
include("../includes/db_connection.php");
include("../includes/header.php");
include("../css/styles.css");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category = isset($_POST["catName"]) ? trim($_POST["catName"]) : "";

    if (!empty($category)) {
        $sql = "INSERT INTO categories (catName) VALUES ('$catName')";
        if ($conn->query($sql) === TRUE) {
            // Insertion successful, you can add further logic if needed
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["action"]) && $_GET["action"] === "remove" && isset($_GET["id"])) {
    $categoryId = $_GET["id"];
    $sql = "DELETE FROM categories WHERE catId = $categoryId";
    
    if ($conn->query($sql) === TRUE) {
        // Deletion successful, you can add further logic if needed
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT catId, catName FROM categories";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Manager</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        input {
            padding: 8px;
            margin-right: 10px;
        }
        button {
            padding: 8px 12px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<h1>Category Manager</h1>

<form method="post" action="">
    <input type="text" name="category" placeholder="Enter a new category" required>
    <button type="submit">Add Category</button>
</form>

<h2>Your Categories</h2>
<ul>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<li id='category{$row['catId']}'><span>{$row['catName']}</span><button type='button' onclick='removeCategory({$row['catId']})'>Remove</button></li>";
        }
    }
    ?>
</ul>

<script>
    function removeCategory(categoryId) {
        $.ajax({
            type: "GET",
            url: "your_php_file.php",
            data: { action: "remove", id: categoryId },
            success: function(response) {
                $("#category" + categoryId).remove();
            },
            error: function(xhr, status, error) {
                console.error("Error removing category:", error);
            }
        });
    }
</script>

</body>
</html>
