<?php
$page_files = array(
    "http://localhost/project/about.php" => "http://localhost/project/about.php",
    "http://localhost/project/dashboard.php" => "http://localhost/project/dashboard.php",
);

// if (isset($_GET['target'])) 
if (in_array($_GET["target"], array_keys($page_files))) {
    $page = $page_files[$_GET['target']];
    header("Location:" . $page);
} else {

    header("Location:" . $page_files["http://localhost/project/about.php"]);
}

// http://localhost/project/redirection.php?target=https://www.attacker.com
// header("Location: " .  $page_files[$_GET['target']]);