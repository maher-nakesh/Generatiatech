<!DOCTYPE html>
<html lang="en">

<head>
  <title>mohammad nakshbandi</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <style>
    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

    }

    h1 {
      font-size: 28px;
      color: #333;
      text-align: center;
      margin-bottom: 20px;
    }

    p {
      font-size: 18px;
      color: #555;
      line-height: 1.6;
    }

    .contact-info {
      margin-top: 20px;
    }

    .contact-info h2 {
      font-size: 24px;
      color: #333;
    }

    .contact-info ul {
      list-style: none;
      padding: 0;
    }

    .contact-info li {
      margin-bottom: 10px;
      display: flex;
      align-items: center;
    }

    .contact-info i {
      font-size: 24px;
      color: #007bff;
      margin-right: 10px;
    }
  </style>
  </style>

</head>

<body>
  <!-- Your content goes here -->
  <?php
  // Include the header
  include './includes/header.php';
  ?>
  <div class="container">
    <!-- Content here -->
    <h1>Contacts</h1>
    <p>If you have any questions or inquiries, please feel free to reach out to us using the contact information below:</p>

    <div class="contact-info">
      <h2>Contact Information</h2>
      <ul>
        <li>
          <i class="fa fa-map-marker"></i>
          123 Main Street, City, Country
        </li>
        <li>
          <i class="fa fa-phone"></i>
          Phone: +1 (123) 456-7890
        </li>
        <li>
          <i class="fa fa-envelope"></i>
          Email: info@example.com
        </li>
      </ul>
    </div>

  </div>
  <?php
  include './includes/footer.php';

  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.min.js"></script>

</body>

</html>