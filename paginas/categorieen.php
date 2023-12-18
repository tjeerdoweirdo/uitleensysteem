<?php
require '../includes/db_connection.php';
include '../includes/header.php';


$cat_id = 1;

$product = "SELECT photo, name, serialnumber, status, id FROM products WHERE cat_id = $cat_id";
$cat = "SELECT cat_name, cat_id FROM category WHERE cat_id = $cat_id";

$result_product = $conn->query($product);
$result_cat = $conn->query($cat);

$row_cat = $result_cat->fetch_assoc();

//$categorie = "<h1>" . $row_cat["cat_name"] . "</h1><br>";
$categorie = "<h1>" . "categorie" . "</h1>";
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/cat.css">
    <script src="../js/cat.js"></script>
</head>

<body>
    <?php echo $categorie; ?>

    <table class='cat_table'>
        <?php
        if ($result_product->num_rows > 0) {
            while ($row_product = $result_product->fetch_assoc()) {
                if ($row_product["status"] == 1) {
                    $status = "beschikbaar";
                } else {
                    $status = "niet verkrijgbaar";
                }

                echo "<tr onclick='redirectToProduct(" . $row_product["id"] . ")'>";
                echo "<td class='list_td'><img src='" . htmlspecialchars($row_product["photo"], ENT_QUOTES, 'UTF-8') . "' alt='Product Photo'></td>";
                echo "<td class='list_td'>" . htmlspecialchars($row_product["name"], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td class='list_td'>" . htmlspecialchars($row_product["serialnumber"], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td class='list_td'>" . $status . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Er zijn geen producten.</td></tr>";
        }
        ?>
    </table>
</body>

</html>