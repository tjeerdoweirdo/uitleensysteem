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
            <h1><?php echo $row_cat["catName"]; ?></h1>
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

                echo "<div class='product_card fade-in-row' onclick='(" . $row_product["itemId"] . ")'>";
                echo "<img src='" . htmlspecialchars($row_product["itemPicture"], ENT_QUOTES, 'UTF-8') . "' alt='Product Photo'>";
                echo "<p>" . htmlspecialchars($row_product["itemName"], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>" . htmlspecialchars($row_product["itemNumber"], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>" . $status . "</p>";
                echo "<td><button type='button' class='btn btn-primary meer' data-bs-toggle='modal' data-bs-target='#modal1'>Item bewerken</button></td>";
                echo "<td><button type='button' class='btn btn-dark logs' data-bs-toggle='modal' data-bs-target='#logs'><i class='bi bi-journal-text'></i></button></td>";
                echo "<td><button type='button' class='btn btn-danger verwijder' data-bs-target='#verwijdermodal' data-bs-toggle='verwijdermodal'><i class='bi bi-trash'></i></button></td>";
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
                            <?php echo $row['itemName'] ?>

                        </h1>
                        <button type=button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="container">

                            <!-- Modal inhoud -->
                            <input type="hidden" name="update_id" id="update_id">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Item:</label>
                                        <input type="text" name="item" id="item" class="form-control" placeholder="">
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
                                                $selected = ($category['catId'] == $row['catId']) ? 'selected' : '';
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
                                        <img style="height: 500px; width: 550px;" src="data:image/jpeg;base64,<?php echo base64_encode($row['itemPicture']); ?>" alt="<?php echo $row['itemName']; ?>">
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
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"> </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>

<script>
    $(document).ready(function() {
        $('.verwijder').on('click', function() {
            $('#verwijdermodal').modal('show');

            console.log('Delete ID:', $('#delete_id').val());

            var $tr = $(this).closest('tr');
            var itemId = $tr.data('itemItemid');


            $('#delete_id').val(itemId);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.meer').on('click', function() {
            $('#modal1').modal('show');

            var $tr = $(this).closest('tr');

            var itemId = $tr.data('item-itemid');
            var itemName = $tr.data('item-name');
            var itemDescription = $tr.data('item-description');
            var itemNumber = $tr.data('item-number');
            var itemDout = $tr.data('item-dout');
            var itemDin = $tr.data('item-din');
            var itemPicture = $tr.data('item-picture');
            var itemState = $tr.data('item-state');

            $('#modal1Label').text(itemName);

            $('#update_id').val(itemId);
            $('#item').val(itemName);
            $('#omschrijving').val(itemDescription);
            $('#nummer').val(itemNumber);
            $('#datumuit').val(itemDout);
            $('#datumin').val(itemDin);
            $('#staat').val(itemState);
            $('#foto').val(itemPicture);


        });
    });
</script>
<?php
//include '../includes/footer.php'; 
?>