<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Manager</title>
    <style>
        h2, h3, form, .category-list {
            opacity: 0;
            animation: fadeIn 1s ease-in-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        h1 {
            text-align: center;
        }

        h2 {
            text-align: center;
        }
        
        h3 {
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

        .category-list li {
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

<?php
session_start();
include '../includes/header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('../includes/db_connection.php');

function generateUniqueId() {
    return 'category_' . time();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category = isset($_POST["category"]) ? trim($_POST["category"]) : "";

    if (!empty($category)) {
        $sql = "INSERT INTO categories (catName) VALUES ('$category')";
        if ($conn->query($sql) === TRUE) {
            $lastInsertedId = $conn->insert_id;
            $uniqueId = "category_" . $lastInsertedId;
            echo "Category added successfully with ID: $lastInsertedId";

            // Echo the new category with fade-in class
            echo "<li id='{$uniqueId}'><span>{$category}</span><button type='button' onclick='removeCategory({$lastInsertedId})'>Remove</button></li>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["action"]) && $_GET["action"] === "remove" && isset($_GET["id"])) {
    $categoryId = $_GET["id"];
    $sql = "DELETE FROM categories WHERE catId = $categoryId";

    if ($conn->query($sql) === TRUE) {
        echo "Category removed successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    exit;
}

$sql = "SELECT catId, catName FROM categories";
$result = $conn->query($sql);

$conn->close();
?>

<h2>Category Manager</h2>

<form method="post" action="">
    <input type="text" name="category" placeholder="Enter a new category" required>
    <button type="submit">Add Category</button>
</form>

<h2>Your Categories</h2>
<div class="category-list">
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categoryId = $row['catId'];
                $categoryName = $row['catName'];
                $uniqueId = "category_" . $categoryId;
                echo "<li id='{$uniqueId}'><span>{$categoryName}</span><button type='button' onclick='removeCategory({$categoryId})'>Remove</button></li>";
            }
        }
        ?>
    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    function removeCategory(categoryId) {
        $.ajax({
            type: "GET",
            url: "<?php echo $_SERVER['PHP_SELF']; ?>",
            data: { action: "remove", id: categoryId },
            success: function(response) {
                $("#" + "category_" + categoryId).remove();
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error("Error removing category:", error);
            }
        });
    }
</script>

</body>
</html>
