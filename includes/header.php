<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="icon" href="%PUBLIC_URL%/favicon.ico" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="theme-color" content="#000000" />
  <meta name="description" content="Feedback is a web application that allows you to send feedback to your users." />
  <link rel="apple-touch-icon" href="%PUBLIC_URL%/logo192.png" />

  <link rel="manifest" href="%PUBLIC_URL%/manifest.json" />
  <title>It Sec</title>

  <!-- <script src="../public/assets/js/tailwind.js" ></script> -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            clifford: '#da373d',
          }
        }
      }
    }
  </script>
</head>

<body>

  <div class="nav-bar">
    <?php
    include("navbar.php");
    ?>
  </div>