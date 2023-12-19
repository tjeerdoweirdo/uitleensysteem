<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="icon" href="../images/logotab.png" type="image/png">
  <style>
    body {
      background: #565353;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }

    .navbar {
      background: linear-gradient(to left, #383737, #000000);
    }

    .navbar-nav .nav-link {
      color: white !important;
    }
  </style>
</head>

<body class="d-flex flex-column min-vh-100">
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a href="index.php">
        <img src="./images/logo.png" height="70" alt="logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="admindashboard.php"><h4>Admin Dashboard</h4></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="add_item.php"><h4>Add Item</h4></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="gebruikertvg.php"><h4>Gebruikertvg</h4></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php"><h4>Logout</h4></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
</body>

</html>
