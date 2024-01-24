<?php
include("../includes/db_connection.php");

if (isset($_GET['query'])) {
    $searchTerm = $_GET['query'];

    $itemSearchSql = "SELECT itemName FROM items WHERE itemName LIKE '%{$searchTerm}%' LIMIT 5";
    $itemSearchResult = $conn->query($itemSearchSql);

    $results = array();
    while ($row = $itemSearchResult->fetch_assoc()) {
        $results[] = $row['itemName'];
    }

    echo json_encode($results);
    exit();
}

$categorySql = "SELECT catName, catPicture, catId FROM categories LIMIT 6";
$categoryResult = $conn->query($categorySql);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>elektronica lenen App</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #333;
            color: #fff;
        }

        .login-btn {
            text-decoration: none;
            color: #fff;
            padding: 8px 16px;
            border-radius: 4px;
            background-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .login-btn:hover {
            background-color: #0056b3;
        }

        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .search-bar {
            margin-bottom: 20px;
            position: relative;
        }

        #autocomplete-results {
            position: absolute;
            width: 100%;
            max-height: 150px;
            overflow-y: auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: none;
        }

        #autocomplete-results div {
            padding: 8px;
            cursor: pointer;
        }

        #autocomplete-results div:hover {
            background-color: #f0f0f0;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .card {
            margin: 15px;
            width: 250px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-img-top {
            max-height: 100%;
            object-fit: fill;
        }

        .card-body {
            padding: 15px;
        }

        .card-footer {
            text-align: center;
            padding: 15px;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#search').keyup(function() {
                var query = $(this).val();

                if (query.length >= 2) {
                    $.ajax({
                        url: 'index.php',
                        method: 'GET',
                        data: {
                            query: query
                        },
                        success: function(data) {
                            try {
                                var results = JSON.parse(data);
                                var autocompleteResults = $('#autocomplete-results');
                                autocompleteResults.empty();

                                results.forEach(function(result) {
                                    autocompleteResults.append('<div>' + result + '</div>');
                                });

                                autocompleteResults.show();
                            } catch (error) {
                                console.error('Error parsing JSON:', error);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Ajax request error:', error);
                        }
                    });
                } else {
                    $('#autocomplete-results').hide();
                }
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#autocomplete-results').length) {
                    $('#autocomplete-results').hide();
                }
            });
            $(document).on('click', '.view-details-btn', function(e) {
                e.preventDefault();

                var categoryId = $(this).data('catId');

                window.location.href = 'products.php?id=' + categoryId;
            });
        });
    </script>
</head>

<body>
    <header>
        <h1>Uitleen systeem</h1>
        <a href="login.php" class="login-btn">Inloggen</a>
    </header>

    <main>
        <section class="hero">
            <h2>Deel leen overzicht</h2>
            <p>de overzicht voor alle elektronica</p>
        </section>
        <section class="search-bar">
            <input type="text" id="search" name="search" placeholder="Zoeken...">
            <div id="autocomplete-results"></div>
        </section>
        <section class="card-container">
            <?php
            while ($row = $categoryResult->fetch_assoc()) {
                echo '<div class="card mb-3">';
                $imagePath = $row['catPicture'];
                echo '<img src="' . htmlspecialchars($imagePath) . '" class="card-img-top" alt="' . htmlspecialchars($row['catName']) . '">';

                echo '<div class="card-body">';
                echo '<h3 class="card-title">' . htmlspecialchars($row['catName']) . '</h3>';
                echo '</div>';

                echo '<div class="card-footer">';
                echo '<a class="btn btn-primary view-details-btn" data-categoryid="' . htmlspecialchars($row['catId']) . '">View Details</a>';
                echo '</div>';

                echo '</div>';
            }
            ?>
        </section>
    </main>
</body>

</html>