<?php
include("./includes/auth_session.php");
?>
<?php if ($_SESSION['id']) : ?>
  <?php
  header("Location: safe_redirection.php?target=https://www.dashboard.com");
  ?>
<?php else : ?>
    <?php header("Location:login.php") ?>
<?php endif; ?>
