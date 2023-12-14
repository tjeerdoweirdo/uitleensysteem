<?php
require 'db_connection.php';

$id = 1;
$cat_id = 1;

$product = "SELECT photo, name, serialnumber, status, id FROM products WHERE id = $id";
$cat = "SELECT name, cat_id FROM category WHERE cat_id = $cat_id";

$result_product = $conn->query($product);
$result_cat = $conn->query($cat);

$row_product = $result_product->fetch_assoc();
$row_cat = $result_cat->fetch_assoc();

$categorie = "<h1>" . $row_cat["name"] . "</h1><br>";
echo $categorie;
?>

<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/css/cat.css">

<body>
    <table>
        <thead>
            <tr>
                <th>Foto </th>
                <th>Naam </th>
                <th>Serienummer </th>
                <th>Beschikbaarheid </th>
            </tr>
        </thead>
        <?php
        echo "<tr>";
        echo "<td class='list_td'>" . htmlspecialchars($row_product["photo"], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='list_td'>" . htmlspecialchars($row_product["name"], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='list_td'>" . htmlspecialchars($row_product["serialnumber"], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='list_td'>" . htmlspecialchars($row_product["status"], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "</tr>";
        ?>
    </table>
</body>

</html>