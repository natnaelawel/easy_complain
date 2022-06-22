<?php
// whitelist

$page_files = array(
    "https://www.dashboard.com" => "http://localhost:8080/www/itsec-project/dashboard.php",
    "http://localhost/project/sample.php" => "http://localhost:8080/www/itsec-project/sample.php",
);

// if (isset($_GET['target'])) 
if (in_array($_GET["target"], array_keys($page_files))) {
    $page = $page_files[$_GET['target']];
    header("Location:" . $page);
} else {
    header("Location:" . $page_files["http://localhost/project/sample.php"]);
}

// http://localhost/project/redirection.php?target=https://www.attacker.com
// header("Location: " .  $page_files[$_GET['target']]);