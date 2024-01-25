<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorieën toevoegen</title>
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

        h1, h2, h3 {
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
            cursor: pointer; 
        }

        .category-list li a {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: inherit;
            width: 100%; 
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
        $catPicture = "";
        if (isset($_FILES['catPicture']) && $_FILES['catPicture']['error'] === 0) {
            $uploadsDirectory = 'uploads/';
            $uploadFile = $uploadsDirectory . basename($_FILES['catPicture']['name']);

            if (move_uploaded_file($_FILES['catPicture']['tmp_name'], $uploadFile)) {
                $catPicture = $uploadFile;
            } else {
                echo "Error uploading image.";
                exit();
            }
        }

        $sql = "INSERT INTO categories (catName, catPicture) VALUES ('$category', '$catPicture')";
        if ($conn->query($sql) === TRUE) {
            $lastInsertedId = $conn->insert_id;
            $uniqueId = "category_" . $lastInsertedId;
            echo "Category added successfully with ID: $lastInsertedId";
            echo "<li id='{$uniqueId}'>";
            echo "<a href='productslogin.php?catId={$lastInsertedId}'>";
            echo "<span>{$category}</span>";
            echo "<img src='{$catPicture}' alt='Category Image' style='max-width: 50px; max-height: 50px;'>";
            echo "<button type='button' onclick='removeCategory({$lastInsertedId})'>Remove</button>";
            echo "</a>";
            echo "</li>";
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

$sql = "SELECT catId, catName, catPicture FROM categories";
$result = $conn->query($sql);

$conn->close();
?>
<h2>Category Manager</h2>
<form method="post" action="" enctype="multipart/form-data">
    <input type="text" name="category" placeholder="Enter a new category" required>
    <input type="file" name="catPicture" accept="image/*">
    <button type="submit">Categorie toevoegen</button>
</form>

<h2>Uw categorieën</h2>
<div class="category-list">
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categoryId = $row['catId'];
                $categoryName = $row['catName'];
                $imagePath = $row['catPicture'];
                $uniqueId = "category_" . $categoryId;
                echo "<li id='{$uniqueId}'>";
                echo "<a href='productslogin.php?catId={$categoryId}'>";
                echo "<span>{$categoryName}</span>";
                echo "<img src='{$imagePath}' alt='Category Image' style='max-width: 75px; max-height: 75px;'>";
                echo "<button type='button' onclick='removeCategory({$categoryId})'>Remove</button>";
                echo "</a>";
                echo "</li>";
            }
        }
        ?>
    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        
        $(".category-list ul li").click(function() {
            var categoryId = $(this).attr("id").replace("category_", "");
            window.location.href = "productslogin.php?catId=" + categoryId;
        });
    });

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
