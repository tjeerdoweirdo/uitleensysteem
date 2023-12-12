<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Add Bootstrap CSS (you can replace this link with the latest version) -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

  <style>
    
    footer {
      background-color: #333; 
      padding: 10px; 
      text-align: center; 
      position: fixed;
      bottom: 0;
      width: 100%;
      font-size: 10px; 
      color: white; 
      display: flex;
      flex-direction: column;
      justify-content: center; 
      align-items: center; 
      height: 13vh; 
    }

    .footer-section {
      max-width: 300px; 
      margin: 0 auto; 
    }
    /* Voeg een beetje ruimte toe tussen de lijsten */
    ul {
      padding: 0;
      list-style: none;
    }

    li {
      margin-bottom: 4px;
    }
  </style>
</head>

<body>

  <footer class="py-2"> <!-- Smaller padding -->
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <h4>Contactgegevens</h4>
          <p>UitleenService BV
            Adresstraat 123, 1234 AB Stad
            Email: info@uitleenservice.nl
            Telefoon: 0123-456789</p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
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
