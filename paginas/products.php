<?php
require '../includes/db_connection.php';
$catId = 1;
$itemId = 1;

$cat = "SELECT catName FROM categories WHERE $catId";
$product = "SELECT itemPicture, itemName, itemNumber, itemState, itemId, itemDin, itemDout, itemDescription FROM items WHERE $catId";
$card_product = "SELECT itemPicture, itemName, itemNumber, itemState, itemId, itemDin, itemDout, itemDescription FROM items WHERE $itemId";

$result_product = $conn->query($product);
$result_cat = $conn->query($cat);
$result_card = $conn->query($card_product);

$row_cat = $result_cat->fetch_assoc();
$result_card_product = $conn->query($card_product);
$row_card_product = $result_card_product->fetch_assoc();

$userRole = 0;

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
            <h1><?php echo $row_cat["catName"]; ?></h1>
        </header>
        <a class="nav-item"><a class="nav-link text-white" href="index.php">Home</a></a>
    </div>

    <div class='cat_container'>
        <?php
        if ($result_product->num_rows > 0) {
            while ($row_product = $result_product->fetch_assoc()) {
                $status = $row_product["itemState"];
                $modalTarget = ($userRole == 1) ? '#modal1' : '#modal2';

                echo "<div class='product_card fade-in-row' data-bs-toggle='modal' data-bs-target='" . $modalTarget . "' data-item-id='" . $row_product["itemId"] . "' data-item-name='" . htmlspecialchars($row_product["itemName"], ENT_QUOTES, 'UTF-8') . "' data-item-description='" . htmlspecialchars($row_product["itemDescription"], ENT_QUOTES, 'UTF-8') . "' data-item-number='" . htmlspecialchars($row_product["itemNumber"], ENT_QUOTES, 'UTF-8') . "' data-item-dout='" . $row_product["itemDout"] . "' data-item-din='" . $row_product["itemDin"] . "' data-item-picture='" . htmlspecialchars($row_product["itemPicture"], ENT_QUOTES, 'UTF-8') . "' data-item-state='" . $row_product["itemState"] . "'>";
                echo "<img src='" . htmlspecialchars($row_product["itemPicture"], ENT_QUOTES, 'UTF-8') . "' alt='Product Photo'>";
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

    <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="modal1Label" aria-hidden="true">
        <form id="save" method="POST" enctype="multipart/form-data">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal1Label">
                            <?php echo htmlspecialchars($row_card_product["itemName"]) ?>
                        </h1>
                        <button type=button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Item:</label>
                                        <input type="text" name="item" id="item" class="form-control" placeholder="" value="<?php echo htmlspecialchars($row_card_product["itemName"], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Serienummer:</label>
                                        <input type="text" name="nummer" id="nummer" class="form-control" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label>Categorie:</label>
                                        <select class="form-select" id="categorie" name="categorie">
                                            <?php
                                            include('includes/db_connection.php');
                                            $categories = mysqli_query($conn, "SELECT * FROM categories");
                                            while ($category = mysqli_fetch_array($categories)) {
                                                $selected = ($category['catId'] == $row_cat['catId']) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $category['catId']; ?>" <?php echo $selected; ?>>
                                                    <?php echo $category['catName']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="d-flex mb-3" style="margin-bottom: 0px!important;">
                                        <div class="form-group p-2" style="padding-left: 0px!important;">
                                            <label>Datum uitgeleend:</label>
                                            <input type="date" name="datumuit" id="datumuit" class="form-control" placeholder="">
                                        </div>

                                        <div class="form-group p-2">
                                            <label>Datum retour:</label>
                                            <input type="date" name="datumin" id="datumin" class="form-control" placeholder="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Huidige staat:</label><br>
                                        <select class="form-select" id="staat" name="staat">
                                            <option value="Beschikbaar">Beschikbaar</option>
                                            <option value="Reparatie">Reparatie</option>
                                            <option value="Kapot">Kapot</option>
                                            <option value="Anders">Anders (zie omschrijving)</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Omschrijving:</label>
                                        <textarea rows="7" name="omschrijving" id="omschrijving" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label>Foto:</label>
                                        <img style="height: 500px; width: 550px;" src="data:image/jpeg;base64,<?php echo "<img src='" . htmlspecialchars($row_card_product["itemPicture"], ENT_QUOTES, 'UTF-8') . "'"; ?>" alt="<?php echo $row_card_product['itemName']; ?>">
                                        <input type="file" name="foto" id="foto" class="form-control" style="margin-top: 8px;"> 
                                    </div>
                                </div>
                            </div>
                            <!-- Hier logs -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                        <button type="submit" name="savedata" class="btn btn-primary">Opslaan</button>
                    </div>
                </div>
            </div>
    </div>

    </form>
    <div class="modal fade" id="verwijdermodal" tabindex="-1" aria-labelledby="verwijdermodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5">Item verwijderen</h4>
                    <button type=button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="includes/deleteitem.php">
                    <input type="hidden" name="delete_id" id="delete_id">
                    <div class="modal-body">

                        <p>Weet u zeker dat u dit item wilt verwijderen?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                        <button type="submit" name="deleteitem" class="btn btn-danger verwijderen">Verwijderen</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
    <!-- Logs modal -->
    <div class="modal fade" id="logs" tabindex="-1" aria-labelledby="logsLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5">Logboek</h4>
                    <button type="button" class="btn btn-primary"></i><button type=button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden">
                <div class="modal-body">
                    <i class="bi bi-plus-square" name="addlog" id="addlog">
                        <?php while ($logRow = mysqli_fetch_assoc($result_product)) { ?>
                            <div>
                                <p><?php echo $logRow['itemLogs']; ?></p>
                            </div>
                        <?php } ?>
                        <?php mysqli_data_seek($result_product, 0); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal2" tabindex="-1" aria-labelledby="modal1Label" aria-hidden="true">
        <form id="save" method="POST" enctype="multipart/form-data">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal1Label">
                            <?php echo htmlspecialchars($row_card_product["itemName"]) ?>

                        </h1>
                        <button type=button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Item:</label>
                                        <input readonly="readonly" type="text" name="item" id="item" class="form-control" placeholder="" value="<?php echo htmlspecialchars($row_card_product["itemName"], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Serienummer:</label>
                                        <input readonly="readonly" type="text" name="item" id="item" class="form-control" placeholder="" value="<?php echo htmlspecialchars($row_card_product["itemNumber"], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Categorie:</label>
                                        <select class="form-select" id="categorie" name="categorie" disabled>
                                            <?php
                                            include('includes/db_connection.php');
                                            $categories = mysqli_query($conn, "SELECT * FROM categories");
                                            while ($category = mysqli_fetch_array($categories)) {
                                                $selected = ($category['catId'] == $row_cat['catId']) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $category['catId']; ?>" <?php echo $selected; ?>>
                                                    <?php echo $category['catName']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="d-flex mb-3" style="margin-bottom: 0px!important;">
                                        <div class="form-group p-2" style="padding-left: 0px!important;">
                                            <label>Datum uitgeleend:</label>
                                            <input readonly="readonly" type="date" name="datumuit" value="<?php echo htmlspecialchars($row_card_product["itemDin"], ENT_QUOTES, 'UTF-8'); ?>" id="datumuit" class="form-control" placeholder="">
                                        </div>

                                        <div class="form-group p-2">
                                            <label>Datum retour:</label>
                                            <input readonly="readonly" type="date" name="datumin" value="<?php echo htmlspecialchars($row_card_product["itemDout"], ENT_QUOTES, 'UTF-8'); ?>" id="datumin" class="form-control" placeholder="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Huidige staat:</label><br>
                                        <select disabled class="form-select" id="staat" name="staat">
                                            <option value="Beschikbaar">Beschikbaar</option>
                                            <option value="Reparatie">Reparatie</option>
                                            <option value="Kapot">Kapot</option>
                                            <option value="Anders">Anders (zie omschrijving)</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Omschrijving:</label>
                                        <textarea rows="7" name="omschrijving" value="<?php echo htmlspecialchars($row_card_product["itemDescription"], ENT_QUOTES, 'UTF-8'); ?>" id="omschrijving" class="form-control" readonly="readonly"></textarea>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label>Foto:</label>
                                        <img style="height: 500px; width: 550px;" src="data:image/jpeg;base64,<?php echo base64_encode($row_card_product['itemPicture']); ?>" alt="<?php echo $row_card_product['itemName']; ?>">
                                    </div>
                                </div>
                            </div>
                            <!-- Hier logs -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                        
                    </div>
                </div>
            </div>
    </div>

    </form>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"> </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>

<?php
//include '../includes/footer.php'; 
?>