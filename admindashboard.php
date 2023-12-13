<?php
// Establishing a connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loaningsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete an item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete"])) {
        $delete_id = $_POST["delete"];

        $delete_sql = "DELETE FROM items WHERE id = $delete_id";

        if ($conn->query($delete_sql) === TRUE) {
            echo "Item deleted!";
        } else {
            echo "Error deleting item: " . $conn->error;
        }
    }
}

// Add a new item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        $name = $_POST["name"];
        $category = ($_POST["category"] === "custom") ? $_POST["custom_category"] : $_POST["category"];
        $turn_in_date = $_POST["turn_in_date"];

        // Check if the "photo" key is set in $_FILES
        if (isset($_FILES["photo"]["name"])) {
            $photo = $_FILES["photo"]["name"];

            // Directory where uploaded photos are stored
            $upload_dir = "uploads/";

            // Upload the photo to the directory
            move_uploaded_file($_FILES["photo"]["tmp_name"], $upload_dir . $photo);

            $sql = "INSERT INTO items (name, category, photo, turn_in_date) VALUES ('$name', '$category', '$photo', '$turn_in_date')";

            if ($conn->query($sql) === TRUE) {
                echo "New item added!";
            } else {
                echo "Error adding item: " . $conn->error;
            }
        } else {
            // Handle the case where no file is uploaded if needed
            echo "No photo uploaded.";
        }
    }
}

// Retrieve items from the database with a search filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT id, name, category, photo, turn_in_date FROM items WHERE name LIKE '%$search%'";
$result = $conn->query($sql);

// Function to check if the turn-in date has passed
function checkDueDate($turnInDate) {
    $today = date("Y-m-d");
    return ($turnInDate < $today) ? "overdue" : "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header class="bg-dark text-white">
        <div class="container">
            <h1>Admin Dashboard</h1>
        </div>
    </header>

    <main class="container mt-4">
        <!-- Searchbar for the 'name' column -->
        <form method="get" action="" class="mb-3">
            <div class="form-group">
                <label for="search">Search by name:</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="search" id="search" placeholder="Enter name">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="container">
            <!-- Form to add a new item -->
            <form method="post" action="" enctype="multipart/form-data" class="mb-3">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name" required>
                </div>

                <div class="form-group">
                    <label for="category">Category:</label>
                    <select name="category" id="categoryDropdown" class="form-control" onchange="toggleCustomCategoryInput()">
                        <option value="keyboard">Keyboard</option>
                        <option value="mouse">Mouse</option>
                        <option value="pc">Computer</option>
                        <option value="headphones">Headphones</option>
                        <option value="tablet">Tablet</option>
                        <option value="camera">Camera</option>
                        <option value="printer">Printer</option>
                        <option value="custom">Custom</option>
                    </select>
                    <div id="customCategoryInput" style="display: none;">
                        <label for="custom_category">Or enter a custom category:</label>
                        <input type="text" class="form-control" name="custom_category">
                    </div>
                </div>

                <div class="form-group">
                    <label for="turn_in_date">Turn In Date:</label>
                    <input type="date" class="form-control" name="turn_in_date">
                </div>

                <div class="form-group">
                    <label for="photo">Photo:</label>
                    <input type="file" class="form-control" name="photo" accept="image/*" required>
                </div>

                <div class="form-group">
                    <input type="submit" name="submit" class="btn btn-primary" value="Add">
                </div>
            </form>

            <!-- Table to display items -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Photo</th>
                        <th>Due</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display items in the table
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row["name"] . "</td>
                                    <td>" . $row["category"] . "</td>
                                    <td><img src='uploads/" . $row["photo"] . "' alt='Item Photo' class='img-thumbnail'></td>
                                    <td class='" . checkDueDate($row["turn_in_date"]) . "'>" . $row["turn_in_date"] . "</td>
                                    <td>
                                        <form method='post' action=''>
                                            <input type='hidden' name='delete' value='" . $row["id"] . "'>
                                            <button type='submit' class='btn btn-danger'>Delete</button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No items found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function toggleCustomCategoryInput() {
            var categoryDropdown = document.getElementById("categoryDropdown");
            var customCategoryInput = document.getElementById("customCategoryInput");

            customCategoryInput.style.display = (categoryDropdown.value === "custom") ? "block" : "none";
        }
    </script>
</body>

</html>
