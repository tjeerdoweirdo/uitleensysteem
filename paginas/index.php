<!DOCTYPE html>
<html lang="nl">

<head
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

        .hero {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar {
            margin-bottom: 20px;
            position: relative;
        }

        /* Add this style for autocomplete results */
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
            flex: 0 0 calc(33.33% - 20px);
            margin: 10px;
            max-width: 300px;
            box-sizing: border-box;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card img {
            width: 100%;
            height: auto;
        }

        .card-body {
            padding: 16px;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .card-text {
            font-size: 14px;
            flex: 1;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .card {
                flex: 0 0 calc(50% - 20px);
            }
        }
    </style>

   
    <script>
        $(document).ready(function () {
            $('#search').keyup(function () {
                var query = $(this).val();

                if (query.length >= 2) {
                    $.ajax({
                        url: '../includes/db_connection.php',
                        method: 'GET',
                        data: { query: query },
                        success: function (data) {
                            try {
                                var results = JSON.parse(data);
                                var autocompleteResults = $('#autocomplete-results');
                                autocompleteResults.empty();

                                results.forEach(function (result) {
                                    autocompleteResults.append('<div>' + result + '</div>');
                                });

                                autocompleteResults.show();
                            } catch (error) {
                                console.error('Error parsing JSON:', error);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Ajax request error:', error);
                        }
                    });
                } else {
                    $('#autocomplete-results').hide();
                }
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('#autocomplete-results').length) {
                    $('#autocomplete-results').hide();
                }
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

        <div class="card-container">
        </div>
    </main>
</body>

</html>

<?php
include("../includes/db_connection.php");




if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $sql = "SELECT itemName FROM items WHERE itemName LIKE '%$query%'";
    $result = mysqli_query($db_connection, $sql);

    if (!$result) {
        die('Error executing query: ' . mysqli_error($db_connection));
    }

    $data = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row['itemName'];
    }

    echo json_encode($data);
}
?>

 