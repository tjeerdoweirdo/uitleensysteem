<?php
require '../includes/db_connection.php';

$catId = 1;

$cat = "SELECT catName FROM categories WHERE $catId";
$product = "SELECT itemPicture, itemName, itemNumber, itemState, itemId, itemDin, itemDout, itemDescription FROM items WHERE $catId";

$result_product = $conn->query($product);
$result_cat = $conn->query($cat);

$row_cat = $result_cat->fetch_assoc();
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/prod.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="../js/prod.js"></script>
</head>

<body>
    <div class="bg-dark text-white">
        <header>
            <h1><?php echo $row_cat["catName"]; ?><h1>
        </header>
        <a class="nav-item"><a class="nav-link text-white" href="index.php">Home</a></a>
    </div>

    <div class='cat_container'>
        <?php
        if ($result_product->num_rows > 0) {
            while ($row_product = $result_product->fetch_assoc()) {
                if ($row_product["itemState"] == 1) {
                    $status = "beschikbaar";
                } else {
                    $status = "niet verkrijgbaar";
                }

                echo "<div class='product_card fade-in-row' onclick='redirectToProduct(" . $row_product["itemId"] . ")'>";
                echo "<img src='" . htmlspecialchars($row_product["photo"], ENT_QUOTES, 'UTF-8') . "' alt='Product Photo'>";
                echo "<p>" . htmlspecialchars($row_product["itemName"], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>" . htmlspecialchars($row_product["itemNumber"], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>" . $status . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Er zijn geen producten.</p>";
        }
        ?>
    </div>
</body>

</html>

<?php
//include '../includes/footer.php'; 
?>