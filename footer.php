<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Add Bootstrap CSS (you can replace this link with the latest version) -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

  <style>
    /* Algemene stijl voor de footer */
    footer {
      background-color: #333;
      padding: 10px; /* Kleinere padding */
      text-align: right; /* Rechts uitlijnen */
      position: fixed;
      bottom: 0;
      width: 100%;
    }

    /* Stijl voor de contactgegevens */
    .footer-section {
      max-width: 300px; /* Beperk de breedte van de contactgegevens */
      margin: 0 auto; /* Centreer de contactgegevens binnen de footer */
    }

    /* Voeg een beetje ruimte toe tussen de lijsten */
    ul {
      padding: 0;
      list-style: none;
    }

    li {
      margin-bottom: 8px;
    }
  </style>
</head>
<body>

  <footer class="bg-light py-3">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <h4>Contactgegevens</h4>
          <p>UitleenService BV</p>
          <p>Adresstraat 123, 1234 AB Stad</p>
          <p>Email: info@uitleenservice.nl</p>
          <p>Telefoon: 0123-456789</p>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-12 text-right">
          <p>&copy; 2023 UitleenService BV. Alle rechten voorbehouden.</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Add Bootstrap JS (optional, only if you need Bootstrap features that require JS) -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>