<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elektronica Uitleen Admin</title>
    <style>
        body {
            display: flex;
            flex-wrap: wrap;
        }

        section {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <section>
        <h2>Elektronica Uitleen Admin</h2>

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "uitleensysteem";

        // Create a connection to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if a delete request is submitted
        if (isset($_GET['delete_id'])) {
            $deleteId = $_GET['delete_id'];

            // Construct the delete query
            $deleteQuery = "DELETE FROM uitleningen WHERE id = $deleteId";

            // Execute the delete query
            if ($conn->query($deleteQuery) === TRUE) {
                echo "<p>Item successfully deleted.</p>";
            } else {
                echo "<p>Error deleting item: " . $conn->error . "</p>";
            }
        }

        // Query to get items that are overdue
        $teLaatQuery = "SELECT * FROM uitleningen WHERE datum_inleveren < CURRENT_DATE";
        $teLaatResult = $conn->query($teLaatQuery);

        // Query to get items that are due today
        $vandaagQuery = "SELECT * FROM uitleningen WHERE datum_inleveren = CURRENT_DATE";
        $vandaagResult = $conn->query($vandaagQuery);

        // Query to get items that are currently borrowed
        $nuUitgeleendQuery = "SELECT * FROM uitleningen WHERE datum_inleveren > CURRENT_DATE";
        $nuUitgeleendResult = $conn->query($nuUitgeleendQuery);
        ?>

        <section>
            <h3>Te Laat</h3>
            <table>
                <tr>
                    <th>Voorwerp Naam</th>
                    <th>Datum van Inleveren</th>
                    <th>Student ID</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
                <?php
                while ($row = $teLaatResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['voorwerp_naam']}</td>
                            <td>{$row['datum_inleveren']}</td>
                            <td>{$row['student_id']}</td>
                            <td>{$row['category']}</td>
                            <td><a href='?delete_id={$row['id']}'>Delete</a></td>
                          </tr>";
                }
                ?>
            </table>
        </section>

        <section>
    <h3>Vandaag Inleveren</h3>
    <table>
        <tr>
            <th>Voorwerp Naam</th>
            <th>Datum van Inleveren</th>
            <th>Student ID</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
        <?php
        while ($row = $vandaagResult->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['voorwerp_naam']}</td>
                    <td>{$row['datum_inleveren']}</td>
                    <td>{$row['student_id']}</td>
                    <td>{$row['category']}</td>
                    <td><a href='?delete_id={$row['id']}'>Delete</a></td>
                  </tr>";
        }
        ?>
    </table>
</section>

<section>
    <h3>Nu Uitgeleend</h3>
    <table>
        <tr>
            <th>Voorwerp Naam</th>
            <th>Datum van Inleveren</th>
            <th>Student ID</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
        <?php
        while ($row = $nuUitgeleendResult->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['voorwerp_naam']}</td>
                    <td>{$row['datum_inleveren']}</td>
                    <td>{$row['student_id']}</td>
                    <td>{$row['category']}</td>
                    <td><a href='?delete_id={$row['id']}'>Delete</a></td>
                  </tr>";
        }
        ?>
    </table>
</section>

<?php
// Close the database connection
$conn->close();
?>
</section>
    </section>
</body>
</html>
